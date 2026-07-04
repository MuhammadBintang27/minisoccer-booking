<x-layouts.admin title="Edit Lapangan">
    <div class="max-w-xl rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">Edit Lapangan</h2>

        <form method="POST" action="{{ route('admin.lapangan.update', $lapangan) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.lapangan._form')
        </form>
    </div>
</x-layouts.admin>
