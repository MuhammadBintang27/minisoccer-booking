<x-layouts.admin title="Data Jadwal">
    @if ($lapangan)
        <x-slot:actions>
            <a href="{{ route('admin.lapangan.jadwal.index', $lapangan) }}" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                Ubah Jadwal
            </a>
        </x-slot:actions>
    @endif

    <form method="GET" action="{{ route('admin.jadwal.index') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pilih Lapangan</label>
            <select name="lapangan_id" class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                @foreach ($daftarLapangan as $item)
                    <option value="{{ $item->id }}" {{ $lapangan && $lapangan->id === $item->id ? 'selected' : '' }}>
                        {{ $item->nama }} {{ $item->is_active ? '' : '(nonaktif)' }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
            Tampilkan
        </button>
    </form>

    @if (! $lapangan)
        <p class="mt-8 text-slate-500">Belum ada data lapangan.</p>
    @else
        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.jadwal.index', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->copy()->subMonth()->format('Y-m')]) }}"
                    class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                    &laquo; Sebelumnya
                </a>
                <div class="text-center">
                    <div class="text-sm font-bold text-slate-800">{{ $bulan->translatedFormat('F Y') }}</div>
                    <div class="text-xs text-slate-500">{{ $lapangan->nama }}</div>
                </div>
                <a href="{{ route('admin.jadwal.index', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->copy()->addMonth()->format('Y-m')]) }}"
                    class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                    Berikutnya &raquo;
                </a>
            </div>

            <div class="mt-3 flex items-center gap-4 border-t border-slate-100 pt-3 text-xs text-slate-500">
                <span>Keterangan:</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-gold"></span> Tersedia</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-slate-300"></span> Penuh/Tutup</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Sudah Lewat</span>
            </div>

            <div class="mt-4 grid grid-cols-7 gap-1 text-center text-[11px] font-semibold uppercase text-slate-400">
                <div>Sen</div>
                <div>Sel</div>
                <div>Rab</div>
                <div>Kam</div>
                <div>Jum</div>
                <div>Sab</div>
                <div>Min</div>
            </div>

            <div class="mt-1 grid grid-cols-7 gap-1">
                @foreach ($kalender as $minggu)
                    @foreach ($minggu as $cell)
                        @if (is_null($cell))
                            <div></div>
                        @elseif ($cell['isPast'] || ($cell['date']->isToday() && $cell['sisa'] === 0))
                            <a href="{{ route('admin.jadwal.index', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->format('Y-m'), 'tanggal' => $cell['date']->toDateString()]) }}"
                                class="rounded-lg border p-2 text-center hover:border-slate-300 {{ $tanggal && $tanggal->isSameDay($cell['date']) ? 'border-gold bg-gold/10' : 'border-slate-100 bg-slate-50' }}">
                                <div class="text-xs font-medium text-slate-400">{{ $cell['date']->day }}</div>
                                <div class="mt-1 flex items-center justify-center gap-1 text-[11px] text-slate-400">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-slate-300 text-[10px] font-bold text-slate-600">{{ $cell['terisi'] }}</span>
                                    /{{ $cell['total'] }}
                                </div>
                                <div class="text-[9px] uppercase tracking-wide text-slate-300">Lewat</div>
                            </a>
                        @elseif ($cell['sisa'] === 0)
                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-2 text-center">
                                <div class="text-xs font-medium text-slate-400">{{ $cell['date']->day }}</div>
                                <div class="mt-1 text-[11px] text-slate-300">&nbsp;</div>
                            </div>
                        @else
                            <a href="{{ route('admin.jadwal.index', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->format('Y-m'), 'tanggal' => $cell['date']->toDateString()]) }}"
                                class="rounded-lg border p-2 text-center hover:border-gold {{ $tanggal && $tanggal->isSameDay($cell['date']) ? 'border-gold bg-gold/10' : 'border-slate-200 bg-white' }}">
                                <div class="text-xs font-medium text-slate-700">{{ $cell['date']->day }}</div>
                                <div class="mt-1 flex items-center justify-center gap-1 text-[11px] text-slate-500">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-gold text-[10px] font-bold text-navy-dark">{{ $cell['sisa'] }}</span>
                                    /{{ $cell['total'] }}
                                </div>
                            </a>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>

        @if ($tanggal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4 backdrop-blur-sm">
                <div class="max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-5 shadow-xl ring-1 ring-slate-900/5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="text-sm font-semibold text-slate-800">
                            Slot {{ $tanggal->translatedFormat('l, d F Y') }}
                        </h2>
                        <a href="{{ route('admin.jadwal.index', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->format('Y-m')]) }}"
                            class="rounded-lg px-2 py-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            &#10005;
                        </a>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach ($slots as $item)
                            @php $slot = $item['slot']; @endphp
                            <div class="rounded-xl border p-4 shadow-sm {{ $item['status'] !== 'tersedia' ? 'bg-slate-100 border-slate-200' : 'bg-white border-slate-200' }}">
                                <div class="text-sm font-semibold text-slate-800">
                                    {{ substr($slot->jam_mulai, 0, 5) }}-{{ substr($slot->jam_selesai, 0, 5) }}
                                </div>
                                @if ($item['status'] === 'closed')
                                    <div class="mt-1 text-xs font-semibold text-slate-500">Tutup</div>
                                @elseif ($item['status'] === 'booked')
                                    <div class="mt-1 text-xs text-slate-600">Rp{{ number_format($slot->hargaUntukTanggal($tanggal, $item['sumber']), 0, ',', '.') }}</div>
                                    <div class="mt-1 text-xs font-semibold text-red-600">Terisi ({{ $item['sumber'] === 'member' ? 'Member' : 'Guest' }})</div>
                                @elseif ($item['status'] === 'lewat')
                                    <div class="mt-1 text-xs font-semibold text-slate-500">Sudah Lewat</div>
                                @else
                                    <div class="mt-1 text-xs text-slate-600">
                                        Member Rp{{ number_format($slot->hargaUntukTanggal($tanggal, 'member'), 0, ',', '.') }}<br>
                                        Non-member Rp{{ number_format($slot->hargaUntukTanggal($tanggal, 'guest'), 0, ',', '.') }}
                                    </div>
                                    <div class="mt-1 text-xs font-semibold text-blue-600">Tersedia</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif
</x-layouts.admin>
