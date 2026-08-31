@extends('layouts.app')

@section('content')
    <x-hero-card
        greeting="Selamat datang kembali"
        name="{{ auth()->user()->nama }}"
        subtitle="Kelola data akademik sekolah"
    />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-7">
        <x-menu-card href="{{ route('admin.users.index') }}" icon="users" title="Manajemen User" description="Kelola akun guru dan siswa" />
        <x-menu-card href="{{ route('admin.kelas.index') }}" icon="school" title="Manajemen Kelas" description="Kelola data kelas dan wali kelas" />
        <x-menu-card href="{{ route('admin.mapel.index') }}" icon="book" title="Manajemen Mapel" description="Kelola daftar mata pelajaran" />
        <x-menu-card href="{{ route('admin.mengajar.index') }}" icon="link" title="Manajemen Mengajar" description="Hubungkan guru, mapel, dan kelas" />
        <x-menu-card href="{{ route('admin.jadwal.index') }}" icon="calendar" title="Manajemen Jadwal" description="Atur jadwal pelajaran per kelas" />
    </div>
@endsection