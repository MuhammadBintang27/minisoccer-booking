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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangan')->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('member')->nullOnDelete();
            $table->enum('sumber', ['member', 'guest']);
            $table->string('nama_tamu')->nullable();
            $table->string('no_hp_tamu')->nullable();
            $table->date('tanggal_main');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('harga', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'expired', 'cancelled', 'completed'])->default('pending');
            $table->timestamp('hold_expires_at')->nullable();
            $table->timestamps();

            $table->string('slot_aktif_key', 60)
                ->nullable()
                ->virtualAs("CASE WHEN status IN ('cancelled', 'expired') THEN NULL ELSE CONCAT(lapangan_id, '-', tanggal_main, '-', jam_mulai) END");
            $table->unique('slot_aktif_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
