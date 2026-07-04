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
        Schema::create('jadwal_lapangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangan')->cascadeOnDelete();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('harga_weekday_member', 10, 2);
            $table->decimal('harga_weekend_member', 10, 2);
            $table->decimal('harga_weekday_nonmember', 10, 2);
            $table->decimal('harga_weekend_nonmember', 10, 2);
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_lapangan');
    }
};
