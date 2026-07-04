<x-layouts.site title="Status Pemesanan - Soccer Bumi Teuku Umar">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <h1 class="text-lg font-semibold text-slate-800">Status Pemesanan</h1>
                <span @class([
                    'shrink-0 rounded-full px-3 py-1 text-xs font-semibold',
                    'bg-yellow-100 text-yellow-700' => $pemesanan->status === 'pending',
                    'bg-green-100 text-green-700' => in_array($pemesanan->status, ['confirmed', 'completed']),
                    'bg-slate-200 text-slate-600' => in_array($pemesanan->status, ['expired', 'cancelled']),
                ])>
                    {{ \App\Support\StatusLabel::label($pemesanan->status) }}
                </span>
            </div>

            <div class="mt-4 space-y-1 text-sm text-slate-600">
                <div class="text-base font-semibold text-slate-800">{{ $pemesanan->lapangan->nama }}</div>
                <div>{{ $pemesanan->tanggal_main->translatedFormat('l, d F Y') }}</div>
                <div>Jam {{ substr($pemesanan->jam_mulai, 0, 5) }} sampai {{ substr($pemesanan->jam_selesai, 0, 5) }}</div>
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

            <div class="mt-4 rounded-lg border border-slate-100 bg-slate-50 p-3 text-xs leading-relaxed text-slate-600">
                @if ($pemesanan->status === 'pending')
                    Selesaikan DP minimal Rp{{ number_format($pemesanan->dpMinimum(), 0, ',', '.') }} dalam 15 menit supaya jadwal ini terkunci untuk Anda. Kalau tidak, slot ini otomatis dilepas kembali dan bisa dipesan orang lain.
                @elseif ($pemesanan->status === 'confirmed' && $pemesanan->sisaTagihan() > 0)
                    Booking Anda sudah aman, tidak ada batas waktu untuk pelunasan. Sisa tagihan bisa dibayar kapan saja lewat transfer atau e-wallet secara online di bawah ini, atau tunai langsung di lapangan saat Anda datang bermain.
                @elseif (in_array($pemesanan->status, ['confirmed', 'completed']) && $pemesanan->sisaTagihan() <= 0)
                    Pembayaran sudah lunas. Sampai jumpa di lapangan!
                @elseif ($pemesanan->status === 'expired')
                    Waktu pembayaran sudah habis dan slot ini sudah dilepas kembali. Silakan lakukan pemesanan baru lewat halaman jadwal.
                @elseif ($pemesanan->status === 'cancelled')
                    Pemesanan ini sudah dibatalkan.
                @endif
            </div>

            @if (! empty($opsiBayar))
                <div class="mt-3 space-y-2">
                    @foreach ($opsiBayar as $opsi)
                        <button type="button" data-jenis="{{ $opsi['jenis'] }}" class="btn-opsi-bayar w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark disabled:opacity-50">
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
    </section>
</x-layouts.site>
