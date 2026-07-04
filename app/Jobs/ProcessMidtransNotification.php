<?php

namespace App\Jobs;

use App\Models\PaketLangganan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessMidtransNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(private int $pembayaranId, private array $payload) {}

    public function handle(SubscriptionService $subscriptionService, PaymentService $paymentService): void
    {
        DB::transaction(function () use ($subscriptionService, $paymentService) {
            $pembayaran = Pembayaran::where('id', $this->pembayaranId)->lockForUpdate()->first();

            if (! $pembayaran) {
                return;
            }

            $transactionStatus = $this->payload['transaction_status'] ?? null;
            $fraudStatus = $this->payload['fraud_status'] ?? null;

            $statusBaru = match (true) {
                in_array($transactionStatus, ['settlement', 'capture'], true) && $fraudStatus !== 'deny' => 'settlement',
                in_array($transactionStatus, ['pending', 'deny', 'cancel', 'expire', 'refund'], true) => $transactionStatus,
                default => $pembayaran->status,
            };

            if ($pembayaran->status === $statusBaru) {
                return;
            }

            $pembayaran->update([
                'status' => $statusBaru,
                'metode_pembayaran' => $this->payload['payment_type'] ?? $pembayaran->metode_pembayaran,
                'midtrans_transaction_id' => $this->payload['transaction_id'] ?? $pembayaran->midtrans_transaction_id,
                'raw_notification_payload' => $this->payload,
                'paid_at' => $statusBaru === 'settlement' ? now() : $pembayaran->paid_at,
            ]);

            $payable = $pembayaran->payable()->lockForUpdate()->first();

            if ($statusBaru === 'settlement') {
                $paymentService->applySettlementEffects($payable, $pembayaran);

                return;
            }

            // Gagal/expired/dibatalkan: booking/paket cuma dibatalkan kalau ini kegagalan DP
            // pertama (masih pending/pending_payment). Kalau sudah confirmed/active dari DP
            // sebelumnya, ini cuma cicilan/pelunasan yang gagal — booking tetap jalan, sisa
            // tagihan tetap ada, member/guest bisa coba bayar lagi.
            if ($payable instanceof Pemesanan && $payable->status === 'pending') {
                $payable->update([
                    'status' => $statusBaru === 'expire' ? 'expired' : 'cancelled',
                ]);
            }

            if ($payable instanceof PaketLangganan && $payable->status === 'pending_payment') {
                $subscriptionService->releaseSubscription($payable, $statusBaru === 'expire' ? 'expired' : 'cancelled');
            }
        });
    }
}
