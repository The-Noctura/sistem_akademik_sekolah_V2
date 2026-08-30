@props(['variant' => 'primary', 'type' => 'button'])
@php
$classes = match($variant) {
    'primary'   => 'bg-accent text-white hover:bg-accent-hover',
    'secondary' => 'bg-transparent border border-slate-200 text-slate-900 hover:bg-surface',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
};
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => "px-4 py-2 rounded-md text-sm font-medium transition-colors $classes"]) }}>
    {{ $slot }}
</button>