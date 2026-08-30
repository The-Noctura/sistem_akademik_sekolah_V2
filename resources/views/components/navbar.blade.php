<nav class="border-b border-slate-200 bg-white">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <span class="font-semibold text-lg">Sistem Akademik</span>
        @auth
        <div class="flex items-center gap-4 text-sm">
            <span class="text-slate-500">{{ auth()->user()->nama }} · {{ ucfirst(auth()->user()->role) }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-accent">Keluar</button>
            </form>
        </div>
        @endauth
    </div>
</nav>