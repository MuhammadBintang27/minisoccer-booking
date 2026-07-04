<x-layouts.admin title="Data Lapangan">
    <x-slot:actions>
        <a href="{{ route('admin.lapangan.create') }}" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Tambah Lapangan
        </a>
    </x-slot:actions>

    @if (session('status'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Nama</th>
                    <th class="px-6 py-3 font-medium">Deskripsi</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($lapangan as $item)
                    <tr>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $item->nama }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->deskripsi ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.lapangan.jadwal.index', $item) }}" class="rounded-lg bg-gold px-3 py-1.5 text-xs font-semibold text-navy-dark hover:bg-gold-dark">
                                    Ubah Jadwal
                                </a>
                                <a href="{{ route('admin.lapangan.edit', $item) }}" class="text-xs font-semibold text-navy hover:underline">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.lapangan.destroy', $item) }}" onsubmit="return confirm('Hapus lapangan {{ $item->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada data lapangan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
