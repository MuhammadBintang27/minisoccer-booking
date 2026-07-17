@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="space-y-4">
    <div>
        <label for="nama" class="block text-sm font-medium text-slate-700">Nama Layanan</label>
        <input id="nama" type="text" name="nama" value="{{ old('nama', $layanan->nama ?? '') }}" required
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
    </div>

    <div>
        <label for="harga" class="block text-sm font-medium text-slate-700">Harga (per jam main)</label>
        <input id="harga" type="text" inputmode="numeric" name="harga" value="{{ old('harga', isset($layanan) ? number_format($layanan->harga, 0, '', '') : '') }}" required
            class="input-rupiah mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
    </div>

    <label class="flex items-center gap-2 text-sm text-slate-700">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $layanan->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-navy focus:ring-navy/30">
        Aktif (bisa dipilih saat booking)
    </label>
</div>

<div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-4">
    <button type="submit" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
        Simpan
    </button>
    <a href="{{ route('admin.layanan-tambahan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
</div>
