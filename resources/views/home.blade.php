<x-layouts.site title="MYSOC - Booking Lapangan Mini Soccer Meulaboh | My Soccer Bumi Teuku Umar">
    <section id="beranda" class="relative overflow-hidden bg-navy text-white">
        <div class="pointer-events-none absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-gold/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-16 h-72 w-72 rounded-full bg-gold/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-5xl px-6 py-20 text-center md:py-28">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-gold">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                Mini Soccer &middot; Meulaboh, Aceh Barat
            </span>
           <h1 class="mt-5 text-3xl font-bold leading-tight md:text-5xl">
                Rasakan Pengalaman Bermain<br class="hidden md:block">
                Kelas Profesional
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-white/70">
                My Soccer Bumi Teuku Umar menghadirkan lapangan mini soccer dengan
                rumput sintetis berstandar <strong>FIFA Quality Pro</strong>, dilengkapi sistem
                booking online yang cepat, mudah, dan aman.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="#jadwal" class="rounded-lg bg-gold px-6 py-3 font-semibold text-navy-dark shadow-lg shadow-gold/20 transition-transform hover:-translate-y-0.5 hover:bg-gold-dark">
                    Cek Jadwal &amp; Booking
                </a>
                <a href="{{ route('register') }}" class="rounded-lg border border-white/30 px-6 py-3 font-semibold text-white transition-colors hover:bg-white/10">
                    Daftar Jadi Member
                </a>
            </div>

            <div class="mx-auto mt-14 grid max-w-3xl grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-white/10 bg-white/5 p-4 transition-colors hover:bg-white/10">
                    <div class="text-2xl font-bold text-gold">FIFA</div>
                    <div class="mt-1 text-xs text-white/60">Rumput Standar Quality Pro</div>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/5 p-4 transition-colors hover:bg-white/10">
                    <div class="text-2xl font-bold text-gold">06.00-23.00</div>
                    <div class="mt-1 text-xs text-white/60">Buka Setiap Hari</div>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/5 p-4 transition-colors hover:bg-white/10">
                    <div class="text-2xl font-bold text-gold">24 Jam</div>
                    <div class="mt-1 text-xs text-white/60">Booking Online Kapan Saja</div>
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
                <div class="group rounded-xl border-2 border-gold bg-white p-5 text-center shadow-sm transition-shadow hover:shadow-md">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gold/15 text-navy">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-13.5-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm3-3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                    </span>
                    <div class="mt-3 text-base font-semibold text-slate-800">Sekali Main</div>
                    <p class="mt-1 text-xs text-slate-500">Pesan satu jadwal, bayar langsung online. Tanpa perlu bikin akun.</p>
                    <a href="#pilih-lapangan" class="mt-4 inline-block rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark transition-transform group-hover:-translate-y-0.5 hover:bg-gold-dark">
                        Cek Jadwal Kosong &darr;
                    </a>
                </div>
                <div class="group rounded-xl border-2 border-navy/20 bg-white p-5 text-center shadow-sm transition-shadow hover:shadow-md">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-navy/10 text-navy">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </span>
                    <div class="mt-3 text-base font-semibold text-slate-800">Paket Bulanan</div>
                    <p class="mt-1 text-xs text-slate-500">Main rutin tiap minggu di hari &amp; jam yang sama, langsung 4x pertemuan. Khusus member.</p>
                    <a href="{{ route('member.langganan.create') }}" class="mt-4 inline-block rounded-lg bg-navy px-5 py-2 text-sm font-semibold text-white transition-transform group-hover:-translate-y-0.5 hover:bg-navy-dark">
                        Pilih Paket Member
                    </a>
                </div>
            </div>

            <div id="pilih-lapangan" class="mx-auto mt-12 max-w-md text-center">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Booking Sekali Main</h3>
            </div>

            <form method="GET" action="{{ route('home') }}#pilih-lapangan" class="mx-auto mt-4 flex max-w-md flex-wrap items-end justify-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pilih Lapangan</label>
                    <select name="lapangan_id" class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none focus:ring-1 focus:ring-navy/30">
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
                <div class="mx-auto mt-6 max-w-2xl rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
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
                                        <div class="mt-1 text-[11px] text-slate-300">&nbsp;</div>
                                    </div>
                                @else
                                    <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->format('Y-m'), 'tanggal' => $cell['date']->toDateString()]) }}#pilih-lapangan"
                                        class="rounded-lg border p-2 text-center transition-colors hover:border-gold {{ $tanggal && $tanggal->isSameDay($cell['date']) ? 'border-gold bg-gold/10' : 'border-slate-200 bg-white' }}">
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
                                <h3 class="text-sm font-semibold text-slate-800">
                                    Slot {{ $tanggal->translatedFormat('l, d F Y') }}
                                </h3>
                                <a href="{{ route('home', ['lapangan_id' => $lapangan->id, 'bulan' => $bulan->format('Y-m')]) }}#pilih-lapangan"
                                    class="rounded-lg px-2 py-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                    &#10005;
                                </a>
                            </div>

                            <p class="mt-3 text-xs text-slate-500">Centang satu atau beberapa jam yang berurutan, lalu klik "Lanjut".</p>

                            <form method="GET" action="{{ route('guest.pemesanan.create') }}">
                                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                                <input type="hidden" name="tanggal" value="{{ $tanggal->toDateString() }}">

                                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                    @foreach ($slots as $item)
                                        @php $slot = $item['slot']; @endphp
                                        @if ($item['status'] === 'tersedia')
                                            <label class="cursor-pointer rounded-xl border border-slate-200 bg-white p-4 text-left shadow-sm transition-colors hover:border-slate-300 has-[:checked]:border-gold has-[:checked]:bg-gold/10">
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
                                                    <div class="mt-1 text-xs font-semibold text-slate-500">Tutup</div>
                                                @elseif ($item['status'] === 'lewat')
                                                    <div class="mt-1 text-xs font-semibold text-slate-500">Sudah Lewat</div>
                                                @else
                                                    <div class="mt-1 text-xs text-slate-600">Rp{{ number_format($slot->hargaUntukTanggal($tanggal, 'guest'), 0, ',', '.') }}</div>
                                                    <div class="mt-1 text-xs font-semibold text-red-600">Terisi</div>
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
        <div class="mx-auto max-w-5xl px-6">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-2 md:items-center">
                <div class="relative">
                    <div class="aspect-[4/3] w-full overflow-hidden rounded-2xl bg-slate-100 shadow-xl ring-1 ring-slate-900/5">
                        <img src="{{ asset('images/lapangan.webp') }}" alt="Lapangan mini soccer MYSOC - My Soccer Bumi Teuku Umar, Meulaboh" class="h-full w-full object-cover">
                    </div>
                    <div class="absolute -bottom-6 -right-6 hidden rounded-xl bg-navy px-5 py-4 text-white shadow-lg sm:block">
                        <div class="text-2xl font-bold text-gold">FIFA</div>
                        <div class="text-xs text-white/70">Quality Pro Standard</div>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gold">Tentang Kami</span>
                    <h2 class="mt-2 text-2xl font-bold text-slate-800">My Soccer Bumi Teuku Umar</h2>
                    <p class="mt-4 leading-relaxed text-slate-600">
                        MYSOC adalah lapangan mini soccer di Meulaboh, Aceh Barat, dengan rumput sintetis
                        berstandar internasional <span class="font-semibold text-slate-800">FIFA Quality Pro</span>.
                        Terbuka untuk booking santai, sparring antar tim, sampai turnamen komunitas — dan jadi markas
                        latihan Sekolah Sepak Bola (SSB) untuk pemain muda.
                    </p>
                    <p class="mt-3 leading-relaxed text-slate-600">
                        Selesai main, langsung nongkrong di cafe <span class="font-semibold text-slate-800">MYCO. &amp; MY KOPI SARENG</span>
                        yang ada tepat di samping lapangan. Booking jadwal makin gampang lewat sistem online ini:
                        lihat ketersediaan real-time, kunci jam favoritmu, bayar langsung dari HP.
                    </p>
                    <a href="#jadwal" class="mt-6 inline-block rounded-lg bg-navy px-6 py-3 font-semibold text-white transition-colors hover:bg-navy-dark">
                        Lihat Jadwal Sekarang
                    </a>
                </div>
            </div>

            <div class="mt-14 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Rumput Standar FIFA Quality Pro</div>
                    <div class="mt-1 text-sm text-slate-500">Rumput sintetis kelas internasional: nyaman di kaki, bola menggelinding mulus, minim cedera.</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Sekolah Sepak Bola (SSB)</div>
                    <div class="mt-1 text-sm text-slate-500">Markas latihan dan pendaftaran SSB untuk pemain muda Meulaboh dan sekitarnya.</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.379a48.474 48.474 0 00-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Cafe MYCO. &amp; MY KOPI SARENG</div>
                    <div class="mt-1 text-sm text-slate-500">Kopi dan makanan tepat di pinggir lapangan, tempat nongkrong sebelum dan sesudah main.</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Pembayaran Online Aman</div>
                    <div class="mt-1 text-sm text-slate-500">Transfer bank, e-wallet dan QRIS semua lewat Midtrans dan langsung terkonfirmasi.</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6.25 3.75h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Loker Penyimpanan</div>
                    <div class="mt-1 text-sm text-slate-500">Simpan baju, tas, dan barang bawaanmu di loker, main jadi tenang tanpa jaga barang.</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a6.75 6.75 0 006.75-6.75c0-4.5-6.75-10.5-6.75-10.5S5.25 9.75 5.25 14.25A6.75 6.75 0 0012 21z" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Kamar Mandi &amp; Bilas</div>
                    <div class="mt-1 text-sm text-slate-500">Selesai main bisa langsung mandi di tempat, pulang atau lanjut aktivitas tetap segar.</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Sewa Sepatu</div>
                    <div class="mt-1 text-sm text-slate-500">Nggak bawa sepatu bola? Tenang, tersedia sewa sepatu langsung di lapangan.</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 transition-shadow hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/20 text-navy">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div class="mt-3 font-semibold text-slate-800">Booking Online 24 Jam</div>
                    <div class="mt-1 text-sm text-slate-500">Cek jadwal dan kunci slot kapan saja dari HP, tanpa harus datang atau telepon dulu.</div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="bg-slate-100 py-16">
        <div class="mx-auto max-w-3xl px-6">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-wider text-gold">FAQ</span>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Pertanyaan yang Sering Ditanyakan</h2>
                <p class="mt-2 text-sm text-slate-500">Masih ada pertanyaan lain? Hubungi kami langsung di lapangan.</p>
            </div>

            <div class="mt-8 space-y-3">
                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Mini soccer itu apa bedanya dengan futsal?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Mini soccer dimainkan di lapangan rumput sintetis yang lebih luas dari futsal (biasanya 7 vs 7), dengan bola dan aturan yang lebih dekat ke sepak bola sungguhan. Di MYSOC, rumputnya berstandar FIFA Quality Pro — jadi rasanya seperti main di lapangan bola beneran.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Apakah harus punya akun untuk booking?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Tidak. Untuk booking sekali main, cukup isi nama dan nomor HP saat pemesanan, tanpa perlu daftar akun. Akun hanya diperlukan kalau kamu mau ambil paket bulanan sebagai member.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Apa bedanya "Sekali Main" dan "Paket Bulanan"?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        "Sekali Main" untuk booking satu jadwal saja, bisa dipakai siapa saja termasuk tanpa akun. "Paket Bulanan" khusus member: pilih satu hari dan jam tetap, sistem otomatis membuatkan 4 pertemuan berturut-turut tiap minggu di hari yang sama.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Berapa yang harus dibayar di awal?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Cukup DP minimal 25% dari total tagihan untuk mengunci jadwalnya. Sisanya bisa dibayar belakangan, kapan saja.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Bagaimana cara melunasi sisa pembayaran?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Bisa online kapan saja lewat halaman status booking/paket kamu (transfer bank, e-wallet, kartu, dan lainnya via Midtrans), atau bayar tunai langsung di lapangan saat datang bermain, nanti dikonfirmasi oleh admin.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Apakah ada batas waktu pelunasan sisa tagihan?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        DP awal harus dibayar dalam 15 menit supaya jadwalnya tidak dilepas kembali untuk orang lain. Tapi setelah DP masuk dan jadwal terkonfirmasi, sisa tagihan tidak ada batas waktu, bisa dilunasi kapan saja.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Metode pembayaran apa saja yang tersedia?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Semua metode dari Midtrans (transfer bank, e-wallet, kartu kredit/debit, QRIS), ditambah opsi bayar tunai langsung di lapangan.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Saya lupa detail booking saya, bagaimana cara ceknya?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Kalau booking tanpa akun (guest), buka halaman "Cek Booking" dan masukkan nomor HP yang dipakai saat pemesanan. Kalau member, tinggal buka menu "Riwayat Pemesanan" setelah login.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Apakah harganya sama tiap hari dan untuk semua orang?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Tidak. Harga dibedakan antara hari biasa (weekday) dan akhir pekan (weekend), dan member mendapat harga lebih murah dibanding non-member di setiap slot jamnya.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Bagaimana kalau jadwal yang saya mau ternyata sudah dipesan orang lain?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Ketersediaan jadwal ditampilkan secara real-time, jadi slot yang sudah dipesan otomatis tidak bisa dipilih lagi. Kalau dua orang mencoba booking slot yang sama persis di saat bersamaan, sistem hanya akan memproses salah satu dan menolak yang lain supaya tidak bentrok.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-4 open:shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-slate-800">
                        Apa itu layanan tambahan (add-on)?
                        <span class="ml-4 shrink-0 text-slate-400 transition-transform group-open:rotate-45">&#43;</span>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Layanan opsional seperti fotografer yang bisa ditambahkan saat booking. Harganya dihitung per jam main, jadi semakin lama main, biayanya menyesuaikan.
                    </p>
                </details>
            </div>
        </div>
    </section>
</x-layouts.site>
