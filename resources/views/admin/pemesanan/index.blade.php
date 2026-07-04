<x-layouts.admin title="Data Pemesanan">
    <form method="GET" action="{{ route('admin.pemesanan.index') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow-sm">
        <div>
            <label class="block text-xs font-medium text-slate-700">Lapangan</label>
            <select name="lapangan_id" class="mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach ($daftarLapangan as $item)
                    <option value="{{ $item->id }}" {{ request('lapangan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-700">Status</label>
            <select name="status" class="mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['pending', 'confirmed', 'expired', 'cancelled', 'completed'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ \App\Support\StatusLabel::label($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-700">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Filter
        </button>
        <a href="{{ route('admin.pemesanan.index') }}" class="text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Tanggal</th>
                    <th class="px-4 py-3 font-medium">Jam</th>
                    <th class="px-4 py-3 font-medium">Lapangan</th>
                    <th class="px-4 py-3 font-medium">Sumber</th>
                    <th class="px-4 py-3 font-medium">Nama</th>
                    <th class="px-4 py-3 font-medium">Status Booking</th>
                    <th class="px-4 py-3 font-medium">Status Bayar</th>
                    <th class="px-4 py-3 font-medium">Add-on</th>
                    <th class="px-4 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pemesanan as $item)
                    <tr>
                        <td class="px-4 py-3 text-slate-700">{{ $item['tanggal_label'] }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $item['jam_label'] }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $item['lapangan'] }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $item['tipe'] === 'paket' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $item['sumber_label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-700">{{ $item['nama'] }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $item['status_class'] }}">
                                {{ $item['status_label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $item['status_bayar'] }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            @forelse ($item['addons'] as $addon)
                                <div>{{ $addon->nama }} <span class="text-slate-400">(Rp{{ number_format($addon->pivot->harga, 0, ',', '.') }})</span></div>
                            @empty
                                <span class="text-slate-300">-</span>
                            @endforelse
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ $item['aksi_url'] }}" class="text-xs font-semibold text-navy hover:underline">{{ $item['aksi_label'] }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-slate-500">Belum ada data pemesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pemesanan->links() }}
    </div>
</x-layouts.admin>
