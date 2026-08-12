<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('layanan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('harga', 10, 2);
            // Layanan berbasis paket/durasi (mis. "Fotografer 2 Jam") biarkan false — jumlah dikunci 1,
            // varian durasinya cukup dibuat sebagai baris terpisah. Layanan berbasis satuan (mis. Rompi)
            // diaktifkan supaya customer bisa isi berapa unit yang dipesan.
            $table->boolean('pakai_jumlah')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_tambahan');
    }
};
