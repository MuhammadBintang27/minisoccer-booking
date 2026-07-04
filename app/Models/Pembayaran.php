<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'payable_type',
        'payable_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'jumlah',
        'metode_pembayaran',
        'status',
        'raw_notification_payload',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'raw_notification_payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function logPembayaran(): HasMany
    {
        return $this->hasMany(LogPembayaran::class);
    }
}
