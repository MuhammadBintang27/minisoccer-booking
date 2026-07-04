<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\PaketLangganan;
use App\Models\Pemesanan;
use App\Services\PaymentService;
use App\Support\StatusLabel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PemesananController extends Controller
{
    /**
     * Booking guest ditampilkan per sesi, booking member digrouping jadi 1 baris per paket
     * (bukan 1 baris per pertemuan mingguan) supaya list tidak dibanjiri 4 baris identik.
     */
    public function index(Request $request): View
    {
        $lapanganId = $request->query('lapangan_id');
        $status = $request->query('status');
        $tanggal = $request->query('tanggal');

        $statusPaketMap = [
            'pending' => 'pending_payment',
            'confirmed' => 'active',
            'expired' => 'expired',
            'cancelled' => 'cancelled',
        ];

        $guestQuery = Pemesanan::with(['lapangan', 'layananTambahan', 'pembayaran'])
            ->where('sumber', 'guest');

        if ($lapanganId) {
            $guestQuery->where('lapangan_id', $lapanganId);
        }
        if ($status) {
            $guestQuery->where('status', $status);
        }
        if ($tanggal) {
            $guestQuery->whereDate('tanggal_main', $tanggal);
        }

        $paketQuery = PaketLangganan::with(['lapangan', 'member.user', 'pembayaran', 'pemesanan.layananTambahan']);

        if ($lapanganId) {
            $paketQuery->where('lapangan_id', $lapanganId);
        }
        if ($status) {
            if (isset($statusPaketMap[$status])) {
                $paketQuery->where('status', $statusPaketMap[$status]);
            } else {
                // status "completed" tidak punya padanan di status paket
                $paketQuery->whereRaw('1 = 0');
            }
        }
        if ($tanggal) {
            $paketQuery->whereHas('pemesanan', fn ($q) => $q->whereDate('tanggal_main', $tanggal));
        }

        $namaHari = ['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'];

        $statusBadge = fn (string $status) => match (true) {
            in_array($status, ['pending', 'pending_payment']) => 'bg-yellow-100 text-yellow-700',
            in_array($status, ['confirmed', 'completed', 'active']) => 'bg-green-100 text-green-700',
            default => 'bg-slate-200 text-slate-600',
        };

        $baris = collect();

        foreach ($guestQuery->get() as $item) {
            $baris->push([
                'tipe' => 'guest',
                'sort_key' => $item->tanggal_main->format('Y-m-d').' '.$item->jam_mulai,
                'tanggal_label' => $item->tanggal_main->translatedFormat('d M Y'),
                'jam_label' => substr($item->jam_mulai, 0, 5).'-'.substr($item->jam_selesai, 0, 5),
                'lapangan' => $item->lapangan->nama,
                'sumber_label' => 'Guest',
                'nama' => $item->nama_tamu,
                'status_label' => StatusLabel::label($item->status),
                'status_class' => $statusBadge($item->status),
                'status_bayar' => ucfirst($item->statusPembayaran()),
                'addons' => $item->layananTambahan,
                'aksi_url' => route('admin.pemesanan.show', $item),
                'aksi_label' => 'Detail',
            ]);
        }

        foreach ($paketQuery->get() as $paket) {
            $addons = $paket->rincianAddon();

            $baris->push([
                'tipe' => 'paket',
                'sort_key' => $paket->periode_mulai->format('Y-m-d'),
                'tanggal_label' => ($namaHari[(string) $paket->hari] ?? '-').' ('.$paket->jumlah_pertemuan.'x pertemuan)',
                'jam_label' => substr($paket->jam_mulai, 0, 5).'-'.substr($paket->jam_selesai, 0, 5),
                'lapangan' => $paket->lapangan->nama,
                'sumber_label' => 'Member',
                'nama' => $paket->member?->user?->name,
                'status_label' => StatusLabel::label($paket->status),
                'status_class' => $statusBadge($paket->status),
                'status_bayar' => ucfirst($paket->statusPembayaran()),
                'addons' => $addons,
                'aksi_url' => route('admin.langganan.show', $paket),
                'aksi_label' => 'Detail Paket',
            ]);
        }

        $baris = $baris->sortByDesc('sort_key')->values();

        $perPage = 20;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $pemesanan = new LengthAwarePaginator(
            $baris->forPage($page, $perPage),
            $baris->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        $daftarLapangan = Lapangan::orderBy('nama')->get();

        return view('admin.pemesanan.index', compact('pemesanan', 'daftarLapangan'));
    }

    public function show(Pemesanan $pemesanan): View
    {
        abort_if($pemesanan->sumber === 'member', 404);

        $pemesanan->load(['lapangan', 'layananTambahan', 'pembayaran' => fn ($q) => $q->orderByDesc('created_at')]);

        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    public function bayarCash(Request $request, Pemesanan $pemesanan, PaymentService $paymentService): RedirectResponse
    {
        abort_if($pemesanan->sumber === 'member', 404);

        $validated = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1', 'max:'.$pemesanan->sisaTagihan()],
        ]);

        $paymentService->recordCashPayment($pemesanan, (float) $validated['jumlah'], $request->user());

        return redirect()->route('admin.pemesanan.show', $pemesanan)->with('status', 'Pembayaran cash berhasil dicatat.');
    }

    /**
     * Kasir: buat Snap token langsung dipanggil lewat fetch() saat tombol diklik,
     * supaya popup pembayaran terbuka dalam satu langkah (tidak perlu reload halaman dulu).
     */
    public function buatTransaksiOnline(Request $request, Pemesanan $pemesanan, PaymentService $paymentService)
    {
        abort_if($pemesanan->sumber === 'member', 404);

        $validated = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1', 'max:'.$pemesanan->sisaTagihan()],
        ]);

        $result = $paymentService->getOrCreateSnapTransaction($pemesanan, (float) $validated['jumlah'], 'BOOK', [
            'first_name' => $pemesanan->nama_tamu,
            'phone' => $pemesanan->no_hp_tamu,
        ]);

        return response()->json(['snap_token' => $result['snap_token']]);
    }
}
