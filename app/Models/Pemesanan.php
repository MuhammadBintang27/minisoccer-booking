<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'lapangan_id',
        'member_id',
        'paket_langganan_id',
        'sumber',
        'nama_tamu',
        'no_hp_tamu',
        'tanggal_main',
        'jam_mulai',
        'jam_selesai',
        'harga',
        'status',
        'hold_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_main' => 'date',
            'harga' => 'decimal:2',
            'hold_expires_at' => 'datetime',
        ];
    }

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function paketLangganan(): BelongsTo
    {
        return $this->belongsTo(PaketLangganan::class);
    }

    public function pembayaran(): MorphOne
    {
        return $this->morphOne(Pembayaran::class, 'payable');
    }

    public function layananTambahan(): BelongsToMany
    {
        return $this->belongsToMany(LayananTambahan::class, 'pemesanan_layanan_tambahan')
            ->withPivot('harga')
            ->withTimestamps();
    }

    public function statusPembayaran(): string
    {
        if ($this->sumber === 'member') {
            return $this->paketLangganan?->pembayaran?->status ?? '-';
        }

        return $this->pembayaran?->status ?? '-';
    }

    public function totalHarga(): float
    {
        return (float) $this->harga + (float) $this->layananTambahan->sum('pivot.harga');
    }
}
