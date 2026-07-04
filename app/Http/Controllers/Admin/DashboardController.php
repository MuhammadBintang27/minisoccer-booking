<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Member;
use App\Models\PaketLangganan;
use App\Models\Pemesanan;
use App\Services\ReportService;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(ReportService $reportService): View
    {
        $today = Carbon::today();

        $totalLapangan = Lapangan::where('is_active', true)->count();
        $totalMember = Member::where('status', 'active')->count();

        $bookingHariIni = Pemesanan::with(['lapangan', 'member.user'])
            ->whereDate('tanggal_main', $today)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->orderBy('jam_mulai')
            ->get();

        $pembayaranBulanIni = $reportService->pembayaranSettlement($today->copy()->startOfMonth(), $today);
        $ringkasanBulanIni = $reportService->ringkasan($pembayaranBulanIni);

        $guestPending = Pemesanan::with('lapangan')
            ->where('sumber', 'guest')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $paketPending = PaketLangganan::with(['lapangan', 'member.user'])
            ->where('status', 'pending_payment')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalLapangan', 'totalMember', 'bookingHariIni', 'ringkasanBulanIni',
            'guestPending', 'paketPending',
        ));
    }
}
