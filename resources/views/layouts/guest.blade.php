<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Sistem Akademik') }} - SMKN 1 Katapang</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased min-h-screen">
        <div class="min-h-screen flex">
            <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-accent via-blue-700 to-indigo-900"></div>
                <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 1px 1px, white 1px, transparent 0);background-size:22px 22px"></div>
                <div class="relative z-10 flex flex-col justify-center px-12 w-full">
                    <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 text-blue-200 text-sm mb-8 hover:text-white"><i class="ti ti-arrow-left"></i> Kembali ke Website</a>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20"><i class="ti ti-school text-3xl text-white"></i></div>
                    <h1 class="text-4xl font-bold text-white mt-6 leading-tight">Sistem Akademik<br>SMKN 1 Katapang</h1>
                    <p class="text-blue-100 mt-3 leading-relaxed">Kelola nilai, absensi & jadwal dengan mudah. Login sesuai role Admin / Guru / Siswa.</p>
                    <div class="mt-8 space-y-2 text-sm text-blue-200">
                        <div class="flex gap-2"><i class="ti ti-check"></i> Manajemen User, Kelas, Mapel terpusat</div>
                        <div class="flex gap-2"><i class="ti ti-check"></i> Input nilai & absensi transaksional</div>
                        <div class="flex gap-2"><i class="ti ti-check"></i> Rekap otomatis via trigger MySQL</div>
                    </div>
                </div>
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-blue-300 text-xs">© {{ date('Y') }} SMKN 1 Katapang</div>
            </div>
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 bg-white">
                <div class="w-full max-w-md">
                    <div class="lg:hidden mb-6 text-center">
                        <a href="{{ route('public.home') }}" class="text-sm text-accent inline-flex items-center gap-1"><i class="ti ti-arrow-left"></i> Kembali ke Beranda</a>
                        <div class="w-14 h-14 mx-auto mt-4 bg-accent-soft rounded-2xl flex items-center justify-center"><i class="ti ti-school text-2xl text-accent"></i></div>
                        <h1 class="font-bold mt-2">SMKN 1 KATAPANG</h1><p class="text-xs text-slate-500">Sistem Akademik</p>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
