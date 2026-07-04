<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Pemesanan extends Model
{
    /**
     * DP minimum sebagai persentase dari total harga sebelum booking dianggap terkonfirmasi.
     */
    public const DP_PERSEN = 0.25;

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

    /**
     * Hanya terisi untuk booking guest (sumber=guest) — pembayaran member
     * ada di paket_langganan induknya, bukan di sini.
     */
    public function pembayaran(): MorphMany
    {
        return $this->morphMany(Pembayaran::class, 'payable');
    }

    public function layananTambahan(): BelongsToMany
    {
        return $this->belongsToMany(LayananTambahan::class, 'pemesanan_layanan_tambahan')
            ->withPivot('harga')
            ->withTimestamps();
    }

    public function totalHarga(): float
    {
        return (float) $this->harga + (float) $this->layananTambahan->sum('pivot.harga');
    }

    public function totalDibayar(): float
    {
        if ($this->sumber === 'member') {
            return $this->paketLangganan?->totalDibayar() ?? 0.0;
        }

        return (float) $this->pembayaran()->where('status', 'settlement')->sum('jumlah');
    }

    public function sisaTagihan(): float
    {
        return max(0.0, $this->totalHarga() - $this->totalDibayar());
    }

    public function dpMinimum(): float
    {
        return round($this->totalHarga() * self::DP_PERSEN);
    }

    public function statusPembayaran(): string
    {
        if ($this->sumber === 'member') {
            return $this->paketLangganan?->statusPembayaran() ?? '-';
        }

        $dibayar = $this->totalDibayar();

        return match (true) {
            $dibayar <= 0 => 'Belum bayar',
            $dibayar >= $this->totalHarga() => 'Lunas',
            default => 'DP (Rp'.number_format($dibayar, 0, ',', '.').'/Rp'.number_format($this->totalHarga(), 0, ',', '.').')',
        };
    }
}
