<?php

namespace App\Notifications;

use App\Models\PaketLangganan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LanggananKedaluwarsa extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private PaketLangganan $paket) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Paket Bulanan Dibatalkan - Soccer Bumi Teuku Umar')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Waktu pembayaran untuk paket bulanan kamu sudah habis, sehingga semua jadwal di paket ini dilepas kembali.')
            ->line('Lapangan: '.$this->paket->lapangan->nama)
            ->line('Jam: '.substr($this->paket->jam_mulai, 0, 5).'-'.substr($this->paket->jam_selesai, 0, 5))
            ->action('Beli Paket Baru', route('member.langganan.create'))
            ->line('Silakan buat pemesanan baru kalau masih berminat.');
    }
}
