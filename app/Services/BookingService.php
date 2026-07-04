<?php

namespace App\Services;

use App\Exceptions\SlotTidakTersediaException;
use App\Models\Lapangan;
use App\Models\LayananTambahan;
use App\Models\Member;
use App\Models\PaketLangganan;
use App\Models\Pemesanan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(
        private AvailabilityService $availability,
        private SubscriptionService $subscriptionService,
    ) {}

    /**
     * @param  Collection<int, \App\Models\JadwalLapangan>  $slots  slot jam berurutan yang dipilih (bisa lebih dari 1)
     * @param  array<int>  $addonIds
     */
    public function createGuestBooking(Lapangan $lapangan, Collection $slots, Carbon $tanggal, string $namaTamu, string $noHpTamu, array $addonIds = []): Pemesanan
    {
        return $this->createBooking($lapangan, $slots, $tanggal, $addonIds, [
            'sumber' => 'guest',
            'nama_tamu' => $namaTamu,
            'no_hp_tamu' => $noHpTamu,
        ]);
    }

    /**
     * @param  Collection<int, \App\Models\JadwalLapangan>  $slots
     * @param  array<int>  $addonIds
     */
    public function createMemberBooking(Lapangan $lapangan, Collection $slots, Carbon $tanggal, Member $member, array $addonIds = []): Pemesanan
    {
        return $this->createBooking($lapangan, $slots, $tanggal, $addonIds, [
            'sumber' => 'member',
            'member_id' => $member->id,
        ]);
    }

    /**
     * @param  Collection<int, \App\Models\JadwalLapangan>  $slots
     * @param  array<int>  $addonIds
     */
    private function createBooking(Lapangan $lapangan, Collection $slots, Carbon $tanggal, array $addonIds, array $extra): Pemesanan
    {
        $this->availability->validateSlotsContiguous($slots);

        $sorted = $slots->sortBy('jam_mulai')->values();
        $jamMulai = $sorted->first()->jam_mulai;
        $jamSelesai = $sorted->last()->jam_selesai;
        $harga = $sorted->sum(fn ($slot) => $slot->hargaUntukTanggal($tanggal, $extra['sumber']));
        $jumlahJam = $sorted->count();

        return DB::transaction(function () use ($lapangan, $tanggal, $jamMulai, $jamSelesai, $harga, $jumlahJam, $addonIds, $extra) {
            Lapangan::where('id', $lapangan->id)->lockForUpdate()->first();

            $bentrok = Pemesanan::where('lapangan_id', $lapangan->id)
                ->whereDate('tanggal_main', $tanggal)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->exists();

            if ($bentrok) {
                throw new SlotTidakTersediaException;
            }

            $pemesanan = Pemesanan::create([
                ...$extra,
                'lapangan_id' => $lapangan->id,
                'tanggal_main' => $tanggal->toDateString(),
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'harga' => $harga,
                'status' => 'pending',
                'hold_expires_at' => now()->addMinutes(15),
            ]);

            $this->attachAddons($pemesanan, $addonIds, $jumlahJam);

            return $pemesanan;
        });
    }

    /**
     * Harga add-on dikali jumlah jam yang dipesan (misal fotografer 1 jam main = 1x harga, 2 jam main = 2x harga).
     *
     * @param  array<int>  $addonIds
     */
    public function attachAddons(Pemesanan $pemesanan, array $addonIds, int $jumlahJam = 1): void
    {
        if (empty($addonIds)) {
            return;
        }

        $addons = LayananTambahan::whereIn('id', $addonIds)->where('is_active', true)->get();

        foreach ($addons as $addon) {
            $pemesanan->layananTambahan()->attach($addon->id, ['harga' => $addon->harga * $jumlahJam]);
        }
    }

    public function expireStaleHolds(): int
    {
        $stale = Pemesanan::where('status', 'pending')
            ->where('hold_expires_at', '<', now())
            ->get();

        $paketIds = $stale->pluck('paket_langganan_id')->filter()->unique();

        $jumlah = Pemesanan::whereIn('id', $stale->pluck('id'))->update(['status' => 'expired']);

        // Kalau semua pertemuan sebuah paket_langganan sudah expired tapi paketnya sendiri
        // belum ikut expired (masih pending_payment), status paket jadi tidak sinkron dengan
        // pertemuannya — member masih bisa buka tombol bayar padahal slotnya sudah dilepas.
        PaketLangganan::whereIn('id', $paketIds)
            ->where('status', 'pending_payment')
            ->whereDoesntHave('pemesanan', fn ($q) => $q->where('status', 'pending'))
            ->get()
            ->each(fn (PaketLangganan $paket) => $this->subscriptionService->releaseSubscription($paket, 'expired'));

        return $jumlah;
    }
}
