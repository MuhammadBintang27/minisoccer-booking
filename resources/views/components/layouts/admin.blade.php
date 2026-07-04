@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - Admin Soccer Bumi Teuku Umar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans antialiased">
    <div class="flex min-h-screen">
        <aside class="w-64 shrink-0 bg-navy text-white flex flex-col">
            <div class="px-6 py-6 text-lg font-bold tracking-wide border-b border-white/10">
                SOCCER <span class="block text-xs font-normal text-white/60">Bumi Teuku Umar</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-4 py-2 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.lapangan.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.lapangan.*') ? 'bg-white/10' : '' }}">
                    Data Lapangan
                </a>
                <a href="{{ route('admin.jadwal.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.jadwal.*') ? 'bg-white/10' : '' }}">
                    Data Jadwal
                </a>
                <a href="{{ route('admin.layanan-tambahan.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.layanan-tambahan.*') ? 'bg-white/10' : '' }}">
                    Layanan Tambahan
                </a>
                <a href="{{ route('admin.pemesanan.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.pemesanan.*') ? 'bg-white/10' : '' }}">
                    Data Pemesanan
                </a>
                <a href="{{ route('admin.member.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.member.*') ? 'bg-white/10' : '' }}">
                    Data Member
                </a>
                <a href="{{ route('admin.laporan.pendapatan') }}" class="block rounded-lg px-4 py-2 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.laporan.*') ? 'bg-white/10' : '' }}">
                    Laporan
                </a>
            </nav>

            <div class="border-t border-white/10 px-4 py-4">
                <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                <div class="text-xs text-white/60">Administrator</div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="flex items-center justify-between bg-white px-8 py-4 shadow-sm">
                <h1 class="text-xl font-semibold text-slate-800">{{ $title }}</h1>

                @isset($actions)
                    <div class="flex items-center gap-3">{{ $actions }}</div>
                @endisset
            </header>

            <main class="flex-1 p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>