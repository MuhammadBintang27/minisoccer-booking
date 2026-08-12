<x-layouts.admin title="Data Pemesanan">
    <form method="GET" action="{{ route('admin.pemesanan.index') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Lapangan</label>
            <select name="lapangan_id" onchange="this.form.submit()" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                <option value="">Semua</option>
                @foreach ($daftarLapangan as $item)
                    <option value="{{ $item->id }}" {{ request('lapangan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</label>
            <select name="status" onchange="this.form.submit()" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                <option value="">Semua</option>
                @foreach (['pending', 'confirmed', 'expired', 'cancelled', 'completed'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ \App\Support\StatusLabel::label($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Hari</label>
            <select name="hari" onchange="this.form.submit()" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                <option value="">Semua</option>
                @foreach (['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'] as $value => $label)
                    <option value="{{ $value }}" {{ request('hari') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
        </div>
        <div class="min-w-[180px] flex-1">
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Cari Nama</label>
            <input id="cari-nama" type="text" name="cari" value="{{ request('cari') }}" placeholder="Ketik untuk saring, Enter untuk cari..."
                class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
        </div>
        <a href="{{ route('admin.pemesanan.index') }}" class="pb-2 text-sm text-slate-500 hover:underline">Reset</a>
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
                    <tr class="transition-colors hover:bg-slate-50" data-nama="{{ strtolower($item['nama'] ?? '') }}">
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
                                <div>{{ $addon->nama }}@if ($addon->pivot->jumlah > 1) &times; {{ $addon->pivot->jumlah }}@endif <span class="text-slate-400">(Rp{{ number_format($addon->pivot->harga, 0, ',', '.') }})</span></div>
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

    <script>
        // Ketik = saring baris yang sedang tampil saja; Enter = submit form (cari ke server, semua halaman).
        document.getElementById('cari-nama').addEventListener('input', function () {
            var q = this.value.trim().toLowerCase();
            document.querySelectorAll('tbody tr[data-nama]').forEach(function (tr) {
                tr.style.display = tr.dataset.nama.indexOf(q) !== -1 ? '' : 'none';
            });
        });
    </script>
</x-layouts.admin>
