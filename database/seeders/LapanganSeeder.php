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
        foreach (['Lapangan A', 'Lapangan B'] as $nama) {
            $lapangan = Lapangan::updateOrCreate(
                ['nama' => $nama],
                ['deskripsi' => 'Lapangan mini soccer rumput sintetis standar FIFA Quality Pro.', 'is_active' => true]
            );

            for ($hour = 6; $hour < 23; $hour++) {
                $jamMulai = sprintf('%02d:00', $hour);
                $jamSelesai = sprintf('%02d:00', $hour + 1);
                $isClosed = $hour === 12 || $hour === 18 || $hour === 19;

                $hargaWeekdayNonmember = $hour < 15 ? 350000 : 450000;
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
}
