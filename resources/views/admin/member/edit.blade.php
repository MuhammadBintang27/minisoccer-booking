<x-layouts.admin title="Edit Member">
    <div class="max-w-xl rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">Edit Member ({{ $member->kode_member }})</h2>

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.member.update', $member) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name', $member->user->name) }}" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $member->user->email) }}" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700">No. HP</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $member->user->phone) }}" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-navy focus:outline-none">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                <select id="status" name="status" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="active" {{ old('status', $member->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $member->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-gold px-4 py-2 text-sm font-semibold text-navy-dark hover:bg-gold-dark">
                    Simpan
                </button>
                <a href="{{ route('admin.member.index') }}" class="text-sm text-slate-600 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
