@props(['variant' => 'default'])
@php
$classes = match($variant) {
    'success' => 'bg-green-50 text-green-700',
    'error'   => 'bg-red-50 text-red-700',
    'warning' => 'bg-amber-50 text-amber-700',
    default   => 'bg-accent-soft text-accent',
};
@endphp
<span {{ $attributes->merge(['class' => "inline-block px-3 py-1 rounded-xs text-xs font-medium $classes"]) }}>{{ $slot }}</span>