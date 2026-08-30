@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-2">Selamat datang, {{ auth()->user()->nama }}</h1>
    <p class="text-sm text-slate-500 mb-8">Lihat nilai, absensi, dan jadwal Anda.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-dashboard-link href="{{ route('siswa.nilai.index') }}" title="Lihat Nilai" description="Lihat nilai per mata pelajaran" />
        <x-dashboard-link href="{{ route('siswa.absensi.index') }}" title="Lihat Absensi" description="Lihat rekap kehadiran Anda" />
        <x-dashboard-link href="{{ route('siswa.jadwal.index') }}" title="Lihat Jadwal" description="Lihat jadwal pelajaran kelas Anda" />
    </div>
@endsection