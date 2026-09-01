@props(['greeting', 'name', 'subtitle' => null])
<div class="bg-slate-900 rounded-2xl px-8 py-7 relative overflow-hidden border border-slate-800">
    <div class="absolute -top-16 -right-10 w-44 h-44 rounded-full bg-accent/20 blur-2xl"></div>
    <div class="absolute -bottom-16 right-14 w-28 h-28 rounded-full bg-blue-500/20 blur-xl"></div>
    <div class="relative">
        <p class="text-sm text-slate-400 mb-1.5">{{ $greeting }}</p>
        <h2 class="text-xl font-bold text-white">{{ $name }}</h2>
        @if($subtitle)
            <p class="text-sm text-slate-300 mt-2">{{ $subtitle }}</p>
        @endif
    </div>
</div>
