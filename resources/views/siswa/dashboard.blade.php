@extends('layouts.app')
@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Selamat datang, {{ auth()->user()->nama }}</h1>
        <p class="text-slate-500 mt-1">Lihat nilai, absensi, dan jadwal pelajaran Anda</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Mata Pelajaran</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['mapel_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-accent-soft rounded-xl flex items-center justify-center"><i class="ti ti-book text-xl text-accent"></i></div></div></x-card>
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Rata-rata Nilai</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['avg_nilai'] ?? '-' }}</p></div><div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="ti ti-chart-line text-xl text-emerald-600"></i></div></div></x-card>
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Kehadiran</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['persen_hadir'] ?? '-' }}%</p></div><div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center"><i class="ti ti-calendar-check text-xl text-blue-600"></i></div></div></x-card>
    </div>
    <h2 class="font-semibold mb-4">Menu Utama</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-menu-card href="{{ route('siswa.nilai.index') }}" icon="file-text" title="Lihat Nilai" description="Lihat nilai per mata pelajaran" />
        <x-menu-card href="{{ route('siswa.absensi.index') }}" icon="calendar" title="Lihat Absensi" description="Lihat rekap kehadiran Anda" />
        <x-menu-card href="{{ route('siswa.jadwal.index') }}" icon="clock" title="Lihat Jadwal" description="Lihat jadwal pelajaran kelas Anda" />
    </div>
@endsection
