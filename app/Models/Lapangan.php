<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lapangan extends Model
{
    use SoftDeletes;

    protected $table = 'lapangan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function jadwalLapangan(): HasMany
    {
        return $this->hasMany(JadwalLapangan::class);
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function paketLangganan(): HasMany
    {
        return $this->hasMany(PaketLangganan::class);
    }
}
