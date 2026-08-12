<?php

namespace Database\Seeders;

use App\Models\JadwalLapangan;
use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lapangan = Lapangan::updateOrCreate(
            ['nama' => 'Lapangan Mini Soccer'],
            ['deskripsi' => 'Lapangan mini soccer rumput sintetis standar FIFA Quality Pro.', 'is_active' => true]
        );

        // Buka 06.00-23.00 plus 00.00-02.00 (lanjutan dini hari). Jam 02.00-06.00 sengaja tidak
        // dibuatkan barisnya sama sekali (bukan ditandai tutup) — di luar jam operasional.
        // Catatan: 00.00 & 01.00 di sini adalah jam normal pada tanggal yang sama, BUKAN hasil
        // "nyebrang" dari 23.00 — 1 booking tetap terikat 1 tanggal, jadi main jam 23.00-01.00
        // butuh 2 transaksi terpisah (23.00-00.00 di tanggal ini, 00.00-01.00 di tanggal besok).
        $jamOperasional = [...range(0, 1), ...range(6, 22)];

        foreach ($jamOperasional as $hour) {
            $jamMulai = sprintf('%02d:00', $hour);
            $jamSelesai = sprintf('%02d:00', $hour + 1);
            $isClosed = $hour === 12 || $hour === 18 || $hour === 19;

            $hargaWeekdayNonmember = ($hour >= 6 && $hour < 15) ? 350000 : 450000;
            $hargaWeekendNonmember = $hargaWeekdayNonmember + 50000;

            JadwalLapangan::updateOrCreate(
                [
                    'lapangan_id' => $lapangan->id,
                    'jam_mulai' => $jamMulai,
                ],
                [
                    'jam_selesai' => $jamSelesai,
                    'harga_weekday_member' => $hargaWeekdayNonmember - 50000,
                    'harga_weekend_member' => $hargaWeekendNonmember - 50000,
                    'harga_weekday_nonmember' => $hargaWeekdayNonmember,
                    'harga_weekend_nonmember' => $hargaWeekendNonmember,
                    'is_closed' => $isClosed,
                ]
            );
        }
    }
}
