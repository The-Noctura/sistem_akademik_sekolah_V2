@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Selamat datang, {{ auth()->user()->nama }}</h1>
        <p class="text-slate-500 mt-1">Kelola nilai, absensi, dan jadwal mengajar Anda</p>
    </div>

    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-sky-600 to-indigo-700 rounded-2xl p-6 sm:p-8 text-white mb-8 shadow-lg shadow-sky-900/10">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider text-sky-100 mb-3">
                <i class="ti ti-id-badge-2 text-sm"></i> Ruang Kerja Pendidik
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->nama }}!</h2>
            <p class="text-sky-100/90 mt-1 max-w-2xl text-sm">
                Hari ini adalah <span class="font-bold text-white uppercase">{{ $currentDayName }}</span>. Kelola absensi siswa, input penilaian akademik, dan pantau jadwal mengajar Anda secara terintegrasi.
            </p>

            <div class="flex flex-wrap gap-2.5 mt-5">
                <a href="{{ route('guru.nilai.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-slate-800 text-xs font-semibold hover:bg-sky-50 transition-all shadow-sm">
                    <i class="ti ti-award text-base text-accent"></i>
                    Input & Kelola Nilai
                </a>
                <a href="{{ route('guru.absensi.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md border border-white/20 text-white text-xs font-semibold hover:bg-white/30 transition-all">
                    <i class="ti ti-calendar-check text-base text-emerald-300"></i>
                    Catat Presensi Siswa
                </a>
                <a href="{{ route('guru.jadwal.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md border border-white/20 text-white text-xs font-semibold hover:bg-white/30 transition-all">
                    <i class="ti ti-clock text-base text-amber-300"></i>
                    Jadwal Mengajar Lengkap
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <x-card class="bg-white p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Kelas Diampu</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['kelas_count'] ?? 0 }}</p>
                <span class="text-[11px] text-slate-400">Rombel aktif semester ini</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                <i class="ti ti-school"></i>
            </div>
        </x-card>

        <x-card class="bg-white p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Mata Pelajaran</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['mapel_count'] ?? 0 }}</p>
                <span class="text-[11px] text-slate-400">Bidang studi penugasan</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                <i class="ti ti-book"></i>
            </div>
        </x-card>

        <x-card class="bg-white p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Total Siswa</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['siswa_count'] ?? 0 }}</p>
                <span class="text-[11px] text-slate-400">Siswa di kelas yang diajar</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                <i class="ti ti-users"></i>
            </div>
        </x-card>
    </div>

    <div class="mb-5">
        <h2 class="text-lg font-semibold text-slate-900">Menu Utama</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <x-menu-card href="{{ route('guru.nilai.index') }}" icon="edit" title="Input Nilai" description="Input nilai per kelas yang diajar" />
        <x-menu-card href="{{ route('guru.absensi.index') }}" icon="calendar" title="Input Absensi" description="Tandai kehadiran siswa" />
        <x-menu-card href="{{ route('guru.jadwal.index') }}" icon="clock" title="Jadwal Mengajar" description="Lihat jadwal mengajar" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <x-card class="p-0 overflow-hidden border border-slate-200/80 shadow-sm bg-white">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                            <i class="ti ti-calendar-event text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900">Jadwal Mengajar Hari Ini</h3>
                            <p class="text-[11px] text-slate-500 capitalize">Hari {{ $currentDayName }}, {{ date('d F Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.jadwal.index') }}" class="text-xs text-accent font-semibold hover:underline">
                        Lihat Jadwal Mingguan &rarr;
                    </a>
                </div>

                <div class="p-6">
                    @if($jadwalHariIni->count() > 0)
                        <div class="space-y-3">
                            @foreach($jadwalHariIni as $j)
                                <div class="p-4 rounded-xl border border-slate-200/80 hover:border-accent hover:shadow-sm transition-all bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 font-bold flex flex-col items-center justify-center shrink-0">
                                            <span class="text-xs">{{ substr($j->jam_mulai, 0, 5) }}</span>
                                            <span class="text-[9px] text-slate-400">WIB</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm text-slate-900">{{ $j->mengajar->mapel->nama_mapel }}</h4>
                                            <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 flex-wrap">
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">
                                                    Kelas {{ $j->mengajar->kelas->nama_kelas }}
                                                </span>
                                                <span>&bull;</span>
                                                <span>Ruangan: <b class="text-slate-700">{{ $j->ruangan }}</b></span>
                                                <span>&bull;</span>
                                                <span>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                        <a href="{{ route('guru.absensi.form', ['mengajar' => $j->mengajar_id, 'tanggal' => date('Y-m-d')]) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                                            <i class="ti ti-check"></i> Absen Hari Ini
                                        </a>
                                        <a href="{{ route('guru.nilai.form', $j->mengajar_id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-xs font-medium hover:bg-slate-50 transition-colors">
                                            <i class="ti ti-edit"></i> Nilai
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 font-bold text-2xl">
                                <i class="ti ti-coffee"></i>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Tidak Ada Jadwal Mengajar Hari Ini</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                Anda tidak memiliki jadwal tatap muka kelas untuk hari {{ $currentDayName }}. Anda dapat memanfaatkan waktu untuk mengoreksi tugas dan nilai siswa.
                            </p>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>

        <div class="space-y-4">
            <x-card class="bg-white border border-slate-200/80 p-5 shadow-sm">
                <h3 class="font-bold text-sm text-slate-900 mb-3 flex items-center gap-1.5">
                    <i class="ti ti-list-details text-accent"></i> Kelas yang Diampu
                </h3>

                <div class="space-y-2.5">
                    @forelse($kelasDiampu as $m)
                        <div class="p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-xs text-slate-800">{{ $m->mapel->nama_mapel }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700">
                                    {{ $m->kelas->nama_kelas }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Semester: {{ ucfirst($m->semester) }} &bull; TA {{ $m->tahun_ajaran }}</p>
                            <div class="flex gap-2 mt-2 pt-2 border-t border-slate-100/80 text-xs">
                                <a href="{{ route('guru.absensi.form', $m->id) }}" class="text-emerald-600 font-semibold hover:underline flex items-center gap-0.5">
                                    <i class="ti ti-calendar-check"></i> Absensi
                                </a>
                                <span class="text-slate-300">&bull;</span>
                                <a href="{{ route('guru.nilai.form', $m->id) }}" class="text-accent font-semibold hover:underline flex items-center gap-0.5">
                                    <i class="ti ti-award"></i> Nilai
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-xs">
                            Belum ada penugasan kelas yang diatur oleh Admin.
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
@endsection

