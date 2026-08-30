@props(['href', 'title', 'description'])
<a href="{{ $href }}" class="block bg-surface border border-slate-200 rounded-lg p-6 hover:shadow-md hover:border-accent transition-all">
    <h3 class="font-semibold mb-1">{{ $title }}</h3>
    <p class="text-sm text-slate-500">{{ $description }}</p>
</a>