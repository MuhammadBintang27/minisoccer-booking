<x-layouts.site title="Soccer Bumi Teuku Umar - Booking Lapangan Futsal Online">
    <section id="beranda" class="relative overflow-hidden bg-navy text-white">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-gold/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-16 h-72 w-72 rounded-full bg-gold/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-5xl px-6 py-20 text-center md:py-28">
            <span class="inline-block rounded-full border border-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-wider text-gold">
                Booking Online 24 Jam
            </span>
            <h1 class="mt-5 text-3xl font-bold leading-tight md:text-5xl">
                Main Futsal Tanpa Ribet,<br class="hidden md:block"> Booking Lapangan Sekarang
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-white/70">
                Soccer Bumi Teuku Umar &mdash; cek jadwal, pilih jam, dan bayar online langsung dari HP. Tersedia paket bulanan buat kamu yang main rutin.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="#jadwal" class="rounded-lg bg-gold px-6 py-3 font-semibold text-navy-dark hover:bg-gold-dark">
                    Cek Jadwal &amp; Booking
                </a>
                <a href="{{ route('register') }}" class="rounded-lg border border-white/30 px-6 py-3 font-semibold text-white hover:bg-white/10">
                    Daftar Jadi Member
                </a>
            </div>

            <div class="mx-auto mt-14 grid max-w-3xl grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                    <div class="text-2xl font-bold text-gold">{{ $daftarLapangan->count() }}</div>
                    <div class="mt-1 text-xs text-white/60">Lapangan Tersedia</div>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                    <div class="text-2xl font-bold text-gold">06.00-23.00</div>
                    <div class="mt-1 text-xs text-white/60">Jam Operasional</div>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                    <div class="text-2xl font-bold text-gold">Midtrans</div>
                    <div class="mt-1 text-xs text-white/60">Pembayaran Aman</div>
                </div>
            </div>
        </div>
    </section>

    <section id="jadwal" class="bg-slate-100 py-16">
        <div class="mx-auto max-w-5xl px-4 md:px-6">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-wider text-gold">Jadwal &amp; Booking</span>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Mau Booking yang Mana?</h2>
                <p class="mt-2 text-sm text-slate-500">Pilih salah satu sesuai kebutuhan kamu.</p>
            </div>

            <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-xl border-2 border-gold bg-white p-5 text-center">
                    <div class="text-base font-semibold text-slate-800">Sekali Main</div>
                    <p class="mt-1 text-xs text-slate-500">Pesan satu jadwal, bayar langsung online. Tanpa perlu bikin akun.</p>
                    <a href="#pilih-lapangan" class="mt-4 inline-block rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                        Cek Jadwal Kosong &darr;
                    </a>
                </div>
                <div class="rounded-xl border-2 border-navy/20 bg-white p-5 text-center">
                    <div class="text-base font-semibold text-slate-800">Paket Bulanan</div>
                    <p class="mt-1 text-xs text-slate-500">Main rutin tiap minggu di hari &amp; jam yang sama, langsung 4x pertemuan. Khusus member.</p>
                    <a href="{{ route('member.langganan.create') }}" class="mt-4 inline-block rounded-lg bg-navy px-5 py-2 text-sm font-semibold text-white hover:bg-navy-dark">
                        Pilih Paket Member
                    </a>
                </div>
            </div>

            <div id="pilih-lapangan" class="mx-auto mt-12 max-w-md text-center">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Booking Sekali Main</h3>
            </div>

            <form method="GET" action="{{ route('home') }}#pilih-lapangan" class="mx-auto mt-4 flex max-w-md flex-wrap items-end justify-center gap-3 rounded-xl bg-white p-4 shadow-sm">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-slate-700">Pilih Lapangan</label>
                    <select name="lapangan_id" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        @foreach ($daftarLapangan as $item)
                            <option value="{{ $item->id }}" {{ $lapangan && $lapangan->id === $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Tampilkan
                </button>
            </form>

            @if (! $lapangan)
                <p class="mt-8 text-center text-slate-500">Belum ada lapangan yang tersedia.</p>
            @else
                <div class="mx-auto mt-6 max-w-2xl rounded-xl bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->copy()->subMonth()->format('Y-m')]) }}#pilih-lapangan"
                            class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                            &laquo; Sebelumnya
                        </a>
                        <div class="text-center">
                            <div class="text-sm font-bold text-slate-800">{{ $bulan->translatedFormat('F Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $lapangan->nama }}</div>
                        </div>
                        <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->copy()->addMonth()->format('Y-m')]) }}#pilih-lapangan"
                            class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                            Berikutnya &raquo;
                        </a>
                    </div>

                    <div class="mt-3 flex items-center gap-4 border-t border-slate-100 pt-3 text-xs text-slate-500">
                        <span>Keterangan:</span>
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-gold"></span> Tersedia</span>
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-slate-300"></span> Penuh/Tutup</span>
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
                                @elseif ($cell['isPast'] || $cell['sisa'] === 0)
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-2 text-center">
                                        <div class="text-xs font-medium text-slate-400">{{ $cell['date']->day }}</div>
                                        <div class="mt-1 text-[11px] text-slate-300">&mdash;</div>
                                    </div>
                                @else
                                    <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->format('Y-m'), 'tanggal' => $cell['date']->toDateString()]) }}#pilih-lapangan"
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
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4">
                        <div class="max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-5 shadow-xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-slate-800">
                                    Slot {{ $tanggal->translatedFormat('l, d F Y') }}
                                </h3>
                                <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->format('Y-m')]) }}#pilih-lapangan"
                                    class="rounded-lg px-2 py-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                    &#10005;
                                </a>
                            </div>

                            <p class="mt-1 text-xs text-slate-500">Centang satu atau beberapa jam yang berurutan, lalu klik "Lanjut".</p>

                            <form method="GET" action="{{ route('guest.pemesanan.create') }}">
                                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                                <input type="hidden" name="tanggal" value="{{ $tanggal->toDateString() }}">

                                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                    @foreach ($slots as $item)
                                        @php $slot = $item['slot']; @endphp
                                        @if ($item['status'] === 'tersedia')
                                            <label class="cursor-pointer rounded-xl border border-slate-200 bg-white p-4 text-left shadow-sm has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                                <input type="checkbox" name="jadwal_id[]" value="{{ $slot->id }}" class="sr-only">
                                                <div class="text-sm font-semibold text-slate-800">
                                                    {{ substr($slot->jam_mulai, 0, 5) }}-{{ substr($slot->jam_selesai, 0, 5) }}
                                                </div>
                                                <div class="mt-1 text-xs text-slate-600">Rp{{ number_format($slot->hargaUntukTanggal($tanggal, 'guest'), 0, ',', '.') }}</div>
                                                <div class="mt-1 text-xs font-semibold text-blue-600">Tersedia</div>
                                            </label>
                                        @else
                                            <div class="rounded-xl border border-slate-200 bg-slate-100 p-4 shadow-sm">
                                                <div class="text-sm font-semibold text-slate-800">
                                                    {{ substr($slot->jam_mulai, 0, 5) }}-{{ substr($slot->jam_selesai, 0, 5) }}
                                                </div>
                                                @if ($item['status'] === 'closed')
                                                    <div class="mt-1 text-xs font-semibold text-slate-500">CLOSE</div>
                                                @else
                                                    <div class="mt-1 text-xs text-slate-600">Rp{{ number_format($slot->hargaUntukTanggal($tanggal, 'guest'), 0, ',', '.') }}</div>
                                                    <div class="mt-1 text-xs font-semibold text-red-600">Booked</div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <button type="submit" class="mt-4 w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                                    Lanjut
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>

    <section id="tentang-kami" class="bg-white py-16">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-10 px-6 md:grid-cols-2 md:items-center">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gold">Tentang Kami</span>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Soccer Bumi Teuku Umar</h2>
                <p class="mt-4 leading-relaxed text-slate-600">
                    Penyedia lapangan futsal dengan beberapa lapangan berkualitas, terbuka untuk umum maupun
                    member berlangganan bulanan. Booking jadwal lebih mudah lewat sistem online ini &mdash;
                    lihat ketersediaan lapangan secara real-time dan bayar langsung lewat payment gateway.
                </p>
                <a href="#jadwal" class="mt-6 inline-block rounded-lg bg-navy px-6 py-3 font-semibold text-white hover:bg-navy-dark">
                    Lihat Jadwal Sekarang
                </a>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-3 rounded-xl border border-slate-200 p-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold/20 font-bold text-navy">1</span>
                    <div>
                        <div class="font-semibold text-slate-800">Multi-Lapangan</div>
                        <div class="text-sm text-slate-500">Beberapa lapangan indoor dengan jadwal masing-masing, tinggal pilih yang kosong.</div>
                    </div>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-slate-200 p-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold/20 font-bold text-navy">2</span>
                    <div>
                        <div class="font-semibold text-slate-800">Paket Bulanan Member</div>
                        <div class="text-sm text-slate-500">Main rutin tiap minggu? Daftar member dan kunci jadwal favoritmu untuk sebulan.</div>
                    </div>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-slate-200 p-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold/20 font-bold text-navy">3</span>
                    <div>
                        <div class="font-semibold text-slate-800">Pembayaran Online Aman</div>
                        <div class="text-sm text-slate-500">Transfer bank, e-wallet, sampai kartu &mdash; semua lewat Midtrans, langsung terkonfirmasi.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-navy-dark py-8 text-center text-xs text-white/50">
        &copy; {{ now()->year }} Soccer Bumi Teuku Umar. Semua hak dilindungi.
    </footer>
</x-layouts.site>
