<x-layouts.site title="Riwayat Pemesanan">
    <div class="mx-auto max-w-5xl px-6 py-10">
        <h1 class="text-xl font-semibold text-slate-800">Riwayat Pemesanan</h1>

        <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-sm">
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
                        <tr>
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
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada riwayat pemesanan.</td>
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
