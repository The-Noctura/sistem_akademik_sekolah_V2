@extends('layouts.app')
@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Selamat datang, {{ auth()->user()->nama }}</h1>
        <p class="text-slate-500 mt-1">Kelola nilai, absensi, dan jadwal mengajar Anda</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Kelas Diampu</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['kelas_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-accent-soft rounded-xl flex items-center justify-center"><i class="ti ti-school text-xl text-accent"></i></div></div></x-card>
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Mata Pelajaran</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['mapel_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center"><i class="ti ti-book text-xl text-blue-600"></i></div></div></x-card>
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Total Siswa</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['siswa_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="ti ti-users text-xl text-emerald-600"></i></div></div></x-card>
    </div>
    <h2 class="font-semibold mb-4">Menu Utama</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-menu-card href="{{ route('guru.nilai.index') }}" icon="edit" title="Input Nilai" description="Input nilai per kelas yang diajar" />
        <x-menu-card href="{{ route('guru.absensi.index') }}" icon="calendar" title="Input Absensi" description="Tandai kehadiran siswa" />
        <x-menu-card href="{{ route('guru.jadwal.index') }}" icon="clock" title="Jadwal Mengajar" description="Lihat jadwal mengajar" />
    </div>
@endsection
