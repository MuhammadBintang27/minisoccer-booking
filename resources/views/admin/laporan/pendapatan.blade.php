<x-layouts.admin title="Laporan Pendapatan">
    <x-slot:actions>
        <a href="{{ route('admin.laporan.pendapatan.export', request()->query()) }}" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Export CSV
        </a>
    </x-slot:actions>

    <form method="GET" action="{{ route('admin.laporan.pendapatan') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ $dari->toDateString() }}" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
        </div>
        <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai->toDateString() }}" class="mt-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
        </div>
        <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Tampilkan
        </button>
    </form>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Total Pendapatan</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">Rp{{ number_format($ringkasan['total'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Dari Member</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">Rp{{ number_format($ringkasan['member'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Dari Non-Member</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">Rp{{ number_format($ringkasan['guest'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Via Midtrans</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">Rp{{ number_format($ringkasan['midtrans'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Via Cash</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">Rp{{ number_format($ringkasan['cash'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Jumlah Transaksi</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">{{ $ringkasan['jumlah_transaksi'] }}</div>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal Bayar</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Order ID</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Sumber</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Metode</th>
                    <th class="px-4 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pembayaran as $item)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-4 py-3.5 text-slate-700">{{ $item->paid_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-4 py-3.5 text-slate-600">{{ $item->midtrans_order_id ?? '-' }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->payable_type === \App\Models\PaketLangganan::class ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $item->payable_type === \App\Models\PaketLangganan::class ? 'Member' : 'Guest' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">{{ $item->channel === 'cash' ? 'Cash' : ($item->metode_pembayaran ?? 'Online') }}</td>
                        <td class="px-4 py-3.5 text-right font-medium tabular-nums text-slate-800">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <x-table-empty :colspan="5">Belum ada pendapatan di rentang tanggal ini.</x-table-empty>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
