<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(Request $request, AvailabilityService $availability): View
    {
        $daftarLapangan = Lapangan::orderBy('nama')->get();

        $lapangan = $daftarLapangan->firstWhere('id', (int) $request->query('lapangan_id'))
            ?? $daftarLapangan->first();

        $bulan = $request->query('bulan')
            ? Carbon::parse($request->query('bulan').'-01')
            : Carbon::today()->startOfMonth();

        $kalender = $lapangan ? $availability->buildKalender($lapangan, $bulan) : [];

        $tanggal = null;
        $slots = collect();

        if ($lapangan && $request->query('tanggal')) {
            $tanggal = Carbon::parse($request->query('tanggal'));
            $slots = $availability->getSlotsForDate($lapangan, $tanggal);
        }

        return view('admin.jadwal', [
            'daftarLapangan' => $daftarLapangan,
            'lapangan' => $lapangan,
            'bulan' => $bulan,
            'kalender' => $kalender,
            'tanggal' => $tanggal,
            'slots' => $slots,
        ]);
    }
}
