<x-layouts.admin title="Edit Lapangan">
    <div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.lapangan.update', $lapangan) }}">
            @csrf
            @method('PUT')
            @include('admin.lapangan._form')
        </form>
    </div>
</x-layouts.admin>
