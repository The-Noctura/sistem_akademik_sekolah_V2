@props(['name', 'label', 'type' => 'text', 'value' => null])
@php
$displayValue = $value;
if ($type === 'time' && $value instanceof \Illuminate\Support\Carbon) {
    $displayValue = $value->format('H:i');
} elseif ($type === 'date' && $value instanceof \Illuminate\Support\Carbon) {
    $displayValue = $value->format('Y-m-d');
}
@endphp
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1.5">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $displayValue) }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}
    >
    @error($name)<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
</div>