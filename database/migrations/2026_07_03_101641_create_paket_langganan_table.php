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
        Schema::create('paket_langganan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('member')->cascadeOnDelete();
            $table->foreignId('lapangan_id')->constrained('lapangan')->cascadeOnDelete();
            $table->unsignedTinyInteger('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->enum('status', ['pending_payment', 'active', 'expired', 'cancelled', 'failed'])->default('pending_payment');
            $table->decimal('total_harga', 10, 2);
            $table->unsignedTinyInteger('jumlah_pertemuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_langganan');
    }
};
