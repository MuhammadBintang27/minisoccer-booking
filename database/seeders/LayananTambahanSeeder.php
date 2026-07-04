<?php

namespace Database\Seeders;

use App\Models\LayananTambahan;
use Illuminate\Database\Seeder;

class LayananTambahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LayananTambahan::updateOrCreate(
            ['nama' => 'Fotografer'],
            ['harga' => 100000, 'is_active' => true]
        );
    }
}
