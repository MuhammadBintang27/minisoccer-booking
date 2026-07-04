<x-layouts.admin title="Data Member">
    <h2 class="text-lg font-semibold text-slate-800">Data Member</h2>

    @if (session('status'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Kode</th>
                    <th class="px-4 py-3 font-medium">Nama</th>
                    <th class="px-4 py-3 font-medium">Email</th>
                    <th class="px-4 py-3 font-medium">No. HP</th>
                    <th class="px-4 py-3 font-medium">Gabung</th>
                    <th class="px-4 py-3 font-medium">Status Akun</th>
                    <th class="px-4 py-3 font-medium">Paket Terakhir</th>
                    <th class="px-4 py-3 font-medium">Sisa Pertemuan</th>
                    <th class="px-4 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($member as $item)
                    <tr>
                        <td class="px-4 py-3 text-slate-700">{{ $item->kode_member }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->user->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->user->email }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->user->phone }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->tanggal_gabung->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $item->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php $paketTerakhir = $item->paketLangganan->first(); @endphp
                            @if ($paketTerakhir)
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-semibold',
                                    'bg-yellow-100 text-yellow-700' => $paketTerakhir->status === 'pending_payment',
                                    'bg-green-100 text-green-700' => $paketTerakhir->status === 'active',
                                    'bg-slate-200 text-slate-600' => in_array($paketTerakhir->status, ['expired', 'cancelled', 'failed']),
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $paketTerakhir->status)) }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">Belum pernah beli</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->sisaPertemuanAktif() }}</td>
                        <td class="px-4 py-3">
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
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-slate-500">Belum ada member terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $member->links() }}
    </div>
</x-layouts.admin>
