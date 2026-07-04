<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    public function index(Request $request): View
    {
        $pemesanan = $request->user()->member
            ->pemesanan()
            ->with(['lapangan', 'layananTambahan'])
            ->orderByDesc('tanggal_main')
            ->orderByDesc('jam_mulai')
            ->paginate(15);

        return view('member.riwayat', compact('pemesanan'));
    }
}
