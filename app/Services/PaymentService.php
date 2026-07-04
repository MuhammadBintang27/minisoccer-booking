<?php

namespace App\Services;

use App\Models\PaketLangganan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\User;
use App\Notifications\PembayaranLangganan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private MidtransService $midtrans,
        private SubscriptionService $subscriptionService,
    ) {}

    /**
     * Ambil pembayaran pending dengan nominal yang sama (kalau ada), atau buat baru + Snap token.
     * Satu payable bisa punya beberapa baris pembayaran (DP, cicilan, pelunasan).
     *
     * @return array{pembayaran: Pembayaran, snap_token: string}
     */
    public function getOrCreateSnapTransaction(Model $payable, float $jumlah, string $orderPrefix, array $customerDetails): array
    {
        $pembayaran = Pembayaran::where('payable_type', $payable::class)
            ->where('payable_id', $payable->id)
            ->where('status', 'pending')
            ->where('channel', 'midtrans')
            ->where('jumlah', $jumlah)
            ->first();

        if (! $pembayaran) {
            $pembayaran = Pembayaran::create([
                'payable_type' => $payable::class,
                'payable_id' => $payable->id,
                'channel' => 'midtrans',
                'midtrans_order_id' => $orderPrefix.'-'.$payable->id.'-'.now()->timestamp,
                'jumlah' => $jumlah,
                'status' => 'pending',
            ]);
        }

        $snapToken = $this->midtrans->createSnapToken([
            'transaction_details' => [
                'order_id' => $pembayaran->midtrans_order_id,
                'gross_amount' => (int) $jumlah,
            ],
            'customer_details' => $customerDetails,
        ]);

        return ['pembayaran' => $pembayaran, 'snap_token' => $snapToken];
    }

    /**
     * Admin mencatat pembayaran cash (kasir) — langsung settlement, tanpa lewat Midtrans.
     */
    public function recordCashPayment(Model $payable, float $jumlah, User $admin): Pembayaran
    {
        return DB::transaction(function () use ($payable, $jumlah, $admin) {
            $pembayaran = Pembayaran::create([
                'payable_type' => $payable::class,
                'payable_id' => $payable->id,
                'channel' => 'cash',
                'midtrans_order_id' => null,
                'jumlah' => $jumlah,
                'status' => 'settlement',
                'paid_at' => now(),
                'dikonfirmasi_oleh' => $admin->id,
            ]);

            $this->applySettlementEffects($payable, $pembayaran);

            return $pembayaran;
        });
    }

    /**
     * Konfirmasi booking/paket begitu total yang sudah dibayar (dari channel manapun) mencapai
     * ambang DP minimum untuk pertama kalinya. Aman dipanggil berkali-kali (idempotent) — kalau
     * status sudah tidak "pending"/"pending_payment" lagi, tidak melakukan apa-apa.
     *
     * Untuk paket member: pembayaran pertama (DP) memicu email "paket aktif" lewat
     * activateSubscription(). Setiap pembayaran berikutnya (cicilan/pelunasan) pada paket yang
     * sudah aktif memicu email "pembayaran diterima" tersendiri, supaya member selalu dapat
     * konfirmasi tiap kali bayar, bukan cuma sekali di awal.
     */
    public function applySettlementEffects(Model $payable, Pembayaran $pembayaran): void
    {
        if ($payable instanceof Pemesanan
            && $payable->status === 'pending'
            && $payable->totalDibayar() >= $payable->dpMinimum()
        ) {
            $payable->update(['status' => 'confirmed']);
        }

        if ($payable instanceof PaketLangganan) {
            if ($payable->status === 'pending_payment' && $payable->totalDibayar() >= $payable->dpMinimum()) {
                $this->subscriptionService->activateSubscription($payable);
            } elseif ($payable->status === 'active') {
                $payable->member->user->notify(new PembayaranLangganan($payable, $pembayaran));
            }
        }
    }
}
