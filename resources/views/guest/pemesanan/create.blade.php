<x-layouts.site title="Pemesanan Lapangan - Soccer Bumi Teuku Umar">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'tanggal' => $tanggal->toDateString()]) }}#jadwal" class="text-sm text-slate-500 hover:underline">&larr; Kembali ke jadwal</a>

        <div class="mt-4 rounded-xl bg-white p-6 shadow-sm">
            <h1 class="text-lg font-semibold text-slate-800">Form Pemesanan</h1>

            <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                <div><span class="font-medium text-slate-800">{{ $lapangan->nama }}</span></div>
                <div>{{ $tanggal->translatedFormat('l, d F Y') }}</div>
                <div>{{ substr($slots->first()->jam_mulai, 0, 5) }}-{{ substr($slots->last()->jam_selesai, 0, 5) }} ({{ $slots->count() }} jam)</div>
                <div class="mt-1 font-semibold text-slate-800">
                    Rp{{ number_format($slots->sum(fn ($slot) => $slot->hargaUntukTanggal($tanggal, 'guest')), 0, ',', '.') }}
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('guest.pemesanan.store') }}" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                @foreach ($slots as $slot)
                    <input type="hidden" name="jadwal_id[]" value="{{ $slot->id }}">
                @endforeach
                <input type="hidden" name="tanggal" value="{{ $tanggal->toDateString() }}">

                <div>
                    <label for="nama_tamu" class="block text-sm font-medium text-slate-700">Nama</label>
                    <input id="nama_tamu" type="text" name="nama_tamu" value="{{ old('nama_tamu') }}" required
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
                </div>

                <div>
                    <label for="no_hp_tamu" class="block text-sm font-medium text-slate-700">No. HP</label>
                    <input id="no_hp_tamu" type="text" name="no_hp_tamu" value="{{ old('no_hp_tamu') }}" required
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
                </div>

                @if ($layananTambahan->isNotEmpty())
                    <div>
                        <div class="block text-sm font-medium text-slate-700">Layanan Tambahan (opsional, dihitung per jam main)</div>
                        <div class="mt-2 space-y-2">
                            @foreach ($layananTambahan as $addon)
                                <label class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-sm has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                    <span class="flex items-center gap-2">
                                        <input type="checkbox" name="layanan_tambahan_id[]" value="{{ $addon->id }}" class="rounded border-slate-300">
                                        {{ $addon->nama }}
                                    </span>
                                    <span class="text-slate-500">
                                        Rp{{ number_format($addon->harga, 0, ',', '.') }}/jam
                                        &times; {{ $slots->count() }} jam = Rp{{ number_format($addon->harga * $slots->count(), 0, ',', '.') }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <p class="text-xs text-slate-500">
                    Slot akan ditahan selama 15 menit untuk penyelesaian pembayaran. Setelah itu, jika belum dibayar, slot otomatis dilepas.
                </p>

                <button type="submit" class="w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Lanjut ke Pembayaran
                </button>
            </form>
        </div>
    </section>
</x-layouts.site>
