<x-layouts.site title="Cek Booking Saya - Soccer Bumi Teuku Umar">
    <section class="mx-auto max-w-md px-4 py-10 md:px-6">
        <h1 class="text-lg font-semibold text-slate-800">Cek Booking Saya</h1>
        <p class="mt-1 text-sm text-slate-500">Masukkan no. HP yang kamu pakai waktu booking untuk lihat status pemesanan kamu.</p>

        <form method="GET" action="{{ route('guest.pemesanan.cek') }}" class="mt-4 flex items-end gap-3 rounded-xl bg-white p-4 shadow-sm">
            <div class="flex-1">
                <label for="no_hp" class="block text-xs font-medium text-slate-700">No. HP</label>
                <input id="no_hp" type="text" name="no_hp" value="{{ $noHp }}" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <button type="submit" class="rounded-lg bg-gold px-5 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                Cari
            </button>
        </form>

        @if ($noHp)
            <div class="mt-6">
                @if ($hasil->isEmpty())
                    <div class="rounded-xl bg-white p-6 text-center text-sm text-slate-500 shadow-sm">
                        Tidak ada booking yang ditemukan untuk no. HP ini.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($hasil as $item)
                            @php $pemesanan = $item['pemesanan']; @endphp
                            <a href="{{ $item['url'] }}" class="block rounded-xl border border-transparent bg-white p-4 shadow-sm hover:border-gold">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-slate-800">{{ $pemesanan->lapangan->nama }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ $pemesanan->tanggal_main->translatedFormat('d M Y') }} &middot;
                                            {{ substr($pemesanan->jam_mulai, 0, 5) }}-{{ substr($pemesanan->jam_selesai, 0, 5) }}
                                        </div>
                                    </div>
                                    <span @class([
                                        'rounded-full px-3 py-1 text-xs font-semibold',
                                        'bg-yellow-100 text-yellow-700' => $pemesanan->status === 'pending',
                                        'bg-green-100 text-green-700' => in_array($pemesanan->status, ['confirmed', 'completed']),
                                        'bg-slate-200 text-slate-600' => in_array($pemesanan->status, ['expired', 'cancelled']),
                                    ])>
                                        {{ \App\Support\StatusLabel::label($pemesanan->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </section>
</x-layouts.site>
