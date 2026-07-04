<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JadwalLapanganRequest;
use App\Models\JadwalLapangan;
use App\Models\Lapangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JadwalLapanganController extends Controller
{
    public function index(Lapangan $lapangan): View
    {
        $jadwal = $lapangan->jadwalLapangan()->orderBy('jam_mulai')->get();

        return view('admin.lapangan.jadwal', compact('lapangan', 'jadwal'));
    }

    public function store(JadwalLapanganRequest $request, Lapangan $lapangan): RedirectResponse
    {
        $lapangan->jadwalLapangan()->create([
            ...$request->validated(),
            'is_closed' => $request->boolean('is_closed'),
        ]);

        return back()->with('status', 'Slot jadwal berhasil ditambahkan.');
    }

    public function update(JadwalLapanganRequest $request, Lapangan $lapangan, JadwalLapangan $jadwal): RedirectResponse
    {
        abort_unless($jadwal->lapangan_id === $lapangan->id, 404);

        $jadwal->update([
            ...$request->validated(),
            'is_closed' => $request->boolean('is_closed'),
        ]);

        return back()->with('status', 'Slot jadwal berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan, JadwalLapangan $jadwal): RedirectResponse
    {
        abort_unless($jadwal->lapangan_id === $lapangan->id, 404);

        $jadwal->delete();

        return back()->with('status', 'Slot jadwal berhasil dihapus.');
    }
}
