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
            $table->decimal('harga_weekday_member', 10, 2)->nullable()->after('jam_selesai');
            $table->decimal('harga_weekend_member', 10, 2)->nullable()->after('harga_weekday_member');
            $table->decimal('harga_weekday_nonmember', 10, 2)->nullable()->after('harga_weekend_member');
            $table->decimal('harga_weekend_nonmember', 10, 2)->nullable()->after('harga_weekday_nonmember');
        });

        DB::table('jadwal_lapangan')->update([
            'harga_weekday_member' => DB::raw('harga_weekday'),
            'harga_weekend_member' => DB::raw('harga_weekend'),
            'harga_weekday_nonmember' => DB::raw('harga_weekday'),
            'harga_weekend_nonmember' => DB::raw('harga_weekend'),
        ]);

        Schema::table('jadwal_lapangan', function (Blueprint $table) {
            $table->dropColumn(['harga_weekday', 'harga_weekend']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_lapangan', function (Blueprint $table) {
            $table->decimal('harga_weekday', 10, 2)->nullable()->after('jam_selesai');
            $table->decimal('harga_weekend', 10, 2)->nullable()->after('harga_weekday');
        });

        DB::table('jadwal_lapangan')->update([
            'harga_weekday' => DB::raw('harga_weekday_nonmember'),
            'harga_weekend' => DB::raw('harga_weekend_nonmember'),
        ]);

        Schema::table('jadwal_lapangan', function (Blueprint $table) {
            $table->dropColumn([
                'harga_weekday_member',
                'harga_weekend_member',
                'harga_weekday_nonmember',
                'harga_weekend_nonmember',
            ]);
        });
    }
};
