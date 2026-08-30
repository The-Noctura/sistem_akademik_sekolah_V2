@props(['name', 'label', 'type' => 'text', 'value' => null])
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}
    >
    @error($name)<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
</div>