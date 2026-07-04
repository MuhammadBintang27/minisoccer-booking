<?php

namespace App\Jobs;

use App\Models\PaketLangganan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Services\SubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessMidtransNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(private int $pembayaranId, private array $payload) {}

    public function handle(SubscriptionService $subscriptionService): void
    {
        DB::transaction(function () use ($subscriptionService) {
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

            if ($payable instanceof Pemesanan) {
                $payable->update([
                    'status' => match ($statusBaru) {
                        'settlement' => 'confirmed',
                        'expire' => 'expired',
                        'deny', 'cancel' => 'cancelled',
                        default => $payable->status,
                    },
                ]);
            }

            if ($payable instanceof PaketLangganan) {
                match ($statusBaru) {
                    'settlement' => $subscriptionService->activateSubscription($payable),
                    'expire' => $subscriptionService->releaseSubscription($payable, 'expired'),
                    'deny', 'cancel' => $subscriptionService->releaseSubscription($payable, 'cancelled'),
                    default => null,
                };
            }
        });
    }
}
