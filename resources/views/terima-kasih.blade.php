<x-layouts.site title="Terima Kasih - Soccer Bumi Teuku Umar">
    <section class="mx-auto flex min-h-[60vh] max-w-md flex-col items-center justify-center px-4 py-16 text-center md:px-6">
        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-3xl text-green-600">
            &#10003;
        </span>

        <h1 class="mt-6 text-xl font-semibold text-slate-800">Terima Kasih!</h1>
        <p class="mt-2 text-sm text-slate-500">
            Pembayaran kamu sedang diproses. Status pemesanan akan otomatis diperbarui begitu Midtrans mengonfirmasi pembayaran.
        </p>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            @if ($linkStatus)
                <a href="{{ $linkStatus }}" class="rounded-lg bg-gold px-6 py-3 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Lihat Status Pemesanan
                </a>
            @endif
            <a href="{{ route('home') }}" class="rounded-lg border border-navy px-6 py-3 text-sm font-semibold text-navy hover:bg-navy hover:text-white">
                Kembali ke Beranda
            </a>
        </div>

        <p class="mt-6 text-xs text-slate-400">
            Lupa simpan link status? Cek lagi lewat <a href="{{ route('guest.pemesanan.cek') }}" class="underline hover:text-slate-600">Cek Booking Saya</a> pakai no. HP kamu.
        </p>
    </section>
</x-layouts.site>
