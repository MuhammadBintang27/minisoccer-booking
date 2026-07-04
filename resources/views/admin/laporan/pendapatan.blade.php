<x-layouts.admin title="Laporan Pendapatan">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">Laporan Pendapatan</h2>
        <a href="{{ route('admin.laporan.pendapatan.export', request()->query()) }}" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Export CSV
        </a>
    </div>

    <form method="GET" action="{{ route('admin.laporan.pendapatan') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow-sm">
        <div>
            <label class="block text-xs font-medium text-slate-700">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ $dari->toDateString() }}" class="mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-700">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai->toDateString() }}" class="mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Tampilkan
        </button>
    </form>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="text-xs text-slate-500">Total Pendapatan</div>
            <div class="mt-1 text-lg font-bold text-slate-800">Rp{{ number_format($ringkasan['total'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="text-xs text-slate-500">Dari Member</div>
            <div class="mt-1 text-lg font-bold text-slate-800">Rp{{ number_format($ringkasan['member'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="text-xs text-slate-500">Dari Non-Member</div>
            <div class="mt-1 text-lg font-bold text-slate-800">Rp{{ number_format($ringkasan['guest'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="text-xs text-slate-500">Jumlah Transaksi</div>
            <div class="mt-1 text-lg font-bold text-slate-800">{{ $ringkasan['jumlah_transaksi'] }}</div>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Tanggal Bayar</th>
                    <th class="px-4 py-3 font-medium">Order ID</th>
                    <th class="px-4 py-3 font-medium">Sumber</th>
                    <th class="px-4 py-3 font-medium">Metode</th>
                    <th class="px-4 py-3 font-medium">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pembayaran as $item)
                    <tr>
                        <td class="px-4 py-3 text-slate-700">{{ $item->paid_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->midtrans_order_id }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $item->payable_type === \App\Models\PaketLangganan::class ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $item->payable_type === \App\Models\PaketLangganan::class ? 'Member' : 'Guest' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->metode_pembayaran ?? '-' }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada pendapatan di rentang tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
