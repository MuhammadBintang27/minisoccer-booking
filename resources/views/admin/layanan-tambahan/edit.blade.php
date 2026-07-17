<x-layouts.admin title="Edit Layanan Tambahan">
    <div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.layanan-tambahan.update', $layanan) }}">
            @csrf
            @method('PUT')
            @include('admin.layanan-tambahan._form')
        </form>
    </div>
</x-layouts.admin>
