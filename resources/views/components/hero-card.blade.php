@props(['greeting', 'name', 'subtitle' => null])
<div class="bg-darkbg rounded-lg px-8 py-7 relative overflow-hidden">
    <div class="absolute -top-16 -right-10 w-44 h-44 rounded-full" style="background: rgba(37,99,235,0.35)"></div>
    <div class="absolute -bottom-16 right-14 w-28 h-28 rounded-full" style="background: rgba(37,99,235,0.20)"></div>
    <div class="relative">
        <p class="text-sm text-slate-400 mb-1.5">{{ $greeting }}</p>
        <h2 class="text-xl font-semibold text-white">{{ $name }}</h2>
        @if($subtitle)
            <p class="text-sm text-slate-300 mt-2">{{ $subtitle }}</p>
        @endif
    </div>
</div>