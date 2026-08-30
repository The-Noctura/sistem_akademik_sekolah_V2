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
    </div>
@endsection