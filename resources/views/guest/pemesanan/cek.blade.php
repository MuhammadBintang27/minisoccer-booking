<x-layouts.site title="Cek Booking Saya - MYSOC Meulaboh">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <div class="text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gold/15 text-navy">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <h1 class="mt-3 text-xl font-semibold text-slate-800">Cek Booking Saya</h1>
            <p class="mt-1 text-sm text-slate-500">Masukkan no. HP yang kamu pakai waktu booking untuk lihat status pemesanan kamu.</p>
        </div>

        <form method="GET" action="{{ route('guest.pemesanan.cek') }}" class="mt-5 flex items-end gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex-1">
                <label for="no_hp" class="block text-xs font-medium text-slate-700">No. HP</label>
                <input id="no_hp" type="tel" name="no_hp" value="{{ $noHp }}" required placeholder="08xxxxxxxxxx"
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
            </div>
            <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark transition-colors hover:bg-gold-dark">
                Cari
            </button>
        </form>

        @if ($noHp)
            <div class="mt-6">
                @if ($hasil->isEmpty())
                    <div class="rounded-xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-900/5">
                        <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                        </span>
                        <p class="mt-3 text-sm text-slate-500">Tidak ada booking yang ditemukan untuk no. HP ini.</p>
                        <a href="{{ route('home') }}#jadwal" class="mt-4 inline-block rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark transition-colors hover:bg-gold-dark">
                            Booking Sekarang
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($hasil as $item)
                            @php $pemesanan = $item['pemesanan']; @endphp
                            <a href="{{ $item['url'] }}" class="block rounded-xl border border-transparent bg-white p-4 shadow-sm transition-colors hover:border-gold">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 font-semibold text-slate-800">{{ $pemesanan->nama_tamu }}</div>
                                    <span @class([
                                        'shrink-0 whitespace-nowrap rounded-full px-2.5 py-1 text-[11px] font-semibold',
                                        'bg-yellow-100 text-yellow-700' => $pemesanan->status === 'pending',
                                        'bg-green-100 text-green-700' => in_array($pemesanan->status, ['confirmed', 'completed']),
                                        'bg-slate-200 text-slate-600' => in_array($pemesanan->status, ['expired', 'cancelled']),
                                    ])>
                                        {{ \App\Support\StatusLabel::label($pemesanan->status) }}
                                    </span>
                                </div>
                                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        {{ $pemesanan->lapangan->nama }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        {{ $pemesanan->tanggal_main->translatedFormat('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ substr($pemesanan->jam_mulai, 0, 5) }}-{{ substr($pemesanan->jam_selesai, 0, 5) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </section>
</x-layouts.site>
