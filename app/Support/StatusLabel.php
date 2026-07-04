<?php

namespace App\Support;

class StatusLabel
{
    /**
     * Satu mapping dipakai bersama untuk semua status di aplikasi (pemesanan, paket_langganan,
     * pembayaran/Midtrans, member) karena beberapa nilai status sama persis lintas tabel
     * (pending, expired, cancelled, active) dan artinya konsisten di semua konteks itu.
     */
    private const LABEL = [
        'pending' => 'Menunggu Pembayaran',
        'pending_payment' => 'Menunggu Pembayaran',
        'confirmed' => 'Terkonfirmasi',
        'completed' => 'Selesai',
        'expired' => 'Kedaluwarsa',
        'expire' => 'Kedaluwarsa',
        'cancelled' => 'Dibatalkan',
        'cancel' => 'Dibatalkan',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'failed' => 'Gagal',
        'settlement' => 'Berhasil',
        'capture' => 'Berhasil',
        'deny' => 'Ditolak',
        'refund' => 'Dikembalikan',
    ];

    public static function label(?string $status): string
    {
        if ($status === null) {
            return '-';
        }

        return self::LABEL[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
