<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PaketLangganan extends Model
{
    protected $table = 'paket_langganan';

    protected $fillable = [
        'member_id',
        'lapangan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'periode_mulai',
        'periode_selesai',
        'status',
        'total_harga',
        'jumlah_pertemuan',
    ];

    protected function casts(): array
    {
        return [
            'periode_mulai' => 'date',
            'periode_selesai' => 'date',
            'total_harga' => 'decimal:2',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function pembayaran(): MorphOne
    {
        return $this->morphOne(Pembayaran::class, 'payable');
    }

    public function sisaPertemuan(): int
    {
        return $this->pemesanan()
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('tanggal_main', '>=', now()->toDateString())
            ->count();
    }
}
