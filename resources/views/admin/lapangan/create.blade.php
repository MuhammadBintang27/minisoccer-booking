<x-layouts.admin title="Tambah Lapangan">
    <div class="max-w-xl rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">Tambah Lapangan</h2>

        <form method="POST" action="{{ route('admin.lapangan.store') }}" class="mt-6">
            @csrf
            @include('admin.lapangan._form')
        </form>
    </div>
</x-layouts.admin>
