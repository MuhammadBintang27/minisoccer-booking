<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LapanganRequest;
use App\Models\Lapangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LapanganController extends Controller
{
    public function index(): View
    {
        $lapangan = Lapangan::orderBy('nama')->get();

        return view('admin.lapangan.index', compact('lapangan'));
    }

    public function create(): View
    {
        return view('admin.lapangan.create');
    }

    public function store(LapanganRequest $request): RedirectResponse
    {
        Lapangan::create($request->validated());

        return redirect()->route('admin.lapangan.index')->with('status', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan): View
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(LapanganRequest $request, Lapangan $lapangan): RedirectResponse
    {
        $lapangan->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.lapangan.index')->with('status', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan): RedirectResponse
    {
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('status', 'Lapangan berhasil dihapus.');
    }
}
