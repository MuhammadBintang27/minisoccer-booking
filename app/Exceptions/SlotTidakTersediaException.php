<?php

namespace App\Exceptions;

use Exception;

class SlotTidakTersediaException extends Exception
{
    protected $message = 'Slot jadwal ini sudah tidak tersedia. Silakan pilih slot lain.';
}
