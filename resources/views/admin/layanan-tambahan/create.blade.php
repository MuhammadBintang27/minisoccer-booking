<x-layouts.admin title="Tambah Layanan Tambahan">
    <div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.layanan-tambahan.store') }}">
            @csrf
            @include('admin.layanan-tambahan._form')
        </form>
    </div>
</x-layouts.admin>
