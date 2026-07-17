@props(['title' => 'Soccer Bumi Teuku Umar'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans antialiased">
    <header class="bg-navy text-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 md:px-6">
            <a href="{{ route('home') }}" class="text-lg font-bold tracking-wide">
                SOCCER <span class="text-sm font-normal text-white/60">Bumi Teuku Umar</span>
            </a>

            <nav class="hidden items-center gap-6 text-sm md:flex">
                <a href="{{ route('home') }}" class="hover:text-gold">Beranda</a>
                <a href="{{ route('home') }}#jadwal" class="hover:text-gold">Jadwal</a>
                <a href="{{ route('home') }}#tentang-kami" class="hover:text-gold">Tentang Kami</a>
                <a href="{{ route('home') }}#faq" class="hover:text-gold">FAQ</a>

                @guest
                    <a href="{{ route('guest.pemesanan.cek') }}" class="hover:text-gold">Cek Booking</a>
                    <a href="{{ route('login') }}" class="rounded-lg bg-gold px-4 py-2 font-semibold text-navy-dark hover:bg-gold-dark">
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

    <main>
        {{ $slot }}
    </main>
</body>
</html>
