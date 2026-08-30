@props(['title' => null])
<div {{ $attributes->merge(['class' => 'bg-surface border border-slate-200 rounded-lg p-6 shadow-sm']) }}>
    @if($title)<h3 class="text-lg font-semibold mb-4">{{ $title }}</h3>@endif
    {{ $slot }}
</div>