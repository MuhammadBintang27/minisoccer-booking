<x-layouts.admin title="Tambah Layanan Tambahan">
    <div class="max-w-xl rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">Tambah Layanan Tambahan</h2>

        <form method="POST" action="{{ route('admin.layanan-tambahan.store') }}" class="mt-6">
            @csrf
            @include('admin.layanan-tambahan._form')
        </form>
    </div>
</x-layouts.admin>
