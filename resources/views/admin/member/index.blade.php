<x-layouts.admin title="Data Member">
    @if (session('status'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Member</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Kontak</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Gabung</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status Akun</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Paket Terakhir</th>
                    <th class="px-4 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Sisa Pertemuan</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($member as $item)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <x-avatar-initial :name="$item->user->name" />
                                <div>
                                    <div class="font-medium text-slate-800">{{ $item->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $item->kode_member }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">
                            <div>{{ $item->user->email }}</div>
                            <div class="text-xs text-slate-400">{{ $item->user->phone }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">{{ $item->tanggal_gabung->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3.5">
                            <x-status-badge :status="$item->status" />
                        </td>
                        <td class="px-4 py-3.5">
                            @php $paketTerakhir = $item->paketLangganan->first(); @endphp
                            @if ($paketTerakhir)
                                <x-status-badge :status="$paketTerakhir->status" />
                            @else
                                <span class="text-xs text-slate-400">Belum pernah beli</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right tabular-nums text-slate-600">{{ $item->sisaPertemuanAktif() }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.member.edit', $item) }}" class="text-xs font-semibold text-navy hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.member.destroy', $item) }}" onsubmit="return confirm('Hapus member {{ $item->user->name }}? Akun login-nya juga akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table-empty :colspan="7">Belum ada member terdaftar.</x-table-empty>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $member->links() }}
    </div>
</x-layouts.admin>
