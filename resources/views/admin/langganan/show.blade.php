<x-layouts.admin title="Detail Paket Langganan">
    <a href="{{ route('admin.pemesanan.index') }}" class="text-sm text-slate-500 hover:underline">&larr; Data Pemesanan</a>

    @if (session('status'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-slate-800">{{ $paket->lapangan->nama }} (Paket Bulanan)</h2>
            <div class="mt-2 text-sm text-slate-600">
                <div>Member: {{ $paket->member->user->name }} ({{ $paket->member->kode_member }})</div>
                <div>{{ substr($paket->jam_mulai, 0, 5) }}-{{ substr($paket->jam_selesai, 0, 5) }}, {{ $paket->jumlah_pertemuan }}x pertemuan</div>
                <div>{{ $paket->periode_mulai->translatedFormat('d M Y') }} sampai {{ $paket->periode_selesai->translatedFormat('d M Y') }}</div>
            </div>

            <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600">
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
                <div class="mt-2 flex justify-between border-t border-slate-100 pt-2 font-semibold text-slate-800">
                    <span>Total Paket</span><span>Rp{{ number_format($paket->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <h3 class="mt-6 text-sm font-semibold text-slate-700">Jadwal Pertemuan</h3>
            <ul class="mt-2 space-y-1 text-sm text-slate-600">
                @foreach ($tanggalPertemuan as $item)
                    <li class="flex items-center justify-between">
                        <span>{{ $item->tanggal_main->translatedFormat('l, d F Y') }}</span>
                        <span class="text-xs text-slate-400">{{ \App\Support\StatusLabel::label($item->status) }}</span>
                    </li>
                @endforeach
            </ul>

            <h3 class="mt-6 text-sm font-semibold text-slate-700">Riwayat Pembayaran</h3>
            <div class="mt-2 overflow-x-auto rounded-lg border border-slate-100">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-3 py-2 font-medium">Tanggal</th>
                            <th class="px-3 py-2 font-medium">Channel</th>
                            <th class="px-3 py-2 font-medium">Jumlah</th>
                            <th class="px-3 py-2 font-medium">Status</th>
                            <th class="px-3 py-2 font-medium">Dikonfirmasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($paket->pembayaran as $bayar)
                            <tr>
                                <td class="px-3 py-2 text-slate-600">{{ $bayar->created_at->translatedFormat('d M Y H:i') }}</td>
                                <td class="px-3 py-2 text-slate-600">{{ $bayar->channel === 'cash' ? 'Tunai' : 'Midtrans' }}</td>
                                <td class="px-3 py-2 text-slate-600">Rp{{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-slate-600">{{ \App\Support\StatusLabel::label($bayar->status) }}</td>
                                <td class="px-3 py-2 text-slate-600">{{ $bayar->dikonfirmasiOleh?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-slate-400">Belum ada pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="text-xs text-slate-500">Status Paket</div>
                <div class="mt-1 font-semibold text-slate-800">{{ \App\Support\StatusLabel::label($paket->status) }}</div>

                <div class="mt-3 text-xs text-slate-500">Sudah Dibayar</div>
                <div class="font-semibold text-slate-800">Rp{{ number_format($paket->totalDibayar(), 0, ',', '.') }}</div>

                <div class="mt-3 text-xs text-slate-500">Sisa Tagihan</div>
                <div class="font-semibold {{ $paket->sisaTagihan() > 0 ? 'text-red-600' : 'text-green-600' }}">
                    Rp{{ number_format($paket->sisaTagihan(), 0, ',', '.') }}
                </div>
            </div>

            @if ($paket->sisaTagihan() > 0)
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-700">Tandai Lunas Cash</h3>
                    <form method="POST" action="{{ route('admin.langganan.bayar-cash', $paket) }}" class="mt-3 space-y-2">
                        @csrf
                        <input type="number" name="jumlah" step="1000" min="0" max="{{ $paket->sisaTagihan() }}"
                            value="{{ $paket->sisaTagihan() }}" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <button type="submit" class="w-full rounded-lg bg-navy px-4 py-2 text-sm font-semibold text-white hover:bg-navy-dark">
                            Tandai Lunas (Cash)
                        </button>
                    </form>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-700">Buatkan Pembayaran Online</h3>
                    <div class="mt-3 space-y-2">
                        <input type="number" id="jumlah-online" step="1000" min="0" max="{{ $paket->sisaTagihan() }}"
                            value="{{ $paket->sisaTagihan() }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <button type="button" id="btn-kasir-bayar" class="w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark disabled:opacity-50">
                            Buat &amp; Buka Pembayaran
                        </button>
                    </div>

                    <script src="https://app.{{ config('services.midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
                        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
                    <script>
                        document.getElementById('btn-kasir-bayar').addEventListener('click', function () {
                            var btn = this;
                            var jumlah = document.getElementById('jumlah-online').value;
                            btn.disabled = true;

                            fetch(@json(route('admin.langganan.buat-transaksi-online', $paket)), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': @json(csrf_token()),
                                },
                                body: JSON.stringify({ jumlah: jumlah }),
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
                                    alert('Gagal membuat transaksi, silakan coba lagi.');
                                    btn.disabled = false;
                                });
                        });
                    </script>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
