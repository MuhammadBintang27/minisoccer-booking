<x-layouts.admin title="Tambah Lapangan">
    <div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.lapangan.store') }}">
            @csrf
            @include('admin.lapangan._form')
        </form>
    </div>
</x-layouts.admin>
