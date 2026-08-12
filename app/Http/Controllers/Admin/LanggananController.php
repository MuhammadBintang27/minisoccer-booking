<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketLangganan;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanggananController extends Controller
{
    public function show(PaketLangganan $paket): View
    {
        $paket->load([
            'lapangan' => fn ($q) => $q->withTrashed(),
            'member.user',
            'pembayaran' => fn ($q) => $q->orderByDesc('created_at'),
            'pemesanan' => fn ($q) => $q->with('layananTambahan')->orderBy('tanggal_main'),
        ]);

        $tanggalPertemuan = $paket->pemesanan;

        return view('admin.langganan.show', compact('paket', 'tanggalPertemuan'));
    }

    public function bayarCash(Request $request, PaketLangganan $paket, PaymentService $paymentService): RedirectResponse
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1', 'max:'.$paket->sisaTagihan()],
        ]);

        $paymentService->recordCashPayment($paket, (float) $validated['jumlah'], $request->user());

        return redirect()->route('admin.langganan.show', $paket)->with('status', 'Pembayaran cash berhasil dicatat.');
    }

    /**
     * Kasir: buat Snap token langsung dipanggil lewat fetch() saat tombol diklik,
     * supaya popup pembayaran terbuka dalam satu langkah (tidak perlu reload halaman dulu).
     */
    public function buatTransaksiOnline(Request $request, PaketLangganan $paket, PaymentService $paymentService)
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1', 'max:'.$paket->sisaTagihan()],
        ]);

        $result = $paymentService->getOrCreateSnapTransaction($paket, (float) $validated['jumlah'], 'SUB', [
            'first_name' => $paket->member->user->name,
            'email' => $paket->member->user->email,
            'phone' => $paket->member->user->phone,
        ]);

        return response()->json(['snap_token' => $result['snap_token']]);
    }
}
