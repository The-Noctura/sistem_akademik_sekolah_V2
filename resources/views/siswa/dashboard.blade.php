@extends('layouts.app')

@section('content')
    <x-hero-card
        greeting="Selamat datang kembali"
        name="{{ auth()->user()->nama }}"
        subtitle="Lihat nilai, absensi, dan jadwal Anda"
    />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-7">
        <x-menu-card href="{{ route('siswa.nilai.index') }}" icon="file-text" title="Lihat Nilai" description="Lihat nilai per mata pelajaran" />
        <x-menu-card href="{{ route('siswa.absensi.index') }}" icon="calendar" title="Lihat Absensi" description="Lihat rekap kehadiran Anda" />
        <x-menu-card href="{{ route('siswa.jadwal.index') }}" icon="clock" title="Lihat Jadwal" description="Lihat jadwal pelajaran kelas Anda" />
    </div>
@endsection