@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-2">Selamat datang, {{ auth()->user()->nama }}</h1>
    <p class="text-sm text-slate-500 mb-8">Kelola nilai dan absensi kelas Anda.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-dashboard-link href="{{ route('guru.nilai.index') }}" title="Input Nilai" description="Input nilai per kelas yang diajar" />
        <x-dashboard-link href="{{ route('guru.absensi.index') }}" title="Input Absensi" description="Tandai kehadiran siswa" />
        <x-dashboard-link href="{{ route('guru.jadwal.index') }}" title="Jadwal Mengajar" description="Lihat jadwal mengajar" />
    </div>
@endsection