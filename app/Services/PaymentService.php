<?php

namespace App\Services;

use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Model;

class PaymentService
{
    public function __construct(private MidtransService $midtrans) {}

    /**
     * Ambil pembayaran pending yang sudah ada untuk payable ini, atau buat baru + Snap token.
     *
     * @return array{pembayaran: Pembayaran, snap_token: string}
     */
    public function getOrCreateSnapTransaction(Model $payable, float $jumlah, string $orderPrefix, array $customerDetails): array
    {
        $pembayaran = Pembayaran::where('payable_type', $payable::class)
            ->where('payable_id', $payable->id)
            ->where('status', 'pending')
            ->first();

        if (! $pembayaran) {
            $pembayaran = Pembayaran::create([
                'payable_type' => $payable::class,
                'payable_id' => $payable->id,
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
}
