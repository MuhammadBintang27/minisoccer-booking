<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ExpireStaleBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-stale-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lepas slot pemesanan pending yang sudah lewat batas waktu pembayaran (hold_expires_at)';

    /**
     * Execute the console command.
     */
    public function handle(BookingService $bookingService): void
    {
        $jumlah = $bookingService->expireStaleHolds();

        $this->info("Melepas {$jumlah} pemesanan pending yang kedaluwarsa.");
    }
}
