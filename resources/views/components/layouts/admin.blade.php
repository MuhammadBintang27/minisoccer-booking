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
        <aside class="sticky top-0 flex h-screen w-64 shrink-0 flex-col overflow-y-auto bg-navy text-white">
            <div class="px-6 py-6 text-lg font-bold tracking-wide border-b border-white/10">
                SOCCER <span class="block text-xs font-normal text-white/60">Bumi Teuku Umar</span>
            </div>

            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Dashboard'],
                    ['route' => 'admin.lapangan.index', 'pattern' => 'admin.lapangan.*', 'label' => 'Data Lapangan'],
                    ['route' => 'admin.jadwal.index', 'pattern' => 'admin.jadwal.*', 'label' => 'Data Jadwal'],
                    ['route' => 'admin.layanan-tambahan.index', 'pattern' => 'admin.layanan-tambahan.*', 'label' => 'Layanan Tambahan'],
                    ['route' => 'admin.pemesanan.index', 'pattern' => 'admin.pemesanan.*', 'label' => 'Data Pemesanan'],
                    ['route' => 'admin.member.index', 'pattern' => 'admin.member.*', 'label' => 'Data Member'],
                    ['route' => 'admin.laporan.pendapatan', 'pattern' => 'admin.laporan.*', 'label' => 'Laporan'],
                ];
            @endphp

            <nav class="flex-1 space-y-1 px-4 py-6">
                @foreach ($navItems as $item)
                    @php $active = request()->routeIs($item['pattern']); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="block rounded-lg border-l-2 px-4 py-2 text-sm font-medium transition-colors {{ $active ? 'border-gold bg-white/10 text-white' : 'border-transparent text-white/70 hover:bg-white/5 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
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