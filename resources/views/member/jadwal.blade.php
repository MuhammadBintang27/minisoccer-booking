<x-layouts.site title="Jadwal Saya - MYSOC Meulaboh">
    <div class="mx-auto max-w-5xl px-6 py-10">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Jadwal Saya</h1>
                <p class="mt-0.5 text-sm text-slate-500">Halo, {{ auth()->user()->name }}! Ini jadwal main kamu di MYSOC.</p>
            </div>
            <a href="{{ route('member.langganan.create') }}" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark shadow-sm transition-colors hover:bg-gold-dark">
                + Beli Paket Bulanan
            </a>
        </div>

        <div class="mt-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Paket Bulanan Saya</h2>

            @if ($paketAktif->isEmpty())
                <div class="mt-2 rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                    <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </span>
                    <p class="mt-3 text-sm text-slate-500">Belum ada paket bulanan aktif.</p>
                    <a href="{{ route('member.langganan.create') }}" class="mt-3 inline-block text-sm font-semibold text-navy hover:underline">
                        Kunci jadwal favoritmu sebulan penuh &rarr;
                    </a>
                </div>
            @else
                <div class="mt-2 space-y-3">
                    @foreach ($paketAktif as $paket)
                        <a href="{{ route('member.langganan.show', $paket) }}" class="block rounded-xl border border-transparent bg-white p-4 shadow-sm transition-colors hover:border-gold">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-navy/5 text-navy">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="font-medium text-slate-800">{{ $paket->lapangan->nama }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ substr($paket->jam_mulai, 0, 5) }}-{{ substr($paket->jam_selesai, 0, 5) }} &middot;
                                            {{ $paket->periode_mulai->translatedFormat('d M') }} - {{ $paket->periode_selesai->translatedFormat('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($paket->status === 'active')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                        <div class="mt-1 text-xs text-slate-500">Sisa {{ $paket->sisaPertemuan() }}/{{ $paket->jumlah_pertemuan }} pertemuan</div>
                                    @else
                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Menunggu bayar</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Jadwal Mendatang</h2>

            @if ($jadwalMendatang->isEmpty())
                <div class="mt-2 rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                    <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </span>
                    <p class="mt-3 text-sm text-slate-500">Belum ada jadwal main yang terkonfirmasi.</p>
                    <a href="{{ route('home') }}#jadwal" class="mt-3 inline-block text-sm font-semibold text-navy hover:underline">
                        Booking jadwal sekarang &rarr;
                    </a>
                </div>
            @else
                <div class="mt-2 overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">Tanggal</th>
                                <th class="px-4 py-3 font-medium">Jam</th>
                                <th class="px-4 py-3 font-medium">Lapangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($jadwalMendatang as $item)
                                <tr class="transition-colors hover:bg-slate-50/60">
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $item->tanggal_main->translatedFormat('l, d M Y') }}
                                        @if ($item->tanggal_main->isToday())
                                            <span class="ml-1.5 rounded-full bg-gold/20 px-2 py-0.5 text-[10px] font-bold uppercase text-gold-dark">Hari ini</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ substr($item->jam_mulai, 0, 5) }}-{{ substr($item->jam_selesai, 0, 5) }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->lapangan->nama }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.site>
