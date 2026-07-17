<x-layouts.site title="Status Pemesanan - MYSOC Meulaboh">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between gap-3 bg-navy px-6 py-4 text-white">
                <div>
                    <h1 class="text-lg font-semibold">Status Pemesanan</h1>
                    <p class="mt-0.5 text-xs text-white/60">MYSOC &middot; My Soccer Bumi Teuku Umar</p>
                </div>
                <span @class([
                    'shrink-0 rounded-full px-3 py-1 text-xs font-semibold',
                    'bg-yellow-100 text-yellow-700' => $pemesanan->status === 'pending',
                    'bg-green-100 text-green-700' => in_array($pemesanan->status, ['confirmed', 'completed']),
                    'bg-slate-200 text-slate-600' => in_array($pemesanan->status, ['expired', 'cancelled']),
                ])>
                    {{ \App\Support\StatusLabel::label($pemesanan->status) }}
                </span>
            </div>

            <div class="p-6">
                <div class="space-y-1.5 text-sm text-slate-600">
                    <div class="text-base font-semibold text-slate-800">{{ $pemesanan->lapangan->nama }}</div>
                    @if ($pemesanan->nama_tamu)
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Atas nama {{ $pemesanan->nama_tamu }}
                        </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        {{ $pemesanan->tanggal_main->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Jam {{ substr($pemesanan->jam_mulai, 0, 5) }} sampai {{ substr($pemesanan->jam_selesai, 0, 5) }}
                    </div>
                </div>

                <div class="mt-4 space-y-1 rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Sewa Lapangan</span>
                        <span>Rp{{ number_format($pemesanan->harga, 0, ',', '.') }}</span>
                    </div>
                    @foreach ($pemesanan->layananTambahan as $addon)
                        <div class="flex justify-between">
                            <span>{{ $addon->nama }}</span>
                            <span>Rp{{ number_format($addon->pivot->harga, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between border-t border-slate-200 pt-1 font-semibold text-slate-800">
                        <span>Total Tagihan</span>
                        <span>Rp{{ number_format($pemesanan->totalHarga(), 0, ',', '.') }}</span>
                    </div>
                </div>

                @if (in_array($pemesanan->status, ['confirmed', 'completed']))
                    @php
                        $persenBayar = $pemesanan->totalHarga() > 0
                            ? min(100, (int) round($pemesanan->totalDibayar() / $pemesanan->totalHarga() * 100))
                            : 100;
                    @endphp
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>Sudah dibayar Rp{{ number_format($pemesanan->totalDibayar(), 0, ',', '.') }}</span>
                            <span>{{ $persenBayar }}%</span>
                        </div>
                        <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-gold" style="width: {{ $persenBayar }}%"></div>
                        </div>
                        @if ($pemesanan->sisaTagihan() > 0)
                            <div class="mt-1.5 text-xs font-semibold text-red-600">
                                Sisa tagihan Rp{{ number_format($pemesanan->sisaTagihan(), 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                @endif

                <div @class([
                    'mt-4 flex items-start gap-2.5 rounded-lg border p-3 text-xs leading-relaxed',
                    'border-yellow-200 bg-yellow-50 text-yellow-800' => $pemesanan->status === 'pending',
                    'border-green-200 bg-green-50 text-green-800' => in_array($pemesanan->status, ['confirmed', 'completed']),
                    'border-slate-100 bg-slate-50 text-slate-600' => in_array($pemesanan->status, ['expired', 'cancelled']),
                ])>
                    @if ($pemesanan->status === 'pending')
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Selesaikan DP minimal <span class="font-semibold">Rp{{ number_format($pemesanan->dpMinimum(), 0, ',', '.') }}</span> dalam 15 menit supaya jadwal ini terkunci untuk Anda. Kalau tidak, slot ini otomatis dilepas kembali dan bisa dipesan orang lain.</span>
                    @elseif ($pemesanan->status === 'confirmed' && $pemesanan->sisaTagihan() > 0)
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Booking Anda sudah aman, tidak ada batas waktu untuk pelunasan. Sisa tagihan bisa dibayar kapan saja lewat transfer atau e-wallet secara online di bawah ini, atau tunai langsung di lapangan saat Anda datang bermain.</span>
                    @elseif (in_array($pemesanan->status, ['confirmed', 'completed']) && $pemesanan->sisaTagihan() <= 0)
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pembayaran sudah lunas. Sampai jumpa di lapangan!</span>
                    @elseif ($pemesanan->status === 'expired')
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Waktu pembayaran sudah habis dan slot ini sudah dilepas kembali. Silakan lakukan pemesanan baru lewat halaman jadwal.</span>
                    @elseif ($pemesanan->status === 'cancelled')
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pemesanan ini sudah dibatalkan.</span>
                    @endif
                </div>

                @if (! empty($opsiBayar))
                    <div class="mt-4 space-y-2">
                        @foreach ($opsiBayar as $opsi)
                            <button type="button" data-jenis="{{ $opsi['jenis'] }}" class="btn-opsi-bayar w-full rounded-lg bg-gold px-4 py-2.5 text-sm font-semibold text-navy-dark shadow-sm transition-colors hover:bg-gold-dark disabled:opacity-50">
                                {{ $opsi['label'] }} (Rp{{ number_format($opsi['jumlah'], 0, ',', '.') }})
                            </button>
                        @endforeach
                    </div>

                    <script src="https://app.{{ config('services.midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
                        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
                    <script>
                        document.querySelectorAll('.btn-opsi-bayar').forEach(function (btn) {
                            btn.addEventListener('click', function () {
                                btn.disabled = true;

                                fetch(@json($opsiBayarUrl), {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': @json(csrf_token()),
                                    },
                                    body: JSON.stringify({ jenis: btn.dataset.jenis }),
                                })
                                    .then(function (res) { return res.json(); })
                                    .then(function (data) {
                                        window.snap.pay(data.snap_token, {
                                            onSuccess: function () { window.location.reload(); },
                                            onPending: function () { window.location.reload(); },
                                            onError: function () { alert('Pembayaran gagal, silakan coba lagi.'); btn.disabled = false; },
                                            onClose: function () { btn.disabled = false; },
                                        });
                                    })
                                    .catch(function () {
                                        alert('Gagal memuat pembayaran, silakan coba lagi.');
                                        btn.disabled = false;
                                    });
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
    </section>
</x-layouts.site>
