<?php

namespace App\Notifications;

use App\Models\PaketLangganan;
use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranLangganan extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private PaketLangganan $paket, private Pembayaran $pembayaran) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $sisa = $this->paket->sisaTagihan();

        $mail = (new MailMessage)
            ->subject('Pembayaran Diterima - Soccer Bumi Teuku Umar')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Pembayaran kamu sebesar Rp'.number_format($this->pembayaran->jumlah, 0, ',', '.').' untuk paket bulanan di '.$this->paket->lapangan->nama.' sudah kami terima.')
            ->line('Total sudah dibayar: Rp'.number_format($this->paket->totalDibayar(), 0, ',', '.'));

        if ($sisa > 0) {
            $mail->line('Sisa tagihan: Rp'.number_format($sisa, 0, ',', '.').'. Bisa dilunasi kapan saja, secara online maupun tunai langsung di lapangan.');
        } else {
            $mail->line('Paket kamu sekarang sudah lunas. Terima kasih!');
        }

        return $mail->action('Lihat Detail Paket', route('member.langganan.show', $this->paket));
    }
}
