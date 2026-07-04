<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemesananController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pemesanan::with(['lapangan', 'member.user', 'pembayaran', 'paketLangganan.pembayaran', 'layananTambahan'])
            ->orderByDesc('tanggal_main')
            ->orderByDesc('jam_mulai');

        if ($request->filled('lapangan_id')) {
            $query->where('lapangan_id', $request->query('lapangan_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_main', $request->query('tanggal'));
        }

        $pemesanan = $query->paginate(20)->withQueryString();
        $daftarLapangan = Lapangan::orderBy('nama')->get();

        return view('admin.pemesanan.index', compact('pemesanan', 'daftarLapangan'));
    }
}
