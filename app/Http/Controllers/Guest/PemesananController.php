<?php

namespace App\Http\Controllers\Guest;

use App\Exceptions\SlotTidakTersediaException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\PemesananRequest;
use App\Models\JadwalLapangan;
use App\Models\Lapangan;
use App\Models\LayananTambahan;
use App\Models\Pemesanan;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class PemesananController extends Controller
{
    public function create(Request $request): View
    {
        $lapangan = Lapangan::findOrFail($request->query('lapangan_id'));
        $slots = JadwalLapangan::where('lapangan_id', $lapangan->id)
            ->whereIn('id', (array) $request->query('jadwal_id', []))
            ->orderBy('jam_mulai')
            ->get();
        $tanggal = Carbon::parse($request->query('tanggal'));
        $layananTambahan = LayananTambahan::where('is_active', true)->orderBy('nama')->get();

        return view('guest.pemesanan.create', compact('lapangan', 'slots', 'tanggal', 'layananTambahan'));
    }

    public function store(PemesananRequest $request, BookingService $bookingService): RedirectResponse
    {
        $validated = $request->validated();

        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);
        $slots = JadwalLapangan::where('lapangan_id', $lapangan->id)
            ->whereIn('id', $validated['jadwal_id'])
            ->get();
        $tanggal = Carbon::parse($validated['tanggal']);
        $addonQuantities = $this->addonQuantities($validated);

        try {
            $pemesanan = $bookingService->createGuestBooking(
                $lapangan,
                $slots,
                $tanggal,
                $validated['nama_tamu'],
                $validated['no_hp_tamu'],
                $addonQuantities,
            );
        } catch (SlotTidakTersediaException $e) {
            return back()->withInput()->withErrors(['jadwal_id' => $e->getMessage()]);
        }

        return redirect()->signedRoute('guest.pemesanan.show', ['pemesanan' => $pemesanan->id]);
    }

    public function show(Pemesanan $pemesanan): View
    {
        $opsiBayar = [];

        if ($pemesanan->status === 'pending') {
            $opsiBayar[] = ['jenis' => 'dp', 'label' => 'Bayar DP (25%)', 'jumlah' => $pemesanan->dpMinimum()];
        } elseif ($pemesanan->status === 'confirmed' && $pemesanan->sisaTagihan() > 0) {
            // Non-member cuma DP di awal, sisanya langsung 1x pelunasan (tidak ada opsi cicil lagi)
            $opsiBayar[] = ['jenis' => 'lunas', 'label' => 'Lunasi Sekarang', 'jumlah' => $pemesanan->sisaTagihan()];
        }

        $opsiBayarUrl = URL::signedRoute('guest.pemesanan.opsi-bayar', ['pemesanan' => $pemesanan->id]);

        return view('guest.pemesanan.show', compact('pemesanan', 'opsiBayar', 'opsiBayarUrl'));
    }

    /**
     * Baris `pembayaran` (dan Snap token) baru dibuat di sini — dipanggil lewat fetch()
     * hanya saat tombol bayar benar-benar diklik, bukan saat halaman status di-load.
     */
    public function opsiBayar(Request $request, Pemesanan $pemesanan, PaymentService $paymentService)
    {
        $customerDetails = [
            'first_name' => $pemesanan->nama_tamu,
            'phone' => $pemesanan->no_hp_tamu,
        ];

        $jumlah = match (true) {
            $request->input('jenis') === 'dp' && $pemesanan->status === 'pending' => $pemesanan->dpMinimum(),
            $request->input('jenis') === 'lunas' && $pemesanan->status === 'confirmed' => $pemesanan->sisaTagihan(),
            default => null,
        };

        abort_if($jumlah === null || $jumlah <= 0, 422);

        $result = $paymentService->getOrCreateSnapTransaction($pemesanan, $jumlah, 'BOOK', $customerDetails);

        return response()->json(['snap_token' => $result['snap_token']]);
    }

    public function cek(Request $request): View
    {
        $noHp = $request->query('no_hp');
        $hasil = collect();

        if ($noHp) {
            $hasil = Pemesanan::where('sumber', 'guest')
                ->where('no_hp_tamu', $noHp)
                ->with('lapangan')
                ->orderByDesc('tanggal_main')
                ->orderByDesc('jam_mulai')
                ->limit(20)
                ->get()
                ->map(fn (Pemesanan $item) => [
                    'pemesanan' => $item,
                    'url' => URL::signedRoute('guest.pemesanan.show', ['pemesanan' => $item->id]),
                ]);
        }

        return view('guest.pemesanan.cek', compact('noHp', 'hasil'));
    }
}
