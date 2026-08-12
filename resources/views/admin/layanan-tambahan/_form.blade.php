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
        <label for="harga" class="block text-sm font-medium text-slate-700">Harga</label>
        <input id="harga" type="text" inputmode="numeric" name="harga" value="{{ old('harga', isset($layanan) ? number_format($layanan->harga, 0, '', '') : '') }}" required
            class="input-rupiah mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
        <p class="mt-1 text-xs text-slate-400">Harga untuk 1x pilih layanan ini.</p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
        <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
            <input type="checkbox" name="pakai_jumlah" value="1" {{ old('pakai_jumlah', $layanan->pakai_jumlah ?? false) ? 'checked' : '' }} class="rounded border-slate-300 text-navy focus:ring-navy/30">
            Customer bisa pilih jumlah
        </label>
        <ul class="mt-2 space-y-1 pl-6 text-xs text-slate-500">
            <li>✅ Nyala → muncul kolom jumlah. Contoh: Rompi, customer isi mau berapa set.</li>
            <li>⬜ Mati → cuma centang ada/tidak, jumlah otomatis 1.</li>
        </ul>
    </div>

    <div class="rounded-lg border border-gold/40 bg-gold/5 p-3 text-xs leading-relaxed text-slate-600">
        <span class="font-semibold text-slate-700">Kalau harganya beda per durasi</span> (mis. Fotografer 1 Jam Rp300rb, 2 Jam Rp500rb),
        jangan pakai jumlah untuk itu — buat 2 layanan terpisah: "Fotografer 1 Jam" dan "Fotografer 2 Jam", masing-masing harganya sendiri.
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
