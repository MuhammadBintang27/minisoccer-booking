<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(Request $request): View
    {
        $member = $request->user()->member;

        $paketAktif = $member->paketLangganan()
            ->whereIn('status', ['pending_payment', 'active'])
            ->latest()
            ->get();

        $jadwalMendatang = $member->pemesanan()
            ->with('lapangan')
            ->where('status', 'confirmed')
            ->whereDate('tanggal_main', '>=', now()->toDateString())
            ->orderBy('tanggal_main')
            ->orderBy('jam_mulai')
            ->get();

        return view('member.jadwal', compact('paketAktif', 'jadwalMendatang'));
    }
}
