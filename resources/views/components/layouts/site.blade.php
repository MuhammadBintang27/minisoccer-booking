@props(['title' => 'MYSOC - My Soccer Bumi Teuku Umar'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MYSOC (My Soccer Bumi Teuku Umar) - lapangan mini soccer rumput sintetis standar FIFA Quality Pro di Meulaboh, Aceh Barat. Booking online 24 jam.">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-100 font-sans antialiased">
    <header class="sticky top-0 z-40 bg-navy text-white shadow-lg shadow-navy/20">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3.5 md:px-6">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo.webp') }}" alt="MYSOC - My Soccer Bumi Teuku Umar" class="h-9 w-auto md:h-10">
            </a>

            <nav class="hidden items-center gap-6 text-sm md:flex">
                <a href="{{ route('home') }}" class="transition-colors hover:text-gold">Beranda</a>
                <a href="{{ route('home') }}#jadwal" class="transition-colors hover:text-gold">Jadwal</a>
                <a href="{{ route('home') }}#tentang-kami" class="transition-colors hover:text-gold">Tentang Kami</a>
                <a href="{{ route('home') }}#faq" class="transition-colors hover:text-gold">FAQ</a>

                @guest
                    <a href="{{ route('guest.pemesanan.cek') }}" class="transition-colors hover:text-gold">Cek Booking</a>
                    <a href="{{ route('login') }}" class="rounded-lg bg-gold px-4 py-2 font-semibold text-navy-dark transition-colors hover:bg-gold-dark">
                        Login
                    </a>
                @else
                    <details class="relative">
                        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-3 py-2 hover:bg-white/10">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold text-sm font-semibold text-navy-dark">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span>{{ auth()->user()->name }}</span>
                        </summary>

                        <div class="absolute right-0 z-10 mt-2 w-56 rounded-lg bg-white py-2 text-slate-700 shadow-lg">
                            <div class="border-b border-slate-100 px-4 py-2 text-xs text-slate-500">
                                {{ auth()->user()->isAdmin() ? 'Administrator' : 'Member' }}
                            </div>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('member.jadwal') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">
                                    Jadwal Saya
                                </a>
                                <a href="{{ route('member.riwayat') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">
                                    Riwayat Pemesanan
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-slate-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </details>
                @endguest
            </nav>

            <details class="relative md:hidden">
                <summary class="flex cursor-pointer list-none items-center rounded-lg p-2 hover:bg-white/10">
                    @guest
                        <span class="text-xl leading-none">&#9776;</span>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold text-sm font-semibold text-navy-dark">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endguest
                </summary>
                <div class="absolute right-0 z-10 mt-2 w-56 space-y-1 rounded-lg bg-navy-dark p-3 text-sm shadow-lg">
                    @auth
                        <div class="px-3 py-1 text-xs text-white/60">{{ auth()->user()->name }}</div>
                    @endauth
                    <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Beranda</a>
                    <a href="{{ route('home') }}#jadwal" class="block rounded-lg px-3 py-2 hover:bg-white/10">Jadwal</a>
                    <a href="{{ route('home') }}#tentang-kami" class="block rounded-lg px-3 py-2 hover:bg-white/10">Tentang Kami</a>
                    <a href="{{ route('home') }}#faq" class="block rounded-lg px-3 py-2 hover:bg-white/10">FAQ</a>

                    @guest
                        <a href="{{ route('guest.pemesanan.cek') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Cek Booking</a>
                        <a href="{{ route('login') }}" class="block rounded-lg bg-gold px-3 py-2 font-semibold text-navy-dark">Login</a>
                    @else
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Dashboard</a>
                        @else
                            <a href="{{ route('member.jadwal') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Jadwal Saya</a>
                            <a href="{{ route('member.riwayat') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Riwayat Pemesanan</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left hover:bg-white/10">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </details>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-navy-dark text-white">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-8 px-6 py-12 sm:grid-cols-3">
            <div>
                <img src="{{ asset('images/logo.webp') }}" alt="MYSOC - My Soccer Bumi Teuku Umar" class="h-8 w-auto">
                <p class="mt-3 text-sm leading-relaxed text-white/60">
                    My Soccer Bumi Teuku Umar — lapangan mini soccer rumput sintetis standar FIFA Quality Pro di Meulaboh, Aceh Barat.
                </p>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gold">Kunjungi Kami</div>
                <ul class="mt-3 space-y-2 text-sm text-white/70">
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        Meulaboh, Kabupaten Aceh Barat, Aceh
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Buka setiap hari, 06.00-23.00 WIB
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0-1.6 1.123-2.994 2.707-3.227 1.068-.157 2.148-.279 3.238-.364.466-.037.893-.281 1.153-.671L12 5.432l2.652 3.066c.26.39.687.634 1.153.67 1.09.086 2.17.208 3.238.365 1.584.233 2.707 1.626 2.707 3.228v6.018c0 .906-.734 1.64-1.64 1.64H3.89a1.64 1.64 0 01-1.64-1.64V12.76z" />
                        </svg>
                        Cafe MYCO. &amp; MY KOPI SARENG di area lapangan
                    </li>
                </ul>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gold">Menu</div>
                <ul class="mt-3 space-y-2 text-sm text-white/70">
                    <li><a href="{{ route('home') }}#jadwal" class="transition-colors hover:text-gold">Jadwal &amp; Booking</a></li>
                    <li><a href="{{ route('home') }}#tentang-kami" class="transition-colors hover:text-gold">Tentang Kami</a></li>
                    <li><a href="{{ route('home') }}#faq" class="transition-colors hover:text-gold">FAQ</a></li>
                    <li><a href="{{ route('guest.pemesanan.cek') }}" class="transition-colors hover:text-gold">Cek Booking Saya</a></li>
                    <li><a href="{{ route('register') }}" class="transition-colors hover:text-gold">Daftar Member</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-5 text-center text-xs text-white/40">
            &copy; {{ now()->year }} MYSOC &middot; My Soccer Bumi Teuku Umar, Meulaboh. Semua hak dilindungi.
        </div>
    </footer>
</body>
</html>
