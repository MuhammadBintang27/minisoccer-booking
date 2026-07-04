<x-layouts.site title="Jadwal Saya">
    <div class="mx-auto max-w-5xl px-6 py-10">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-800">Jadwal Saya</h1>
            <a href="{{ route('member.langganan.create') }}" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                Beli Paket Bulanan
            </a>
        </div>

        <div class="mt-6">
            <h2 class="text-sm font-semibold text-slate-700">Paket Bulanan Saya</h2>

            @if ($paketAktif->isEmpty())
                <div class="mt-2 rounded-xl bg-white p-6 text-sm text-slate-500 shadow-sm">
                    Belum ada paket bulanan aktif.
                </div>
            @else
                <div class="mt-2 space-y-3">
                    @foreach ($paketAktif as $paket)
                        <a href="{{ route('member.langganan.show', $paket) }}" class="block rounded-xl border border-transparent bg-white p-4 shadow-sm hover:border-gold">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-slate-800">{{ $paket->lapangan->nama }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ substr($paket->jam_mulai, 0, 5) }}-{{ substr($paket->jam_selesai, 0, 5) }} &middot;
                                        {{ $paket->periode_mulai->translatedFormat('d M') }} - {{ $paket->periode_selesai->translatedFormat('d M Y') }}
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
            <h2 class="text-sm font-semibold text-slate-700">Jadwal Mendatang</h2>

            @if ($jadwalMendatang->isEmpty())
                <div class="mt-2 rounded-xl bg-white p-6 text-sm text-slate-500 shadow-sm">
                    Belum ada jadwal main yang terkonfirmasi.
                </div>
            @else
                <div class="mt-2 overflow-x-auto rounded-xl bg-white shadow-sm">
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
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $item->tanggal_main->translatedFormat('l, d M Y') }}</td>
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
