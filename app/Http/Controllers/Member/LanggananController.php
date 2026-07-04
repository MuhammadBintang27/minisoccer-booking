<?php

namespace App\Http\Controllers\Member;

use App\Exceptions\SlotTidakTersediaException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\LanggananRequest;
use App\Models\JadwalLapangan;
use App\Models\Lapangan;
use App\Models\LayananTambahan;
use App\Models\PaketLangganan;
use App\Services\AvailabilityService;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LanggananController extends Controller
{
    public function create(Request $request, SubscriptionService $subscriptionService, AvailabilityService $availability): View
    {
        $daftarLapangan = Lapangan::where('is_active', true)->orderBy('nama')->get();
        $lapangan = $daftarLapangan->firstWhere('id', (int) $request->query('lapangan_id'));

        $slots = $lapangan
            ? $lapangan->jadwalLapangan()->where('is_closed', false)->orderBy('jam_mulai')->get()
            : collect();

        $bulanOptions = collect(range(0, 2))->map(fn ($i) => Carbon::today()->addMonthsNoOverflow($i)->startOfMonth());
        $layananTambahan = LayananTambahan::where('is_active', true)->orderBy('nama')->get();

        $hari = $request->query('hari') ? (int) $request->query('hari') : null;
        $bulan = $request->query('bulan') ? Carbon::parse($request->query('bulan').'-01') : Carbon::today()->startOfMonth();

        $tanggalPreview = collect();
        $previewPerSlot = [];

        if ($lapangan && $hari) {
            $tanggalPreview = collect($subscriptionService->computeOccurrenceDates($hari, $bulan));

            $statusPerTanggal = $tanggalPreview->map(
                fn (Carbon $tanggal) => $availability->getSlotsForDate($lapangan, $tanggal)
                    ->mapWithKeys(fn ($item) => [$item['slot']->id => $item['status']])
            );

            foreach ($slots as $slot) {
                $previewPerSlot[$slot->id] = $statusPerTanggal->map(fn ($map) => $map->get($slot->id, 'tersedia'));
            }
        }

        return view('member.langganan.create', compact(
            'daftarLapangan', 'lapangan', 'slots', 'bulanOptions', 'layananTambahan',
            'hari', 'bulan', 'tanggalPreview', 'previewPerSlot',
        ));
    }

    public function store(LanggananRequest $request, SubscriptionService $subscriptionService): RedirectResponse
    {
        $validated = $request->validated();

        $member = $request->user()->member;
        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);
        $slots = JadwalLapangan::where('lapangan_id', $lapangan->id)
            ->whereIn('id', $validated['jadwal_id'])
            ->get();
        $bulan = Carbon::parse($validated['bulan'].'-01');

        try {
            $paket = $subscriptionService->createSubscription(
                $member,
                $lapangan,
                $slots,
                (int) $validated['hari'],
                $bulan,
                $validated['layanan_tambahan_id'] ?? [],
            );
        } catch (SlotTidakTersediaException $e) {
            return back()->withInput()->withErrors(['jadwal_id' => $e->getMessage()]);
        }

        return redirect()->route('member.langganan.show', $paket);
    }

    public function show(Request $request, PaketLangganan $paket, PaymentService $paymentService): View
    {
        abort_unless($paket->member_id === $request->user()->member->id, 403);

        $snapToken = null;

        if ($paket->status === 'pending_payment') {
            $result = $paymentService->getOrCreateSnapTransaction(
                $paket,
                (float) $paket->total_harga,
                'SUB',
                [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'phone' => $request->user()->phone,
                ],
            );

            $snapToken = $result['snap_token'];
        }

        $tanggalPertemuan = $paket->pemesanan()->orderBy('tanggal_main')->get();

        return view('member.langganan.show', compact('paket', 'snapToken', 'tanggalPertemuan'));
    }
}
