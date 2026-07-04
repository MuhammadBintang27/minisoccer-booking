<x-layouts.site title="Status Pemesanan - Soccer Bumi Teuku Umar">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h1 class="text-lg font-semibold text-slate-800">Status Pemesanan</h1>

            <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                <div><span class="font-medium text-slate-800">{{ $pemesanan->lapangan->nama }}</span></div>
                <div>{{ $pemesanan->tanggal_main->translatedFormat('l, d F Y') }}</div>
                <div>{{ substr($pemesanan->jam_mulai, 0, 5) }}-{{ substr($pemesanan->jam_selesai, 0, 5) }}</div>
                <div class="mt-1">Slot: Rp{{ number_format($pemesanan->harga, 0, ',', '.') }}</div>
                @foreach ($pemesanan->layananTambahan as $addon)
                    <div>{{ $addon->nama }}: Rp{{ number_format($addon->pivot->harga, 0, ',', '.') }}</div>
                @endforeach
                <div class="mt-1 font-semibold text-slate-800">Total: Rp{{ number_format($pemesanan->totalHarga(), 0, ',', '.') }}</div>
                <div class="mt-2">
                    <span @class([
                        'rounded-full px-3 py-1 text-xs font-semibold',
                        'bg-yellow-100 text-yellow-700' => $pemesanan->status === 'pending',
                        'bg-green-100 text-green-700' => in_array($pemesanan->status, ['confirmed', 'completed']),
                        'bg-slate-200 text-slate-600' => in_array($pemesanan->status, ['expired', 'cancelled']),
                    ])>
                        {{ ucfirst($pemesanan->status) }}
                    </span>
                </div>
            </div>

            @if ($pemesanan->status === 'pending' && $snapToken)
                <p class="mt-4 text-xs text-slate-500">
                    Selesaikan pembayaran dalam 15 menit agar slot tidak dilepas kembali.
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
            @elseif ($pemesanan->status === 'confirmed')
                <p class="mt-4 text-sm text-green-700">Pembayaran berhasil, pemesanan kamu sudah terkonfirmasi.</p>
            @elseif ($pemesanan->status === 'expired')
                <p class="mt-4 text-sm text-slate-600">Waktu pembayaran sudah habis dan slot ini sudah dilepas kembali.</p>
            @endif
        </div>
    </section>
</x-layouts.site>
