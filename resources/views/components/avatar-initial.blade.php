@props(['name'])

<span {{ $attributes->merge(['class' => 'flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-navy/10 text-xs font-bold text-navy']) }}>
    {{ $name ? strtoupper(substr($name, 0, 1)) : '?' }}
</span>
