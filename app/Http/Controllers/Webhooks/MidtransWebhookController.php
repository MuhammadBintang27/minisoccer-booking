<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessMidtransNotification;
use App\Models\LogPembayaran;
use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans): JsonResponse
    {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $valid = $orderId
            && $midtrans->verifySignature(
                $orderId,
                (string) ($payload['status_code'] ?? ''),
                (string) ($payload['gross_amount'] ?? ''),
                (string) ($payload['signature_key'] ?? ''),
            );

        if (! $valid) {
            abort(403, 'Signature tidak valid.');
        }

        $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->first();

        if (! $pembayaran) {
            return response()->json(['message' => 'ok']);
        }

        LogPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'event_type' => $payload['transaction_status'] ?? 'unknown',
            'payload' => $payload,
        ]);

        ProcessMidtransNotification::dispatch($pembayaran->id, $payload);

        return response()->json(['message' => 'ok']);
    }
}
