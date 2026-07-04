<x-layouts.site title="Status Paket Langganan">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h1 class="text-lg font-semibold text-slate-800">Paket Bulanan</h1>

            <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                <div><span class="font-medium text-slate-800">{{ $paket->lapangan->nama }}</span></div>
                <div>{{ substr($paket->jam_mulai, 0, 5) }}-{{ substr($paket->jam_selesai, 0, 5) }}, {{ $paket->jumlah_pertemuan }}x pertemuan</div>
                <div>{{ $paket->periode_mulai->translatedFormat('d M Y') }} &ndash; {{ $paket->periode_selesai->translatedFormat('d M Y') }}</div>
                @if ($tanggalPertemuan->isNotEmpty() && $tanggalPertemuan->first()->layananTambahan->isNotEmpty())
                    <div class="mt-1 text-xs text-slate-500">
                        Add-on per pertemuan: {{ $tanggalPertemuan->first()->layananTambahan->pluck('nama')->join(', ') }}
                    </div>
                @endif
                <div class="mt-1 font-semibold text-slate-800">Total: Rp{{ number_format($paket->total_harga, 0, ',', '.') }}</div>
                <div class="mt-2">
                    <span @class([
                        'rounded-full px-3 py-1 text-xs font-semibold',
                        'bg-yellow-100 text-yellow-700' => $paket->status === 'pending_payment',
                        'bg-green-100 text-green-700' => $paket->status === 'active',
                        'bg-slate-200 text-slate-600' => in_array($paket->status, ['expired', 'cancelled', 'failed']),
                    ])>
                        {{ ucfirst(str_replace('_', ' ', $paket->status)) }}
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <div class="text-xs font-medium text-slate-700">Jadwal Pertemuan</div>
                <ul class="mt-2 space-y-1 text-sm text-slate-600">
                    @foreach ($tanggalPertemuan as $item)
                        <li class="flex items-center justify-between">
                            <span>{{ $item->tanggal_main->translatedFormat('l, d F Y') }}</span>
                            <span class="text-xs text-slate-400">{{ ucfirst($item->status) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($paket->status === 'pending_payment' && $snapToken)
                <p class="mt-4 text-xs text-slate-500">
                    Selesaikan pembayaran dalam 15 menit agar semua slot tidak dilepas kembali.
                </p>
                <button id="btn-bayar" class="mt-3 w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Bayar Sekarang
                </button>

                <script src="https://app.{{ config('services.midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
                    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
                <script>
                    document.getElementById('btn-bayar').addEventListener('click', function () {
                        window.snap.pay(@json($snapToken), {
                            onSuccess: function () { window.location.reload(); },
                            onPending: function () { window.location.reload(); },
                            onError: function () { alert('Pembayaran gagal, silakan coba lagi.'); },
                        });
                    });
                </script>
            @elseif ($paket->status === 'active')
                <p class="mt-4 text-sm text-green-700">Pembayaran berhasil, paket kamu sudah aktif.</p>
            @elseif ($paket->status === 'expired')
                <p class="mt-4 text-sm text-slate-600">Waktu pembayaran sudah habis dan semua slot di paket ini sudah dilepas kembali.</p>
            @endif
        </div>
    </section>
</x-layouts.site>
