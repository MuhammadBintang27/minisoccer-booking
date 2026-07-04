<?php

namespace App\Notifications;

use App\Models\PaketLangganan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LanggananAktif extends Notification implements ShouldQueue
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
            ->subject('Paket Bulanan Aktif - Soccer Bumi Teuku Umar')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Pembayaran paket bulanan kamu berhasil dan sudah aktif.')
            ->line('Lapangan: '.$this->paket->lapangan->nama)
            ->line('Jam: '.substr($this->paket->jam_mulai, 0, 5).'-'.substr($this->paket->jam_selesai, 0, 5))
            ->line('Jumlah pertemuan: '.$this->paket->jumlah_pertemuan.'x')
            ->line('Periode: '.$this->paket->periode_mulai->translatedFormat('d M Y').' - '.$this->paket->periode_selesai->translatedFormat('d M Y'))
            ->action('Lihat Detail Paket', route('member.langganan.show', $this->paket))
            ->line('Terima kasih sudah booking di Soccer Bumi Teuku Umar!');
    }
}
