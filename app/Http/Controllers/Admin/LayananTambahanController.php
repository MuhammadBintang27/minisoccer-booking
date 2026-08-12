<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LayananTambahanRequest;
use App\Models\LayananTambahan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayananTambahanController extends Controller
{
    public function index(): View
    {
        $layanan = LayananTambahan::orderBy('nama')->get();

        return view('admin.layanan-tambahan.index', compact('layanan'));
    }

    public function create(): View
    {
        return view('admin.layanan-tambahan.create');
    }

    public function store(LayananTambahanRequest $request): RedirectResponse
    {
        LayananTambahan::create([
            ...$request->validated(),
            'pakai_jumlah' => $request->boolean('pakai_jumlah'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.layanan-tambahan.index')->with('status', 'Layanan tambahan berhasil ditambahkan.');
    }

    public function edit(LayananTambahan $layananTambahan): View
    {
        return view('admin.layanan-tambahan.edit', ['layanan' => $layananTambahan]);
    }

    public function update(LayananTambahanRequest $request, LayananTambahan $layananTambahan): RedirectResponse
    {
        $layananTambahan->update([
            ...$request->validated(),
            'pakai_jumlah' => $request->boolean('pakai_jumlah'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.layanan-tambahan.index')->with('status', 'Layanan tambahan berhasil diperbarui.');
    }

    public function destroy(LayananTambahan $layananTambahan): RedirectResponse
    {
        $layananTambahan->delete();

        return redirect()->route('admin.layanan-tambahan.index')->with('status', 'Layanan tambahan berhasil dihapus.');
    }
}
