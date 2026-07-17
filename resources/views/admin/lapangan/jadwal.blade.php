<x-layouts.admin title="Ubah Jadwal - {{ $lapangan->nama }}">
    <a href="{{ route('admin.lapangan.index') }}" class="text-sm text-slate-500 hover:underline">&larr; Data Lapangan</a>

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

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
        @foreach ($jadwal as $slot)
            <div class="rounded-xl border p-4 shadow-sm transition-shadow hover:shadow-md {{ $slot->is_closed ? 'bg-slate-100 border-slate-200' : 'bg-white border-slate-200' }}">
                <div class="text-sm font-semibold text-slate-800">
                    {{ substr($slot->jam_mulai, 0, 5) }}-{{ substr($slot->jam_selesai, 0, 5) }}
                </div>

                @if ($slot->is_closed)
                    <div class="mt-1 text-xs font-semibold text-slate-500">Tutup</div>

                    <form method="POST" action="{{ route('admin.lapangan.jadwal.update', [$lapangan, $slot]) }}" class="mt-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="jam_mulai" value="{{ substr($slot->jam_mulai, 0, 5) }}">
                        <input type="hidden" name="jam_selesai" value="{{ substr($slot->jam_selesai, 0, 5) }}">
                        <input type="hidden" name="harga_weekday_member" value="{{ $slot->harga_weekday_member }}">
                        <input type="hidden" name="harga_weekend_member" value="{{ $slot->harga_weekend_member }}">
                        <input type="hidden" name="harga_weekday_nonmember" value="{{ $slot->harga_weekday_nonmember }}">
                        <input type="hidden" name="harga_weekend_nonmember" value="{{ $slot->harga_weekend_nonmember }}">
                        <button type="submit" class="w-full rounded-lg bg-gold px-3 py-1.5 text-xs font-semibold text-navy-dark hover:bg-gold-dark">
                            Buka Kembali
                        </button>
                    </form>
                @else
                    <div class="mt-2 text-xs text-slate-600">
                        <div class="font-semibold text-slate-700">Member</div>
                        <div>Weekday Rp{{ number_format($slot->harga_weekday_member, 0, ',', '.') }}</div>
                        <div>Weekend Rp{{ number_format($slot->harga_weekend_member, 0, ',', '.') }}</div>
                    </div>
                    <div class="mt-2 text-xs text-slate-600">
                        <div class="font-semibold text-slate-700">Non-Member</div>
                        <div>Weekday Rp{{ number_format($slot->harga_weekday_nonmember, 0, ',', '.') }}</div>
                        <div>Weekend Rp{{ number_format($slot->harga_weekend_nonmember, 0, ',', '.') }}</div>
                    </div>
                    <div class="mt-1 text-xs font-semibold text-blue-600">Tersedia</div>
                @endif

                <details class="group mt-3 border-t border-slate-200 pt-3">
                    <summary class="cursor-pointer list-none text-xs font-semibold text-navy hover:underline">
                        Edit Lengkap
                    </summary>

                    <div class="mt-3 space-y-2">
                        <form method="POST" action="{{ route('admin.lapangan.jadwal.update', [$lapangan, $slot]) }}" class="space-y-2">
                            @csrf
                            @method('PUT')
                            <div class="flex gap-2">
                                <input type="text" inputmode="numeric" name="jam_mulai" value="{{ substr($slot->jam_mulai, 0, 5) }}" placeholder="HH:MM" pattern="([01][0-9]|2[0-3]):[0-5][0-9]" maxlength="5" required class="input-jam w-1/2 rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                <input type="text" inputmode="numeric" name="jam_selesai" value="{{ substr($slot->jam_selesai, 0, 5) }}" placeholder="HH:MM (24:00 = tengah malam)" pattern="([01][0-9]|2[0-3]):[0-5][0-9]|24:00" maxlength="5" required class="input-jam w-1/2 rounded-lg border border-slate-300 px-2 py-1 text-xs">
                            </div>
                            <div class="text-xs font-semibold text-slate-500">Harga Member</div>
                            <div>
                                <label class="text-xs text-slate-500">Weekday</label>
                                <input type="number" name="harga_weekday_member" value="{{ $slot->harga_weekday_member }}" step="1000" min="0" required class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Weekend</label>
                                <input type="number" name="harga_weekend_member" value="{{ $slot->harga_weekend_member }}" step="1000" min="0" required class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs">
                            </div>
                            <div class="text-xs font-semibold text-slate-500">Harga Non-Member</div>
                            <div>
                                <label class="text-xs text-slate-500">Weekday</label>
                                <input type="number" name="harga_weekday_nonmember" value="{{ $slot->harga_weekday_nonmember }}" step="1000" min="0" required class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Weekend</label>
                                <input type="number" name="harga_weekend_nonmember" value="{{ $slot->harga_weekend_nonmember }}" step="1000" min="0" required class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs">
                            </div>
                            <label class="flex items-center gap-2 text-xs text-slate-600">
                                <input type="checkbox" name="is_closed" value="1" {{ $slot->is_closed ? 'checked' : '' }} class="rounded border-slate-300">
                                Tutup (tidak bisa dipesan)
                            </label>
                            <button type="submit" class="w-full rounded-lg bg-gold px-3 py-1.5 text-xs font-semibold text-navy-dark hover:bg-gold-dark">
                                Simpan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.lapangan.jadwal.destroy', [$lapangan, $slot]) }}" onsubmit="return confirm('Hapus slot ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">
                                Hapus Slot
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        @endforeach
    </div>

    <div class="mt-8 max-w-xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-800">Tambah Slot Jadwal</h3>
        <form method="POST" action="{{ route('admin.lapangan.jadwal.store', $lapangan) }}" class="mt-4 space-y-3">
            @csrf
            <div class="flex gap-3">
                <div class="w-1/2">
                    <label class="block text-xs font-medium text-slate-700">Jam Mulai</label>
                    <input type="text" inputmode="numeric" name="jam_mulai" placeholder="HH:MM" pattern="([01][0-9]|2[0-3]):[0-5][0-9]" maxlength="5" required class="input-jam mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="w-1/2">
                    <label class="block text-xs font-medium text-slate-700">Jam Selesai</label>
                    <input type="text" inputmode="numeric" name="jam_selesai" placeholder="HH:MM (24:00 = tengah malam)" pattern="([01][0-9]|2[0-3]):[0-5][0-9]|24:00" maxlength="5" required class="input-jam mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div class="text-xs font-semibold text-slate-500">Harga Member</div>
            <div class="flex gap-3">
                <div class="w-1/2">
                    <label class="block text-xs font-medium text-slate-700">Weekday</label>
                    <input type="number" name="harga_weekday_member" step="1000" min="0" required placeholder="300000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="w-1/2">
                    <label class="block text-xs font-medium text-slate-700">Weekend</label>
                    <input type="number" name="harga_weekend_member" step="1000" min="0" required placeholder="400000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div class="text-xs font-semibold text-slate-500">Harga Non-Member</div>
            <div class="flex gap-3">
                <div class="w-1/2">
                    <label class="block text-xs font-medium text-slate-700">Weekday</label>
                    <input type="number" name="harga_weekday_nonmember" step="1000" min="0" required placeholder="350000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="w-1/2">
                    <label class="block text-xs font-medium text-slate-700">Weekend</label>
                    <input type="number" name="harga_weekend_nonmember" step="1000" min="0" required placeholder="450000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="is_closed" value="1" class="rounded border-slate-300">
                Tutup (tidak bisa dipesan)
            </label>
            <button type="submit" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                Tambah Slot
            </button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.input-jam').forEach(function (input) {
            input.addEventListener('input', function () {
                var digit = input.value.replace(/\D/g, '').slice(0, 4);
                input.value = digit.length > 2 ? digit.slice(0, 2) + ':' + digit.slice(2) : digit;
            });
        });
    </script>
</x-layouts.admin>
