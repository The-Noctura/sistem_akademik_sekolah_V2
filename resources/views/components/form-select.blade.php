@props(['name', 'label', 'options' => [], 'selected' => null])
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}>
        <option value="">-- Pilih --</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error($name)<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
</div>