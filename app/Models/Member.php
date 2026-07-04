<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $table = 'member';

    protected $fillable = [
        'user_id',
        'kode_member',
        'tanggal_gabung',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_gabung' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function paketLangganan(): HasMany
    {
        return $this->hasMany(PaketLangganan::class);
    }

    public function sisaPertemuanAktif(): int
    {
        return $this->paketLangganan()
            ->where('status', 'active')
            ->get()
            ->sum(fn (PaketLangganan $paket) => $paket->sisaPertemuan());
    }
}
