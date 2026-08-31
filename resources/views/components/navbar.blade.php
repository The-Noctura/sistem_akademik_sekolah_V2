<nav class="border-b border-slate-200 bg-white">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-sm bg-accent"></div>
            <span class="font-semibold text-[15px]">Sistem Akademik</span>
        </div>
        @auth
        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-500">{{ auth()->user()->nama }} · {{ ucfirst(auth()->user()->role) }}</span>
            <div class="w-[34px] h-[34px] rounded-sm bg-accent-soft flex items-center justify-center text-[13px] font-semibold text-accent">
                {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-accent text-sm">Keluar</button>
            </form>
        </div>
        @endauth
    </div>
</nav>