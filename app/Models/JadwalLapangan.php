<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class JadwalLapangan extends Model
{
    protected $table = 'jadwal_lapangan';

    protected $fillable = [
        'lapangan_id',
        'jam_mulai',
        'jam_selesai',
        'harga_weekday_member',
        'harga_weekend_member',
        'harga_weekday_nonmember',
        'harga_weekend_nonmember',
        'is_closed',
    ];

    protected function casts(): array
    {
        return [
            'is_closed' => 'boolean',
            'harga_weekday_member' => 'decimal:2',
            'harga_weekend_member' => 'decimal:2',
            'harga_weekday_nonmember' => 'decimal:2',
            'harga_weekend_nonmember' => 'decimal:2',
        ];
    }

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class);
    }

    /**
     * @param  string  $sumber  'member' atau 'guest'
     */
    public function hargaUntukTanggal(Carbon $tanggal, string $sumber = 'guest'): float
    {
        $isWeekend = $tanggal->isWeekend();
        $isMember = $sumber === 'member';

        return (float) match (true) {
            $isMember && $isWeekend => $this->harga_weekend_member,
            $isMember => $this->harga_weekday_member,
            $isWeekend => $this->harga_weekend_nonmember,
            default => $this->harga_weekday_nonmember,
        };
    }
}
