<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

class PaketLangganan extends Model
{
    /**
     * DP minimum sebagai persentase dari total harga sebelum paket dianggap terkonfirmasi/aktif.
     */
    public const DP_PERSEN = 0.25;

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

    public function pembayaran(): MorphMany
    {
        return $this->morphMany(Pembayaran::class, 'payable');
    }

    /**
     * Harga slot lapangan per satu pertemuan (sama tiap minggu karena hari/jam/lapangan tetap).
     * Butuh relasi `pemesanan` sudah di-load supaya tidak query ulang.
     */
    public function hargaSlotPerPertemuan(): float
    {
        return (float) ($this->pemesanan->first()->harga ?? 0);
    }

    /**
     * Rincian add-on yang direplikasi ke tiap pertemuan, dijumlah totalnya per add-on
     * (bukan snapshot 1 pertemuan saja). Butuh relasi `pemesanan.layananTambahan` sudah di-load.
     */
    public function rincianAddon(): Collection
    {
        return $this->pemesanan
            ->flatMap(fn (Pemesanan $p) => $p->layananTambahan)
            ->groupBy('id')
            ->map(function (Collection $grup) {
                $addon = $grup->first();
                $addon->pivot->harga = $grup->sum('pivot.harga');

                return $addon;
            })
            ->values();
    }

    public function sisaPertemuan(): int
    {
        return $this->pemesanan()
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('tanggal_main', '>=', now()->toDateString())
            ->count();
    }

    public function totalDibayar(): float
    {
        return (float) $this->pembayaran()->where('status', 'settlement')->sum('jumlah');
    }

    public function sisaTagihan(): float
    {
        return max(0.0, (float) $this->total_harga - $this->totalDibayar());
    }

    public function dpMinimum(): float
    {
        return round((float) $this->total_harga * self::DP_PERSEN);
    }

    public function statusPembayaran(): string
    {
        $dibayar = $this->totalDibayar();
        $total = (float) $this->total_harga;

        return match (true) {
            $dibayar <= 0 => 'Belum bayar',
            $dibayar >= $total => 'Lunas',
            default => 'DP (Rp'.number_format($dibayar, 0, ',', '.').'/Rp'.number_format($total, 0, ',', '.').')',
        };
    }
}
