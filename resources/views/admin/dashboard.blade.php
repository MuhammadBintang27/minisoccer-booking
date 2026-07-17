<x-layouts.admin title="Dashboard">
    <p class="text-sm text-slate-500">Selamat datang, {{ auth()->user()->name }}.</p>

    <div class="mt-4 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Lapangan Aktif</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">{{ $totalLapangan }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Member Aktif</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">{{ $totalMember }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Booking Hari Ini</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">{{ $bookingHariIni->count() }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">Pendapatan Bulan Ini</div>
            <div class="mt-1 text-lg font-bold tabular-nums text-slate-800">Rp{{ number_format($ringkasanBulanIni['total'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-800">Booking Hari Ini</h2>
                <a href="{{ route('admin.pemesanan.index', ['tanggal' => now()->toDateString()]) }}" class="text-xs font-semibold text-navy hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($bookingHariIni as $item)
                    <div class="flex items-center justify-between px-4 py-3 text-sm transition-colors hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <x-avatar-initial :name="$item->sumber === 'member' ? $item->member?->user?->name : $item->nama_tamu" />
                            <div>
                                <div class="font-medium text-slate-800">
                                    {{ substr($item->jam_mulai, 0, 5) }}-{{ substr($item->jam_selesai, 0, 5) }} &middot; {{ $item->lapangan->nama }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $item->sumber === 'member' ? $item->member?->user?->name : $item->nama_tamu }}
                                    <span class="ml-1 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $item->sumber === 'member' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($item->sumber) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <x-status-badge :status="$item->status" />
                    </div>
                @empty
                    <div class="px-4 py-14 text-center text-sm text-slate-400">Belum ada booking hari ini.</div>
                @endforelse
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-800">Menunggu Pembayaran</h2>
                <a href="{{ route('admin.pemesanan.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-navy hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($guestPending as $item)
                    <a href="{{ route('admin.pemesanan.show', $item) }}" class="flex items-center justify-between px-4 py-3 text-sm transition-colors hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <x-avatar-initial :name="$item->nama_tamu" />
                            <div>
                                <div class="font-medium text-slate-800">{{ $item->nama_tamu }} &middot; {{ $item->lapangan->nama }}</div>
                                <div class="text-xs text-slate-500">{{ $item->tanggal_main->translatedFormat('d M Y') }}, {{ substr($item->jam_mulai, 0, 5) }}-{{ substr($item->jam_selesai, 0, 5) }}</div>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Guest</span>
                    </a>
                @empty
                @endforelse

                @forelse ($paketPending as $paket)
                    <a href="{{ route('admin.langganan.show', $paket) }}" class="flex items-center justify-between px-4 py-3 text-sm transition-colors hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <x-avatar-initial :name="$paket->member?->user?->name" />
                            <div>
                                <div class="font-medium text-slate-800">{{ $paket->member?->user?->name }} &middot; {{ $paket->lapangan->nama }}</div>
                                <div class="text-xs text-slate-500">{{ $paket->periode_mulai->translatedFormat('d M Y') }}, {{ substr($paket->jam_mulai, 0, 5) }}-{{ substr($paket->jam_selesai, 0, 5) }}</div>
                            </div>
                        </div>
                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700">Member</span>
                    </a>
                @empty
                @endforelse

                @if ($guestPending->isEmpty() && $paketPending->isEmpty())
                    <div class="px-4 py-14 text-center text-sm text-slate-400">Tidak ada yang menunggu pembayaran.</div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
