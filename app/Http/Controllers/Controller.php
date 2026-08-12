<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Gabungkan input `layanan_tambahan_id[]` (dicentang) dengan `layanan_tambahan_jumlah[id]`
     * (jumlah per addon) jadi satu peta [id => jumlah] buat dikirim ke BookingService/SubscriptionService.
     *
     * @param  array<string, mixed>  $validated
     * @return array<int, int>
     */
    protected function addonQuantities(array $validated): array
    {
        $ids = $validated['layanan_tambahan_id'] ?? [];
        $jumlahPerId = $validated['layanan_tambahan_jumlah'] ?? [];

        return collect($ids)
            ->mapWithKeys(fn ($id) => [(int) $id => max(1, (int) ($jumlahPerId[$id] ?? 1))])
            ->all();
    }
}
