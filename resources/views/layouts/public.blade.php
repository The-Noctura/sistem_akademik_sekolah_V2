<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','SMKN 1 Katapang - Sekolah Vokasi Unggul di Kabupaten Bandung')</title>
    <meta name="description" content="@yield('desc','SMKN 1 Katapang - 9 Kompetensi Keahlian, Akreditasi A, Jalan Ceuri Terusan Kopo KM 13.5, Katapang, Kab. Bandung')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite('resources/css/app.css')
</head>
<body class="bg-white text-slate-900 font-sans antialiased">
    {{-- Topbar --}}
    <div class="bg-slate-900 text-slate-300 text-xs">
        <div class="max-w-7xl mx-auto px-4 h-9 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5"><i class="ti ti-map-pin text-sm"></i> Jl. Ceuri Terusan Kopo KM 13.5, Katapang - Kab. Bandung 40921</span>
                <span class="hidden md:flex items-center gap-1.5"><i class="ti ti-phone text-sm"></i> 022-5893737</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline">NPSN 20206214 • Akreditasi A • ISO 9001:2008</span>
                <a href="mailto:smkn1katapang@yahoo.co.id" class="hover:text-white">smkn1katapang@yahoo.co.id</a>
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 h-[72px] flex items-center justify-between">
            <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent flex items-center justify-center text-white">
                    <i class="ti ti-school text-xl"></i>
                </div>
                <div class="leading-tight">
                    <div class="font-bold text-[15px] tracking-tight" style="font-family: Plus Jakarta Sans, sans-serif">SMKN 1 KATAPANG</div>
                    <div class="text-xs text-slate-500 -mt-0.5">Kab. Bandung • NPSN 20206214</div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1 text-sm font-medium">
                <a href="{{ route('public.home') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('public.home') ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">Beranda</a>
                <a href="{{ route('public.about') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('public.about') ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">Profil</a>
                <a href="{{ route('public.programs') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('public.programs') ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">Program Keahlian</a>
                <a href="{{ route('public.facilities') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('public.facilities') ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">Fasilitas</a>
                <a href="{{ route('public.news') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('public.news') ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">Berita</a>
                <a href="{{ route('public.contact') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('public.contact') ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">Kontak</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-accent text-white text-sm font-medium hover:bg-accent-hover transition">
                    <i class="ti ti-login"></i> Sistem Akademik
                </a>
                {{-- Mobile hamburger --}}
                <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="lg:hidden p-2 rounded-lg border border-slate-200">
                    <i class="ti ti-menu-2 text-lg"></i>
                </button>
            </div>
        </div>
        {{-- Mobile menu --}}
        <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 bg-white">
            <nav class="px-4 py-3 flex flex-col gap-1 text-sm">
                <a href="{{ route('public.home') }}" class="px-3 py-2.5 rounded-xl hover:bg-slate-100">Beranda</a>
                <a href="{{ route('public.about') }}" class="px-3 py-2.5 rounded-xl hover:bg-slate-100">Profil Sekolah</a>
                <a href="{{ route('public.programs') }}" class="px-3 py-2.5 rounded-xl hover:bg-slate-100">Program Keahlian (9 Jurusan)</a>
                <a href="{{ route('public.facilities') }}" class="px-3 py-2.5 rounded-xl hover:bg-slate-100">Fasilitas</a>
                <a href="{{ route('public.news') }}" class="px-3 py-2.5 rounded-xl hover:bg-slate-100">Berita & PPDB</a>
                <a href="{{ route('public.contact') }}" class="px-3 py-2.5 rounded-xl hover:bg-slate-100">Kontak</a>
                <a href="{{ route('login') }}" class="mt-2 inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-accent text-white font-medium">Masuk Sistem Akademik</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-accent flex items-center justify-center text-white"><i class="ti ti-school"></i></div>
                        <div class="text-white font-bold leading-tight">SMKN 1 KATAPANG</div>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-400">Sekolah Menengah Kejuruan Negeri di bawah naungan Dinas Pendidikan Prov. Jawa Barat. Berdiri 1999/2000, kini 9 kompetensi keahlian & akreditasi A.</p>
                    <div class="flex gap-2 mt-4">
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20"><i class="ti ti-brand-facebook"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20"><i class="ti ti-brand-instagram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20"><i class="ti ti-brand-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('public.about') }}" class="hover:text-white">Profil & Sejarah</a></li>
                        <li><a href="{{ route('public.programs') }}" class="hover:text-white">9 Program Keahlian</a></li>
                        <li><a href="{{ route('public.facilities') }}" class="hover:text-white">Fasilitas & Ekstrakurikuler</a></li>
                        <li><a href="{{ route('public.news') }}" class="hover:text-white">Berita & Agenda</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Sistem Akademik (Login)</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Kontak</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex gap-2"><i class="ti ti-map-pin mt-0.5"></i> Jl. Ceuri Terusan Kopo KM 13.5, Katapang, Kab. Bandung 40921</li>
                        <li class="flex gap-2"><i class="ti ti-phone"></i> (022) 5893737</li>
                        <li class="flex gap-2"><i class="ti ti-mail"></i> smkn1katapang@yahoo.co.id</li>
                        <li class="flex gap-2"><i class="ti ti-world"></i> www.smkn1katapang.sch.id</li>
                    </ul>
                </div>
                <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                    <h4 class="text-white font-semibold">PPDB 2025/2026</h4>
                    <p class="text-sm text-slate-400 mt-1">Pendaftaran 17 rombel untuk 9 kompetensi. Jurusan favorit: TKRO, TKJ, RPL, Multimedia.</p>
                    <a href="{{ route('public.contact') }}" class="mt-3 inline-flex w-full justify-center px-4 py-2 rounded-xl bg-accent text-white text-sm font-medium">Hubungi Panitia PPDB</a>
                    <p class="text-xs text-slate-500 mt-2">Kepala Sekolah: Hendra Hermansah, S.Pd., M.M.</p>
                </div>
            </div>
            <div class="border-t border-white/10 mt-10 pt-6 flex flex-col md:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                <span>© {{ date('Y') }} SMKN 1 Katapang. All rights reserved.</span>
                <span>Dibuat untuk Sistem Akademik Sekolah • Laravel 11 + Blade</span>
            </div>
        </div>
    </footer>
</body>
</html>
