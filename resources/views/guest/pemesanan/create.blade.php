<x-layouts.site title="Pemesanan Lapangan - MYSOC Meulaboh">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'tanggal' => $tanggal->toDateString()]) }}#jadwal" class="inline-flex items-center gap-1 text-sm text-slate-500 transition-colors hover:text-navy">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke jadwal
        </a>

        <div class="mt-4 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="bg-navy px-6 py-4 text-white">
                <h1 class="text-lg font-semibold">Form Pemesanan</h1>
                <p class="mt-0.5 text-xs text-white/60">Satu langkah lagi menuju lapangan.</p>
            </div>

            <div class="p-6">
                <div class="rounded-lg border border-gold/40 bg-gold/5 p-4 text-sm text-slate-600">
                    <div class="flex items-center gap-2 font-semibold text-slate-800">
                        <svg class="h-4 w-4 text-gold-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        {{ $lapangan->nama }}
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        {{ $tanggal->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="mt-1.5 flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ substr($slots->first()->jam_mulai, 0, 5) }}-{{ substr($slots->last()->jam_selesai, 0, 5) }} ({{ $slots->count() }} jam)
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-gold/30 pt-2.5">
                        <span class="text-xs text-slate-500">Sewa lapangan</span>
                        <span class="text-base font-bold text-slate-800">
                            Rp{{ number_format($slots->sum(fn ($slot) => $slot->hargaUntukTanggal($tanggal, 'guest')), 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('guest.pemesanan.store') }}" class="mt-5 space-y-4">
                    @csrf
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                    @foreach ($slots as $slot)
                        <input type="hidden" name="jadwal_id[]" value="{{ $slot->id }}">
                    @endforeach
                    <input type="hidden" name="tanggal" value="{{ $tanggal->toDateString() }}">

                    <div>
                        <label for="nama_tamu" class="block text-sm font-medium text-slate-700">Nama</label>
                        <input id="nama_tamu" type="text" name="nama_tamu" value="{{ old('nama_tamu') }}" required placeholder="Nama kamu atau nama tim"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                    </div>

                    <div>
                        <label for="no_hp_tamu" class="block text-sm font-medium text-slate-700">No. HP</label>
                        <input id="no_hp_tamu" type="tel" name="no_hp_tamu" value="{{ old('no_hp_tamu') }}" required placeholder="08xxxxxxxxxx"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                        <p class="mt-1 text-xs text-slate-400">Simpan nomor ini — dipakai untuk cek status booking kamu nanti.</p>
                    </div>

                    @if ($layananTambahan->isNotEmpty())
                        <div>
                            <div class="block text-sm font-medium text-slate-700">Layanan Tambahan <span class="font-normal text-slate-400">(opsional, dihitung per jam main)</span></div>
                            <div class="mt-2 space-y-2">
                                @foreach ($layananTambahan as $addon)
                                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-sm transition-colors hover:border-slate-300 has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                        <span class="flex items-center gap-2">
                                            <input type="checkbox" name="layanan_tambahan_id[]" value="{{ $addon->id }}" class="rounded border-slate-300 text-navy focus:ring-navy/30">
                                            {{ $addon->nama }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            Rp{{ number_format($addon->harga, 0, ',', '.') }}/jam
                                            &times; {{ $slots->count() }} jam = Rp{{ number_format($addon->harga * $slots->count(), 0, ',', '.') }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start gap-2 rounded-lg bg-slate-50 p-3 text-xs leading-relaxed text-slate-500">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Slot akan ditahan selama <span class="font-semibold text-slate-700">15 menit</span> untuk penyelesaian pembayaran DP. Setelah itu, jika belum dibayar, slot otomatis dilepas.</span>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-gold px-4 py-2.5 text-sm font-semibold text-navy-dark shadow-sm transition-colors hover:bg-gold-dark">
                        Lanjut ke Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.site>
