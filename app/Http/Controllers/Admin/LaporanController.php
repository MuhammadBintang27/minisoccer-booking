<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketLangganan;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function pendapatan(Request $request, ReportService $reportService): View
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        $pembayaran = $reportService->pembayaranSettlement($dari, $sampai);
        $ringkasan = $reportService->ringkasan($pembayaran);

        return view('admin.laporan.pendapatan', [
            'pembayaran' => $pembayaran,
            'ringkasan' => $ringkasan,
            'dari' => $dari,
            'sampai' => $sampai,
        ]);
    }

    public function exportPendapatan(Request $request, ReportService $reportService): Response
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        $pembayaran = $reportService->pembayaranSettlement($dari, $sampai);

        $csv = "Tanggal Bayar,Order ID,Sumber,Metode,Jumlah\n";

        foreach ($pembayaran as $item) {
            $sumber = $item->payable_type === PaketLangganan::class ? 'Member' : 'Guest';
            $metode = $item->channel === 'cash' ? 'Cash' : ($item->metode_pembayaran ?? 'Online');
            $csv .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $item->paid_at->format('Y-m-d H:i'),
                $item->midtrans_order_id,
                $sumber,
                $metode,
                $item->jumlah,
            );
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-pendapatan-'.$dari->format('Ymd').'-'.$sampai->format('Ymd').'.csv"',
        ]);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function rentangTanggal(Request $request): array
    {
        $dari = $request->filled('dari') ? Carbon::parse($request->query('dari')) : Carbon::today()->startOfMonth();
        $sampai = $request->filled('sampai') ? Carbon::parse($request->query('sampai')) : Carbon::today();

        return [$dari, $sampai];
    }
}
