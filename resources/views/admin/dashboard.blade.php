@extends('layouts.app')
@section('content')
    <h1 class="text-xl font-semibold mb-2">Selamat datang, {{ auth()->user()->nama }}</h1>
    <p class="text-sm text-slate-500 mb-8">Kelola data akademik dari sini.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-dashboard-link href="{{ route('admin.users.index') }}" title="Manajemen User" description="Kelola akun guru dan siswa" />
        <x-dashboard-link href="{{ route('admin.kelas.index') }}" title="Manajemen Kelas" description="Kelola data kelas dan wali kelas" />
        <x-dashboard-link href="{{ route('admin.mapel.index') }}" title="Manajemen Mapel" description="Kelola daftar mata pelajaran" />
        <x-dashboard-link href="{{ route('admin.mengajar.index') }}" title="Manajemen Mengajar" description="Hubungkan guru, mapel, dan kelas" />
        <x-dashboard-link href="{{ route('admin.jadwal.index') }}" title="Manajemen Jadwal" description="Atur jadwal pelajaran per kelas" />
=======
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Selamat datang, {{ auth()->user()->nama }}</h1>
        <p class="text-slate-500 mt-1">Kelola data akademik sekolah dari dashboard ini</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Total Guru</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['guru_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-accent-soft rounded-xl flex items-center justify-center"><i class="ti ti-users text-xl text-accent"></i></div></div></x-card>
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Total Siswa</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['siswa_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="ti ti-user text-xl text-emerald-600"></i></div></div></x-card>
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Total Kelas</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['kelas_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center"><i class="ti ti-school text-xl text-amber-600"></i></div></div></x-card>
        <x-card class="bg-white"><div class="flex items-center justify-between"><div><p class="text-sm text-slate-500">Mata Pelajaran</p><p class="text-2xl font-bold text-accent mt-1">{{ $stats['mapel_count'] ?? 0 }}</p></div><div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center"><i class="ti ti-book text-xl text-violet-600"></i></div></div></x-card>
    </div>
    <h2 class="font-semibold mb-4">Menu Utama</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-menu-card href="{{ route('admin.users.index') }}" icon="users" title="Manajemen User" description="Kelola akun guru dan siswa" />
        <x-menu-card href="{{ route('admin.kelas.index') }}" icon="school" title="Manajemen Kelas" description="Kelola data kelas dan wali kelas" />
        <x-menu-card href="{{ route('admin.mapel.index') }}" icon="book" title="Manajemen Mapel" description="Kelola daftar mata pelajaran" />
        <x-menu-card href="{{ route('admin.mengajar.index') }}" icon="link" title="Manajemen Mengajar" description="Hubungkan guru, mapel, dan kelas" />
        <x-menu-card href="{{ route('admin.jadwal.index') }}" icon="calendar" title="Manajemen Jadwal" description="Atur jadwal pelajaran per kelas" />
>>>>>>> Stashed changes
    </div>
    <x-card class="bg-slate-50 mt-6">
        <h3 class="font-semibold flex items-center gap-2"><i class="ti ti-info-circle text-accent"></i> Panduan Cepat</h3>
        <div class="grid md:grid-cols-2 gap-2 mt-3 text-sm text-slate-600">
            <p>• Mulai dari <b>Manajemen User</b> → tambah Guru & Siswa</p>
            <p>• Buat <b>Kelas</b> dan tentukan wali kelas</p>
            <p>• Hubungkan di <b>Mengajar</b> (Guru+Mapel+Kelas+Semester)</p>
            <p>• Atur <b>Jadwal</b> untuk tiap mengajar</p>
        </div>
    </x-card>
@endsection