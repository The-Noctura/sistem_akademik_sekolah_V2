<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Akademik') — SMKN 1 Katapang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen" x-data="{ sidebarOpen: false }">
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <div class="flex min-h-screen">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/90 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
            <div class="h-16 px-6 flex items-center justify-between border-b border-slate-100 bg-white">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-500 flex items-center justify-center text-white font-bold shadow-md shadow-blue-500/20">
                        <i class="ti ti-school text-xl"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 text-base leading-tight block">SIAKAD</span>
                        <span class="text-[11px] font-medium text-slate-400 block tracking-wider uppercase">SMKN 1 Katapang</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 p-1">
                    <i class="ti ti-x text-lg"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
                <div>
                    <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Utama</span>
                    <nav class="mt-2 space-y-1">
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-accent text-white shadow-sm shadow-accent/30 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="ti ti-layout-dashboard text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Akademik</span>
                    <nav class="mt-2 space-y-1">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.users.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-users text-lg"></i>
                                <span>Manajemen User</span>
                            </a>
                            <a href="{{ route('admin.kelas.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.kelas.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-school text-lg"></i>
                                <span>Data Kelas</span>
                            </a>
                            <a href="{{ route('admin.mapel.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.mapel.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-book text-lg"></i>
                                <span>Mata Pelajaran</span>
                            </a>
                            <a href="{{ route('admin.mengajar.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.mengajar.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-link text-lg"></i>
                                <span>Penugasan Mengajar</span>
                            </a>
                            <a href="{{ route('admin.jadwal.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.jadwal.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-calendar text-lg"></i>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        @elseif(auth()->user()->role === 'guru')
                            <a href="{{ route('guru.nilai.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('guru.nilai.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-certificate text-lg"></i>
                                <span>Input & Rekap Nilai</span>
                            </a>
                            <a href="{{ route('guru.absensi.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('guru.absensi.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-calendar-check text-lg"></i>
                                <span>Presensi & Absensi</span>
                            </a>
                            <a href="{{ route('guru.jadwal.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('guru.jadwal.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-clock-hour-4 text-lg"></i>
                                <span>Jadwal Mengajar</span>
                            </a>
                        @elseif(auth()->user()->role === 'siswa')
                            <a href="{{ route('siswa.nilai.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('siswa.nilai.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-award text-lg"></i>
                                <span>Nilai & Rapor</span>
                            </a>
                            <a href="{{ route('siswa.absensi.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('siswa.absensi.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-calendar-check text-lg"></i>
                                <span>Rekap Kehadiran</span>
                            </a>
                            <a href="{{ route('siswa.jadwal.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('siswa.jadwal.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="ti ti-calendar-event text-lg"></i>
                                <span>Jadwal Kelas</span>
                            </a>
                        @endif
                    </nav>
                </div>

                @if(auth()->user()->role === 'admin')
                <div>
                    <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Konten & TEFA</span>
                    <nav class="mt-2 space-y-1">
                        <a href="{{ route('admin.berita.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.berita.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="ti ti-news text-lg"></i>
                            <span>Berita Sekolah</span>
                        </a>
                        <a href="{{ route('admin.tefa.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.tefa.*') ? 'bg-accent-soft text-accent font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="ti ti-shopping-bag text-lg"></i>
                            <span>Produk TEFA Jurusan</span>
                        </a>
                    </nav>
                </div>
                @endif

                <div>
                    <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Website Sekolah</span>
                    <nav class="mt-2 space-y-1">
                        <a href="{{ route('public.tefa') }}" target="_blank"
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all">
                            <i class="ti ti-building-store text-lg text-emerald-600"></i>
                            <span>Katalog TEFA Publik</span>
                            <i class="ti ti-external-link text-xs ml-auto text-slate-400"></i>
                        </a>
                        <a href="{{ route('public.home') }}" target="_blank"
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all">
                            <i class="ti ti-world text-lg text-blue-600"></i>
                            <span>Halaman Depan</span>
                            <i class="ti ti-external-link text-xs ml-auto text-slate-400"></i>
                        </a>
                    </nav>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/70">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold text-xs text-slate-900 truncate">{{ auth()->user()->nama }}</div>
                            <div class="text-[11px] text-slate-500 capitalize flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                {{ auth()->user()->role }}
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <i class="ti ti-logout text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-16 bg-white border-b border-slate-200/80 sticky top-0 z-30 px-4 sm:px-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                        <i class="ti ti-menu-2 text-xl"></i>
                    </button>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            {{ ucfirst(auth()->user()->role) }} Workspace
                        </span>
                        <h2 class="text-sm font-bold text-slate-800 leading-tight">SMK Negeri 1 Katapang</h2>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('public.home') }}" target="_blank"
                       class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        <i class="ti ti-world text-sm text-blue-600"></i>
                        Lihat Web Publik
                    </a>

                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                    <div class="flex items-center gap-2 pl-1">
                        <span class="text-xs font-medium text-slate-600 hidden md:inline-block">{{ auth()->user()->nama }}</span>
                        <span class="px-2 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider {{ auth()->user()->role === 'admin' ? 'bg-violet-100 text-violet-700' : (auth()->user()->role === 'guru' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto px-4 sm:px-8 py-6 max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div class="mb-5 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm shadow-sm" role="alert">
                        <i class="ti ti-circle-check text-xl text-emerald-600 shrink-0"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-5 flex items-center gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm" role="alert">
                        <i class="ti ti-alert-circle text-xl text-rose-600 shrink-0"></i>
                        <span class="font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-200/60 bg-white">
                &copy; {{ date('Y') }} Sistem Informasi Akademik &bull; SMK Negeri 1 Katapang
            </footer>
        </div>
    </div>
</body>
</html>
