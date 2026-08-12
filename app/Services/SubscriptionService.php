<?php

namespace App\Services;

use App\Exceptions\SlotTidakTersediaException;
use App\Models\Lapangan;
use App\Models\LayananTambahan;
use App\Models\Member;
use App\Models\PaketLangganan;
use App\Models\Pemesanan;
use App\Notifications\LanggananAktif;
use App\Notifications\LanggananKedaluwarsa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function __construct(private AvailabilityService $availability) {}

    /**
     * Tanggal kemunculan pertama suatu hari pada/sesudah awal bulan yang dipilih (belum digeser
     * ke minggu berikutnya walau sudah lewat hari ini).
     */
    private function kemunculanPertama(int $hariIso, Carbon $bulan): Carbon
    {
        $cursor = $bulan->copy()->startOfMonth();
        while ($cursor->dayOfWeekIso !== $hariIso) {
            $cursor->addDay();
        }

        return $cursor;
    }

    /**
     * Hitung 4 tanggal (4 minggu) berturut-turut yang jatuh pada hari tertentu, dimulai dari
     * kemunculan pertama hari itu pada/sesudah awal bulan yang dipilih (dan tidak boleh sebelum hari ini).
     * Selalu tepat 4 kali per paket — bukan mengikuti jumlah kemunculan hari itu dalam bulan kalender
     * (yang bisa 4 atau 5), jadi kalau kemunculan ke-4 lewat ke bulan berikutnya, itu tetap dihitung.
     *
     * @return array<int, Carbon>
     */
    public function computeOccurrenceDates(int $hariIso, Carbon $bulan): array
    {
        $today = Carbon::today();
        $cursor = $this->kemunculanPertama($hariIso, $bulan);

        while ($cursor->lt($today)) {
            $cursor->addWeek();
        }

        $dates = [];
        for ($i = 0; $i < 4; $i++) {
            $dates[] = $cursor->copy();
            $cursor->addWeek();
        }

        return $dates;
    }

    /**
     * @param  Collection<int, \App\Models\JadwalLapangan>  $slots  slot jam berurutan yang dipilih (bisa lebih dari 1)
     * @param  array<int, int>  $addonQuantities  [layanan_tambahan_id => jumlah], lepas dari jumlah jam lapangan
     */
    public function createSubscription(Member $member, Lapangan $lapangan, Collection $slots, int $hariIso, Carbon $bulan, array $addonQuantities = []): PaketLangganan
    {
        $this->availability->validateSlotsContiguous($slots);

        $sorted = $slots->sortBy('jam_mulai')->values();
        $jamMulai = $sorted->first()->jam_mulai;
        $jamSelesai = $sorted->last()->jam_selesai;

        $tanggalList = $this->computeOccurrenceDates($hariIso, $bulan);

        if ($tanggalList[0]->isToday() && $jamMulai <= now()->format('H:i:s')) {
            throw new SlotTidakTersediaException('Slot jam ini sudah lewat untuk hari ini. Silakan pilih jam lain.');
        }

        // hari (weekday/weekend) sama untuk semua kemunculan tanggal karena hari-dalam-minggu-nya tetap
        $hargaPerPertemuan = $sorted->sum(fn ($slot) => $slot->hargaUntukTanggal($tanggalList[0], 'member'));

        $addons = LayananTambahan::whereIn('id', array_keys($addonQuantities))->where('is_active', true)->get();
        // Kunci ke 1 kalau layanan ini nggak diizinkan pakai jumlah, terlepas dari apa yang dikirim client.
        $addonPerPertemuan = $addons->sum(fn ($addon) => $addon->harga * ($addon->pakai_jumlah ? max(1, (int) $addonQuantities[$addon->id]) : 1));

        return DB::transaction(function () use ($member, $lapangan, $jamMulai, $jamSelesai, $tanggalList, $hargaPerPertemuan, $addonPerPertemuan, $addons, $addonQuantities) {
            Lapangan::where('id', $lapangan->id)->lockForUpdate()->first();

            $tanggalString = array_map(fn (Carbon $d) => $d->toDateString(), $tanggalList);

            $bentrok = Pemesanan::where('lapangan_id', $lapangan->id)
                ->whereIn('tanggal_main', $tanggalString)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->exists();

            if ($bentrok) {
                throw new SlotTidakTersediaException('Salah satu tanggal di paket ini sudah terisi. Silakan pilih jadwal lain.');
            }

            $jumlahPertemuan = count($tanggalList);

            $paket = PaketLangganan::create([
                'member_id' => $member->id,
                'lapangan_id' => $lapangan->id,
                'hari' => $tanggalList[0]->dayOfWeekIso,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'periode_mulai' => $tanggalList[0]->toDateString(),
                'periode_selesai' => end($tanggalList)->toDateString(),
                'status' => 'pending_payment',
                'total_harga' => ($hargaPerPertemuan + $addonPerPertemuan) * $jumlahPertemuan,
                'jumlah_pertemuan' => $jumlahPertemuan,
            ]);

            foreach ($tanggalList as $tanggal) {
                $pemesanan = Pemesanan::create([
                    'lapangan_id' => $lapangan->id,
                    'member_id' => $member->id,
                    'paket_langganan_id' => $paket->id,
                    'sumber' => 'member',
                    'tanggal_main' => $tanggal->toDateString(),
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'harga' => $hargaPerPertemuan,
                    'status' => 'pending',
                    'hold_expires_at' => now()->addMinutes(15),
                ]);

                foreach ($addons as $addon) {
                    $jumlah = $addon->pakai_jumlah ? max(1, (int) $addonQuantities[$addon->id]) : 1;

                    $pemesanan->layananTambahan()->attach($addon->id, [
                        'harga' => $addon->harga * $jumlah,
                        'jumlah' => $jumlah,
                    ]);
                }
            }

            return $paket;
        });
    }

    public function activateSubscription(PaketLangganan $paket): void
    {
        $paket->update(['status' => 'active']);

        $paket->pemesanan()->where('status', 'pending')->update(['status' => 'confirmed']);

        $paket->member->user->notify(new LanggananAktif($paket));
    }

    public function releaseSubscription(PaketLangganan $paket, string $status): void
    {
        $paket->update(['status' => $status]);

        $statusPemesanan = $status === 'expired' ? 'expired' : 'cancelled';
        $paket->pemesanan()->where('status', 'pending')->update(['status' => $statusPemesanan]);

        if ($status === 'expired') {
            $paket->member->user->notify(new LanggananKedaluwarsa($paket));
        }
    }
}
