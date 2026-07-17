@props(['status'])

@php
    $tone = match (true) {
        in_array($status, ['pending', 'pending_payment']) => 'yellow',
        in_array($status, ['confirmed', 'completed', 'active', 'settlement', 'capture']) => 'green',
        default => 'slate',
    };

    $classes = [
        'yellow' => 'bg-yellow-100 text-yellow-700',
        'green' => 'bg-green-100 text-green-700',
        'slate' => 'bg-slate-200 text-slate-600',
    ][$tone];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold $classes"]) }}>
    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-current"></span>
    {{ \App\Support\StatusLabel::label($status) }}
</span>
