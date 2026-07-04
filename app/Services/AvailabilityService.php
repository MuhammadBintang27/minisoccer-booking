<?php

namespace App\Services;

use App\Exceptions\SlotTidakTersediaException;
use App\Models\Lapangan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Ambil status tiap slot jadwal sebuah lapangan untuk satu tanggal: "closed", "booked", atau "tersedia".
     *
     * Deteksi "booked" berbasis overlap rentang jam (bukan exact-match jam_mulai) supaya booking
     * multi-jam (misal 16.00-18.00) tetap menandai semua template slot jam di dalamnya sebagai booked.
     *
     * @return Collection<int, array{slot: \App\Models\JadwalLapangan, status: string, sumber: string|null}>
     */
    public function getSlotsForDate(Lapangan $lapangan, Carbon $date): Collection
    {
        $pemesananAktif = $lapangan->pemesanan()
            ->whereDate('tanggal_main', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        return $lapangan->jadwalLapangan()
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($slot) use ($pemesananAktif) {
                $pemesanan = $pemesananAktif->first(
                    fn ($p) => $p->jam_mulai < $slot->jam_selesai && $p->jam_selesai > $slot->jam_mulai
                );

                $status = match (true) {
                    $slot->is_closed => 'closed',
                    $pemesanan !== null => 'booked',
                    default => 'tersedia',
                };

                return ['slot' => $slot, 'status' => $status, 'sumber' => $pemesanan?->sumber];
            });
    }

    /**
     * Ringkasan jumlah slot bookable (total) dan sisa yang masih tersedia untuk satu tanggal.
     *
     * @return array{total: int, sisa: int}
     */
    public function countAvailableForDate(Lapangan $lapangan, Carbon $date): array
    {
        $slots = $this->getSlotsForDate($lapangan, $date);
        $total = $slots->whereIn('status', ['tersedia', 'booked'])->count();
        $sisa = $slots->where('status', 'tersedia')->count();

        return ['total' => $total, 'sisa' => $sisa];
    }

    /**
     * Bangun grid kalender satu bulan (7 kolom Sen-Min) berisi ringkasan sisa/total slot per tanggal.
     *
     * @return array<int, array<int, array{date: Carbon, total: int, sisa: int, isPast: bool}|null>>
     */
    public function buildKalender(Lapangan $lapangan, Carbon $bulan): array
    {
        $today = Carbon::today();
        $awalBulan = $bulan->copy()->startOfMonth();
        $akhirBulan = $bulan->copy()->endOfMonth();

        $cells = [];

        for ($i = 1; $i < $awalBulan->dayOfWeekIso; $i++) {
            $cells[] = null;
        }

        for ($day = 1; $day <= $akhirBulan->day; $day++) {
            $date = $awalBulan->copy()->day($day);
            $ringkasan = $this->countAvailableForDate($lapangan, $date);

            $cells[] = [
                'date' => $date,
                'total' => $ringkasan['total'],
                'sisa' => $ringkasan['sisa'],
                'isPast' => $date->lt($today),
            ];
        }

        return array_chunk($cells, 7);
    }

    /**
     * Pastikan slot yang dipilih (untuk booking multi-jam) tidak kosong, tidak ada yang tutup,
     * dan berurutan tanpa jeda (jam_selesai slot sebelumnya = jam_mulai slot berikutnya).
     *
     * @param  Collection<int, \App\Models\JadwalLapangan>  $slots
     */
    public function validateSlotsContiguous(Collection $slots): void
    {
        if ($slots->isEmpty()) {
            throw new SlotTidakTersediaException('Pilih minimal 1 slot jam.');
        }

        $sorted = $slots->sortBy('jam_mulai')->values();

        foreach ($sorted as $index => $slot) {
            if ($slot->is_closed) {
                throw new SlotTidakTersediaException('Salah satu slot yang dipilih sedang tutup.');
            }

            if ($index > 0) {
                $sebelumnya = $sorted[$index - 1];

                if (substr($sebelumnya->jam_selesai, 0, 5) !== substr($slot->jam_mulai, 0, 5)) {
                    throw new SlotTidakTersediaException('Slot jam yang dipilih harus berurutan tanpa jeda.');
                }
            }
        }
    }
}
