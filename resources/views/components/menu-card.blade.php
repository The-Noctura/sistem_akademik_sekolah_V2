@props(['href', 'icon', 'title', 'description'])
<a href="{{ $href }}" class="group block bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-lg hover:border-accent/20 hover:-translate-y-0.5 transition-all">
    <div class="w-11 h-11 rounded-xl bg-accent-soft flex items-center justify-center mb-3.5 group-hover:bg-accent group-hover:text-white transition-colors">
        <i class="ti ti-{{ $icon }} text-accent group-hover:text-white text-xl transition-colors"></i>
    </div>
    <h3 class="text-sm font-semibold">{{ $title }}</h3>
    <p class="text-xs text-slate-500 leading-relaxed mt-1">{{ $description }}</p>
</a>
