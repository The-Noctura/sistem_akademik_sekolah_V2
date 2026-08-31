@props(['href', 'icon', 'title', 'description'])
<a href="{{ $href }}" class="block bg-surface rounded p-5 hover:shadow-md transition-all">
    <div class="w-[38px] h-[38px] rounded-sm bg-accent-soft flex items-center justify-center mb-3.5">
        <i class="ti ti-{{ $icon }} text-accent text-lg"></i>
    </div>
    <h3 class="text-sm font-semibold mb-1">{{ $title }}</h3>
    <p class="text-xs text-slate-500 leading-relaxed">{{ $description }}</p>
</a>