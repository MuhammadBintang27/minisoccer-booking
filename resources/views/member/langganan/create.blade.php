<x-layouts.site title="Beli Paket Bulanan">
    <section class="mx-auto max-w-2xl px-4 py-10 md:px-6">
        <h1 class="text-xl font-semibold text-slate-800">Beli Paket Bulanan</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih lapangan, hari, dan bulan dulu untuk lihat jam mana saja yang kosong 4 minggu ke depan.</p>

        <form method="GET" action="{{ route('member.langganan.create') }}" class="mt-6 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow-sm">
            <div class="min-w-[160px] flex-1">
                <label class="block text-xs font-medium text-slate-700">Lapangan</label>
                <select name="lapangan_id" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">-- Pilih --</option>
                    @foreach ($daftarLapangan as $item)
                        <option value="{{ $item->id }}" {{ $lapangan && $lapangan->id === $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-slate-700">Hari</label>
                <select name="hari" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">-- Pilih --</option>
                    @foreach (['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'] as $value => $label)
                        <option value="{{ $value }}" {{ $hari === (int) $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-slate-700">Bulan</label>
                <select name="bulan" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    @foreach ($bulanOptions as $opsi)
                        <option value="{{ $opsi->format('Y-m') }}" {{ $bulan->isSameMonth($opsi) ? 'selected' : '' }}>{{ $opsi->translatedFormat('F Y') }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                Tampilkan Jadwal
            </button>
        </form>

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if ($lapangan && $hari)
            <form method="POST" action="{{ route('member.langganan.store') }}" class="mt-4 space-y-4 rounded-xl bg-white p-4 shadow-sm">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                <input type="hidden" name="hari" value="{{ $hari }}">
                <input type="hidden" name="bulan" value="{{ $bulan->format('Y-m') }}">

                <div>
                    <div class="flex items-center justify-between">
                        <div class="block text-xs font-medium text-slate-700">Jam (centang 1 atau lebih, harus berurutan)</div>
                        <div class="text-xs text-slate-400">4 pertemuan: {{ $tanggalPreview->map(fn ($t) => $t->format('d/m'))->join(', ') }}</div>
                    </div>

                    <div class="mt-2 space-y-2">
                        @foreach ($slots as $slot)
                            <label class="flex cursor-pointer items-center justify-between rounded-lg border border-slate-200 p-2 text-xs has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" name="jadwal_id[]" value="{{ $slot->id }}" class="rounded border-slate-300">
                                    <span>
                                        <span class="font-semibold text-slate-800">{{ substr($slot->jam_mulai, 0, 5) }}-{{ substr($slot->jam_selesai, 0, 5) }}</span>
                                        <span class="ml-1 text-slate-500">
                                            (Rp{{ number_format($slot->harga_weekday_member, 0, ',', '.') }} weekday / Rp{{ number_format($slot->harga_weekend_member, 0, ',', '.') }} weekend)
                                        </span>
                                    </span>
                                </span>
                                <span class="flex items-center gap-1">
                                    @foreach ($previewPerSlot[$slot->id] as $status)
                                        <span @class([
                                            'flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold',
                                            'bg-green-100 text-green-700' => $status === 'tersedia',
                                            'bg-red-100 text-red-600' => $status === 'booked',
                                            'bg-slate-200 text-slate-500' => $status === 'closed',
                                        ])>
                                            {{ $status === 'tersedia' ? '✓' : '✕' }}
                                        </span>
                                    @endforeach
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Centang hijau (✓) = kosong di 4 tanggal itu, merah (✕) = sudah ada yang pakai/tutup di salah satu tanggal.</p>
                </div>

                @if ($layananTambahan->isNotEmpty())
                    <div>
                        <div class="block text-xs font-medium text-slate-700">Layanan Tambahan (opsional, dihitung per jam main setiap pertemuan)</div>
                        <div class="mt-2 space-y-2">
                            @foreach ($layananTambahan as $addon)
                                <label class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-sm has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                    <span class="flex items-center gap-2">
                                        <input type="checkbox" name="layanan_tambahan_id[]" value="{{ $addon->id }}" class="rounded border-slate-300">
                                        {{ $addon->nama }}
                                    </span>
                                    <span class="text-slate-500">Rp{{ number_format($addon->harga, 0, ',', '.') }}/jam</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <p class="text-xs text-slate-500">
                    Sistem akan membuat pemesanan untuk hari yang dipilih setiap minggu, tepat 4 kali (4 minggu) dari kemunculan pertama di bulan tersebut, lalu total harga dibayar sekaligus. Kalau salah satu tanggal sudah terisi orang lain, seluruh pembelian akan ditolak.
                </p>

                <button type="submit" class="w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Buat Paket
                </button>
            </form>
        @endif
    </section>
</x-layouts.site>
