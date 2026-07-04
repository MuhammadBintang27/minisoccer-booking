<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\PaketLangganan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Ambil semua pembayaran yang sudah settlement dalam rentang tanggal.
     */
    public function pembayaranSettlement(Carbon $dari, Carbon $sampai): Collection
    {
        return Pembayaran::with('payable')
            ->where('status', 'settlement')
            ->whereBetween('paid_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])
            ->orderByDesc('paid_at')
            ->get();
    }

    /**
     * Ringkasan pendapatan: total, dipecah member (paket_langganan) vs guest (pemesanan langsung).
     *
     * @return array{total: float, member: float, guest: float, jumlah_transaksi: int}
     */
    public function ringkasan(Collection $pembayaran): array
    {
        $member = $pembayaran->filter(fn (Pembayaran $p) => $p->payable_type === PaketLangganan::class)->sum('jumlah');
        $guest = $pembayaran->sum('jumlah') - $member;

        return [
            'total' => (float) $pembayaran->sum('jumlah'),
            'member' => (float) $member,
            'guest' => (float) $guest,
            'jumlah_transaksi' => $pembayaran->count(),
        ];
    }
}
