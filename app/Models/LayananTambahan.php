<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananTambahan extends Model
{
    protected $table = 'layanan_tambahan';

    protected $fillable = [
        'nama',
        'harga',
        'pakai_jumlah',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'pakai_jumlah' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
