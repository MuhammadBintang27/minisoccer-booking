<x-layouts.site title="Riwayat Pemesanan - MYSOC Meulaboh">
    <div class="mx-auto max-w-5xl px-6 py-10">
        <h1 class="text-xl font-semibold text-slate-800">Riwayat Pemesanan</h1>
        <p class="mt-0.5 text-sm text-slate-500">Semua booking kamu di MYSOC, dari yang terbaru.</p>

        <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Jam</th>
                        <th class="px-4 py-3 font-medium">Lapangan</th>
                        <th class="px-4 py-3 font-medium">Add-on</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pemesanan as $item)
                        <tr class="transition-colors hover:bg-slate-50/60">
                            <td class="px-4 py-3 text-slate-700">{{ $item->tanggal_main->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ substr($item->jam_mulai, 0, 5) }}-{{ substr($item->jam_selesai, 0, 5) }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $item->lapangan->nama }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $item->layananTambahan->pluck('nama')->join(', ') ?: '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-semibold',
                                    'bg-yellow-100 text-yellow-700' => $item->status === 'pending',
                                    'bg-green-100 text-green-700' => in_array($item->status, ['confirmed', 'completed']),
                                    'bg-slate-200 text-slate-600' => in_array($item->status, ['expired', 'cancelled']),
                                ])>
                                    {{ \App\Support\StatusLabel::label($item->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 3h4m-7.5 6h13.5a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5h-3.086a1.5 1.5 0 01-1.06-.44l-1.328-1.32a1.5 1.5 0 00-1.06-.44H9.75a1.5 1.5 0 00-1.5 1.5V4.5m-3 1.5h.008v.008H5.25V6z" />
                                    </svg>
                                </span>
                                <p class="mt-3 text-sm text-slate-500">Belum ada riwayat pemesanan.</p>
                                <a href="{{ route('home') }}#jadwal" class="mt-2 inline-block text-sm font-semibold text-navy hover:underline">
                                    Booking pertama kamu &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pemesanan->links() }}
        </div>
    </div>
</x-layouts.site>
