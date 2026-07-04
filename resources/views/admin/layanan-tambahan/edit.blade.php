<x-layouts.admin title="Edit Layanan Tambahan">
    <div class="max-w-xl rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">Edit Layanan Tambahan</h2>

        <form method="POST" action="{{ route('admin.layanan-tambahan.update', $layanan) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.layanan-tambahan._form')
        </form>
    </div>
</x-layouts.admin>
