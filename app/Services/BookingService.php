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
     * @param  array<int, int>  $addonQuantities  [layanan_tambahan_id => jumlah], lepas dari jumlah jam lapangan
     */
    public function createGuestBooking(Lapangan $lapangan, Collection $slots, Carbon $tanggal, string $namaTamu, string $noHpTamu, array $addonQuantities = []): Pemesanan
    {
        return $this->createBooking($lapangan, $slots, $tanggal, $addonQuantities, [
            'sumber' => 'guest',
            'nama_tamu' => $namaTamu,
            'no_hp_tamu' => $noHpTamu,
        ]);
    }

    /**
     * @param  Collection<int, \App\Models\JadwalLapangan>  $slots
     * @param  array<int, int>  $addonQuantities  [layanan_tambahan_id => jumlah]
     */
    public function createMemberBooking(Lapangan $lapangan, Collection $slots, Carbon $tanggal, Member $member, array $addonQuantities = []): Pemesanan
    {
        return $this->createBooking($lapangan, $slots, $tanggal, $addonQuantities, [
            'sumber' => 'member',
            'member_id' => $member->id,
        ]);
    }

    /**
     * @param  Collection<int, \App\Models\JadwalLapangan>  $slots
     * @param  array<int, int>  $addonQuantities
     */
    private function createBooking(Lapangan $lapangan, Collection $slots, Carbon $tanggal, array $addonQuantities, array $extra): Pemesanan
    {
        $this->availability->validateSlotsContiguous($slots);

        $sorted = $slots->sortBy('jam_mulai')->values();
        $jamMulai = $sorted->first()->jam_mulai;
        $jamSelesai = $sorted->last()->jam_selesai;
        $harga = $sorted->sum(fn ($slot) => $slot->hargaUntukTanggal($tanggal, $extra['sumber']));

        if ($tanggal->isToday() && $jamMulai <= now()->format('H:i:s')) {
            throw new SlotTidakTersediaException('Slot jam ini sudah lewat untuk hari ini. Silakan pilih jam lain.');
        }

        return DB::transaction(function () use ($lapangan, $tanggal, $jamMulai, $jamSelesai, $harga, $addonQuantities, $extra) {
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

            $this->attachAddons($pemesanan, $addonQuantities);

            return $pemesanan;
        });
    }

    /**
     * Harga & jumlah add-on lepas dari jumlah jam lapangan yang dipesan — customer pilih sendiri
     * berapa jumlah tiap layanan (mis. fotografer dipilih sebagai varian durasi terpisah, jumlah
     * tetap 1; rompi jumlahnya bisa lebih dari 1 set).
     *
     * @param  array<int, int>  $addonQuantities  [layanan_tambahan_id => jumlah]
     */
    public function attachAddons(Pemesanan $pemesanan, array $addonQuantities): void
    {
        if (empty($addonQuantities)) {
            return;
        }

        $addons = LayananTambahan::whereIn('id', array_keys($addonQuantities))->where('is_active', true)->get();

        foreach ($addons as $addon) {
            // Kunci ke 1 kalau layanan ini nggak diizinkan pakai jumlah, terlepas dari apa yang dikirim client.
            $jumlah = $addon->pakai_jumlah ? max(1, (int) $addonQuantities[$addon->id]) : 1;

            $pemesanan->layananTambahan()->attach($addon->id, [
                'harga' => $addon->harga * $jumlah,
                'jumlah' => $jumlah,
            ]);
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
