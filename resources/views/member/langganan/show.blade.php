<x-layouts.site title="Status Paket Langganan - MYSOC Meulaboh">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between gap-3 bg-navy px-6 py-4 text-white">
                <div>
                    <h1 class="text-lg font-semibold">Paket Bulanan</h1>
                    <p class="mt-0.5 text-xs text-white/60">MYSOC &middot; My Soccer Bumi Teuku Umar</p>
                </div>
                <span @class([
                    'shrink-0 rounded-full px-3 py-1 text-xs font-semibold',
                    'bg-yellow-100 text-yellow-700' => $paket->status === 'pending_payment',
                    'bg-green-100 text-green-700' => $paket->status === 'active',
                    'bg-slate-200 text-slate-600' => in_array($paket->status, ['expired', 'cancelled', 'failed']),
                ])>
                    {{ \App\Support\StatusLabel::label($paket->status) }}
                </span>
            </div>

            <div class="p-6">
            <div class="space-y-1.5 text-sm text-slate-600">
                <div class="text-base font-semibold text-slate-800">{{ $paket->lapangan->nama }}</div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    {{ $paket->jumlah_pertemuan }}x pertemuan, jam {{ substr($paket->jam_mulai, 0, 5) }} sampai {{ substr($paket->jam_selesai, 0, 5) }}
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ $paket->periode_mulai->translatedFormat('d M Y') }} sampai {{ $paket->periode_selesai->translatedFormat('d M Y') }}
                </div>
            </div>

            <div class="mt-4 space-y-1 rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                <div class="flex justify-between">
                    <span>Lapangan ({{ $paket->jumlah_pertemuan }}x &times; Rp{{ number_format($paket->hargaSlotPerPertemuan(), 0, ',', '.') }})</span>
                    <span>Rp{{ number_format($paket->hargaSlotPerPertemuan() * $paket->jumlah_pertemuan, 0, ',', '.') }}</span>
                </div>
                @foreach ($paket->rincianAddon() as $addon)
                    <div class="flex justify-between">
                        <span>{{ $addon->nama }} ({{ $paket->jumlah_pertemuan }}x)</span>
                        <span>Rp{{ number_format($addon->pivot->harga, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between border-t border-slate-200 pt-1 font-semibold text-slate-800">
                    <span>Total Tagihan</span>
                    <span>Rp{{ number_format($paket->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            @if ($paket->status === 'active')
                @php
                    $persenBayar = (float) $paket->total_harga > 0
                        ? min(100, (int) round($paket->totalDibayar() / $paket->total_harga * 100))
                        : 100;
                @endphp
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Sudah dibayar Rp{{ number_format($paket->totalDibayar(), 0, ',', '.') }}</span>
                        <span>{{ $persenBayar }}%</span>
                    </div>
                    <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-2 rounded-full bg-gold" style="width: {{ $persenBayar }}%"></div>
                    </div>
                    @if ($paket->sisaTagihan() > 0)
                        <div class="mt-1.5 text-xs font-semibold text-red-600">
                            Sisa tagihan Rp{{ number_format($paket->sisaTagihan(), 0, ',', '.') }}
                        </div>
                    @endif
                </div>
            @endif

            <div @class([
                'mt-4 rounded-lg border p-3 text-xs leading-relaxed',
                'border-yellow-200 bg-yellow-50 text-yellow-800' => $paket->status === 'pending_payment',
                'border-green-200 bg-green-50 text-green-800' => $paket->status === 'active',
                'border-slate-100 bg-slate-50 text-slate-600' => ! in_array($paket->status, ['pending_payment', 'active']),
            ])>
                @if ($paket->status === 'pending_payment')
                    Selesaikan DP minimal Rp{{ number_format($paket->dpMinimum(), 0, ',', '.') }} dalam 15 menit supaya semua jadwal di paket ini terkunci untuk Anda. Kalau tidak, seluruh slot otomatis dilepas kembali.
                @elseif ($paket->status === 'active' && $paket->sisaTagihan() > 0)
                    Paket Anda sudah aktif, tidak ada batas waktu untuk pelunasan. Sisa tagihan bisa dicicil bertahap (kelipatan 25%) atau dilunasi sekaligus, kapan saja. Pembayaran bisa dilakukan online lewat tombol di bawah, atau tunai langsung di lapangan saat Anda datang bermain.
                @elseif ($paket->status === 'active' && $paket->sisaTagihan() <= 0)
                    Pembayaran sudah lunas. Sampai jumpa di lapangan tiap minggunya!
                @elseif ($paket->status === 'expired')
                    Waktu pembayaran sudah habis dan semua slot di paket ini sudah dilepas kembali. Silakan buat paket baru lewat halaman langganan.
                @elseif (in_array($paket->status, ['cancelled', 'failed']))
                    Paket ini sudah dibatalkan.
                @endif
            </div>

            <div class="mt-4">
                <div class="text-xs font-medium text-slate-700">Jadwal Pertemuan</div>
                <ul class="mt-2 space-y-1 text-sm text-slate-600">
                    @foreach ($tanggalPertemuan as $item)
                        <li class="flex items-center justify-between">
                            <span>{{ $item->tanggal_main->translatedFormat('l, d F Y') }}</span>
                            <span class="text-xs text-slate-400">{{ \App\Support\StatusLabel::label($item->status) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if (! empty($opsiBayar))
                <div class="mt-4 space-y-2">
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

                            fetch(@json(route('member.langganan.opsi-bayar', $paket)), {
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
