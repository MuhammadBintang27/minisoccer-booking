@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="space-y-4">
    <div>
        <label for="nama" class="block text-sm font-medium text-slate-700">Nama Lapangan</label>
        <input id="nama" type="text" name="nama" value="{{ old('nama', $lapangan->nama ?? '') }}" required
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
    </div>

    <div>
        <label for="deskripsi" class="block text-sm font-medium text-slate-700">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="3"
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">{{ old('deskripsi', $lapangan->deskripsi ?? '') }}</textarea>
    </div>

    <label class="flex items-center gap-2 text-sm text-slate-700">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $lapangan->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300">
        Aktif (bisa dipesan)
    </label>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
        Simpan
    </button>
    <a href="{{ route('admin.lapangan.index') }}" class="text-sm text-slate-600 hover:underline">Batal</a>
</div>
