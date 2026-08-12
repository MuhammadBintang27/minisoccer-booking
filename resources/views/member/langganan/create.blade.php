<x-layouts.site title="Beli Paket Bulanan - MYSOC Meulaboh">
    <section class="mx-auto max-w-2xl px-4 py-10 md:px-6">
        <h1 class="text-xl font-semibold text-slate-800">Beli Paket Bulanan</h1>
        <p class="mt-1 text-sm text-slate-500">Kunci jadwal main mingguan kamu di MYSOC: pilih lapangan, hari, dan bulan buat lihat jam mana saja yang kosong 4 minggu ke depan.</p>

        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold/20 text-xs font-bold text-navy">1</span>
                <h2 class="text-sm font-semibold text-slate-700">Pilih Jadwal</h2>
            </div>

            <form method="GET" action="{{ route('member.langganan.create') }}" class="mt-3 flex flex-wrap items-end gap-3">
                <div class="min-w-[160px] flex-1">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Lapangan</label>
                    <select name="lapangan_id" class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                        <option value="">-- Pilih --</option>
                        @foreach ($daftarLapangan as $item)
                            <option value="{{ $item->id }}" {{ $lapangan && $lapangan->id === $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[140px]">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Hari</label>
                    <select name="hari" class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                        <option value="">-- Pilih --</option>
                        @foreach (['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'] as $value => $label)
                            <option value="{{ $value }}" {{ $hari === (int) $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[160px]">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Bulan</label>
                    <select name="bulan" class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                        @foreach ($bulanOptions as $opsi)
                            <option value="{{ $opsi->format('Y-m') }}" {{ $bulan->isSameMonth($opsi) ? 'selected' : '' }}>{{ $opsi->translatedFormat('F Y') }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Tampilkan Jadwal
                </button>
            </form>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (! $lapangan || ! $hari)
            <div class="mt-4 rounded-xl border border-dashed border-slate-200 px-4 py-10 text-center text-sm text-slate-400">
                Pilih lapangan dan hari dulu di atas untuk lihat jam yang kosong.
            </div>
        @elseif ($slots->isEmpty())
            <div class="mt-4 rounded-xl border border-dashed border-slate-200 px-4 py-10 text-center text-sm text-slate-400">
                Lapangan ini belum punya jadwal jam yang diatur.
            </div>
        @else
            <form method="POST" action="{{ route('member.langganan.store') }}" class="mt-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                <input type="hidden" name="hari" value="{{ $hari }}">
                <input type="hidden" name="bulan" value="{{ $bulan->format('Y-m') }}">

                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold/20 text-xs font-bold text-navy">2</span>
                    <h2 class="text-sm font-semibold text-slate-700">Pilih Jam &amp; Layanan Tambahan</h2>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-1.5 rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-500">
                    <span class="font-medium text-slate-600">4 pertemuan:</span>
                    @foreach ($tanggalPreview as $tanggal)
                        <span class="rounded-full bg-white px-2 py-0.5 font-medium text-slate-600 ring-1 ring-slate-200">{{ $tanggal->translatedFormat('d M') }}</span>
                    @endforeach
                </div>

                <div class="mt-4">
                    <div class="text-xs font-medium text-slate-700">Jam (centang 1 atau lebih, harus berurutan)</div>

                    <div class="mt-2 space-y-2">
                        @foreach ($slots as $slot)
                            @php $slotTersedia = $previewPerSlot[$slot->id]->every(fn ($status) => $status === 'tersedia'); @endphp
                            <label @class([
                                'flex items-center justify-between rounded-lg border p-3 text-xs transition-colors has-[:checked]:border-gold has-[:checked]:bg-gold/10',
                                'cursor-pointer border-slate-200 hover:border-slate-300' => $slotTersedia,
                                'cursor-not-allowed border-slate-100 bg-slate-50 opacity-60' => ! $slotTersedia,
                            ])>
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" name="jadwal_id[]" value="{{ $slot->id }}" class="rounded border-slate-300 text-navy focus:ring-navy/30" {{ $slotTersedia ? '' : 'disabled' }}>
                                    <span>
                                        <span class="font-semibold text-slate-800">{{ substr($slot->jam_mulai, 0, 5) }}-{{ substr($slot->jam_selesai, 0, 5) }}</span>
                                        <span class="ml-1 text-slate-500">
                                            (Rp{{ number_format($slot->hargaUntukTanggal($tanggalPreview->first(), 'member'), 0, ',', '.') }}/pertemuan)
                                        </span>
                                    </span>
                                </span>
                                <span class="flex items-center gap-1">
                                    @foreach ($previewPerSlot[$slot->id] as $status)
                                        <span @class([
                                            'flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold',
                                            'bg-green-100 text-green-700' => $status === 'tersedia',
                                            'bg-red-100 text-red-600' => $status === 'booked',
                                            'bg-slate-200 text-slate-500' => in_array($status, ['closed', 'lewat']),
                                        ])>
                                            {{ $status === 'tersedia' ? '✓' : '✕' }}
                                        </span>
                                    @endforeach
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">Centang hijau (✓) = kosong di 4 tanggal itu, merah (✕) = sudah ada yang pakai/tutup/lewat jamnya di salah satu tanggal.</p>
                </div>

                @if ($layananTambahan->isNotEmpty())
                    <div class="mt-4">
                        <div class="text-xs font-medium text-slate-700">Layanan Tambahan <span class="font-normal text-slate-400">(opsional, berlaku tiap pertemuan)</span></div>
                        <div class="mt-2 space-y-2">
                            @foreach ($layananTambahan as $addon)
                                <label class="flex cursor-pointer items-center justify-between gap-3 rounded-lg border border-slate-200 px-3 py-2 text-sm transition-colors hover:border-slate-300 has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                    <span class="flex items-center gap-2">
                                        <input type="checkbox" name="layanan_tambahan_id[]" value="{{ $addon->id }}" class="rounded border-slate-300 text-navy focus:ring-navy/30">
                                        {{ $addon->nama }}
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <span class="text-slate-500">Rp{{ number_format($addon->harga, 0, ',', '.') }}</span>
                                        @if ($addon->pakai_jumlah)
                                            <input type="number" name="layanan_tambahan_jumlah[{{ $addon->id }}]" value="1" min="1" max="20"
                                                aria-label="Jumlah {{ $addon->nama }}"
                                                class="w-14 rounded-lg border border-slate-300 px-2 py-1 text-xs focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">Jumlah hanya berlaku untuk layanan yang dicentang, diulang tiap pertemuan.</p>
                    </div>
                @endif

                <div class="mt-4 rounded-lg border border-slate-100 bg-slate-50 p-3 text-xs leading-relaxed text-slate-600">
                    Sistem akan membuat pemesanan untuk hari yang dipilih setiap minggu, tepat 4 kali (4 minggu) berturut-turut dari kemunculan pertama yang masih tersedia, lalu total harga dibayar sekaligus. Kalau salah satu tanggal sudah terisi orang lain, seluruh pembelian akan ditolak.
                </div>

                <button type="submit" class="mt-4 w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Buat Paket
                </button>
            </form>
        @endif
    </section>
</x-layouts.site>
