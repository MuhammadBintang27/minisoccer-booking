<x-layouts.admin title="Layanan Tambahan">
    <x-slot:actions>
        <a href="{{ route('admin.layanan-tambahan.create') }}" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Tambah Layanan
        </a>
    </x-slot:actions>

    @if (session('status'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nama</th>
                    <th class="px-4 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Harga</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Jumlah</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($layanan as $item)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-4 py-3.5 font-medium text-slate-800">{{ $item->nama }}</td>
                        <td class="px-4 py-3.5 text-right tabular-nums text-slate-600">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-slate-500">{{ $item->pakai_jumlah ? 'Bisa pilih' : 'Tetap 1' }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.layanan-tambahan.edit', $item) }}" class="text-xs font-semibold text-navy hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.layanan-tambahan.destroy', $item) }}" onsubmit="return confirm('Hapus layanan {{ $item->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table-empty :colspan="5">Belum ada layanan tambahan.</x-table-empty>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
