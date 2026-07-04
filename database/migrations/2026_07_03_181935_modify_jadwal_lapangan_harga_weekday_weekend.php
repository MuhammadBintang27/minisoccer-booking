<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal_lapangan', function (Blueprint $table) {
            $table->decimal('harga_weekday', 10, 2)->nullable()->after('jam_selesai');
            $table->decimal('harga_weekend', 10, 2)->nullable()->after('harga_weekday');
        });

        DB::table('jadwal_lapangan')->update([
            'harga_weekday' => DB::raw('harga'),
            'harga_weekend' => DB::raw('harga'),
        ]);

        Schema::table('jadwal_lapangan', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_lapangan', function (Blueprint $table) {
            $table->decimal('harga', 10, 2)->nullable()->after('jam_selesai');
        });

        DB::table('jadwal_lapangan')->update([
            'harga' => DB::raw('harga_weekday'),
        ]);

        Schema::table('jadwal_lapangan', function (Blueprint $table) {
            $table->dropColumn(['harga_weekday', 'harga_weekend']);
        });
    }
};
