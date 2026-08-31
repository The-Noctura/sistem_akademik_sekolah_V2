@extends('layouts.app')

@section('content')
    <x-hero-card
        greeting="Selamat datang kembali"
        name="{{ auth()->user()->nama }}"
        subtitle="Input nilai dan absensi kelas Anda"
    />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-7">
        <x-menu-card href="{{ route('guru.nilai.index') }}" icon="edit" title="Input Nilai" description="Input nilai per kelas yang diajar" />
        <x-menu-card href="{{ route('guru.absensi.index') }}" icon="calendar" title="Input Absensi" description="Tandai kehadiran siswa" />
        <x-menu-card href="{{ route('guru.jadwal.index') }}" icon="clock" title="Jadwal Mengajar" description="Lihat jadwal mengajar" />
    </div>
@endsection