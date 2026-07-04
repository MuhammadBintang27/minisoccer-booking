<x-layouts.site title="Login - Soccer Bumi Teuku Umar">
    <section class="mx-auto max-w-md px-6 py-16">
        <div class="rounded-xl bg-white p-8 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800">Login Member</h1>

            @if ($errors->any())
                <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Ingat saya
                </label>

                <button type="submit" class="w-full rounded-lg bg-gold px-4 py-2 font-semibold text-navy-dark hover:bg-gold-dark">
                    Login
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-navy hover:underline">Daftar sebagai member</a>
            </p>
        </div>
    </section>
</x-layouts.site>
