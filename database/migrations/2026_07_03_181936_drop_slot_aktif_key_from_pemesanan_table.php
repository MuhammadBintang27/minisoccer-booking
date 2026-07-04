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
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropUnique(['slot_aktif_key']);
            $table->dropColumn('slot_aktif_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->string('slot_aktif_key', 60)
                ->nullable()
                ->virtualAs("CASE WHEN status IN ('cancelled', 'expired') THEN NULL ELSE CONCAT(lapangan_id, '-', tanggal_main, '-', jam_mulai) END");
            $table->unique('slot_aktif_key');
        });
    }
};
