<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\PaketLangganan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function home(Request $request, AvailabilityService $availability): View
    {
        $daftarLapangan = Lapangan::where('is_active', true)->orderBy('nama')->get();

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

        return view('home', [
            'daftarLapangan' => $daftarLapangan,
            'lapangan' => $lapangan,
            'bulan' => $bulan,
            'kalender' => $kalender,
            'tanggal' => $tanggal,
            'slots' => $slots,
        ]);
    }

    public function terimaKasih(Request $request): View
    {
        $linkStatus = null;
        $pembayaran = Pembayaran::where('midtrans_order_id', $request->query('order_id'))->first();

        if ($pembayaran?->payable_type === Pemesanan::class) {
            $linkStatus = URL::signedRoute('guest.pemesanan.show', ['pemesanan' => $pembayaran->payable_id]);
        } elseif ($pembayaran?->payable_type === PaketLangganan::class) {
            $linkStatus = route('member.langganan.show', $pembayaran->payable_id);
        }

        return view('terima-kasih', compact('linkStatus'));
    }
}
