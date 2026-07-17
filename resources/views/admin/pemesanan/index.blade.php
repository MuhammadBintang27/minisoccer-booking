<x-layouts.admin title="Data Pemesanan">
    <form method="GET" action="{{ route('admin.pemesanan.index') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Lapangan</label>
            <select name="lapangan_id" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                <option value="">Semua</option>
                @foreach ($daftarLapangan as $item)
                    <option value="{{ $item->id }}" {{ request('lapangan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</label>
            <select name="status" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                <option value="">Semua</option>
                @foreach (['pending', 'confirmed', 'expired', 'cancelled', 'completed'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ \App\Support\StatusLabel::label($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
        </div>
        <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Filter
        </button>
        <a href="{{ route('admin.pemesanan.index') }}" class="text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Jadwal</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Lapangan</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pemesan</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status Booking</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status Bayar</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Add-on</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pemesanan as $item)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-4 py-3.5 text-slate-700">
                            <div class="font-medium text-slate-800">{{ $item['tanggal_label'] }}</div>
                            <div class="text-xs text-slate-400">{{ $item['jam_label'] }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-700">{{ $item['lapangan'] }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <x-avatar-initial :name="$item['nama']" />
                                <div>
                                    <div class="font-medium text-slate-800">{{ $item['nama'] }}</div>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $item['tipe'] === 'paket' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $item['sumber_label'] }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <x-status-badge :status="$item['status']" />
                        </td>
                        <td class="px-4 py-3.5 text-slate-500">{{ $item['status_bayar'] }}</td>
                        <td class="px-4 py-3.5 text-slate-600">
                            @forelse ($item['addons'] as $addon)
                                <div>{{ $addon->nama }} <span class="text-slate-400">(Rp{{ number_format($addon->pivot->harga, 0, ',', '.') }})</span></div>
                            @empty
                                <span class="text-slate-300">-</span>
                            @endforelse
                        </td>
                        <td class="px-4 py-3.5">
                            <a href="{{ $item['aksi_url'] }}" class="text-xs font-semibold text-navy hover:underline">{{ $item['aksi_label'] }}</a>
                        </td>
                    </tr>
                @empty
                    <x-table-empty :colspan="7">Belum ada data pemesanan.</x-table-empty>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pemesanan->links() }}
    </div>
</x-layouts.admin>
