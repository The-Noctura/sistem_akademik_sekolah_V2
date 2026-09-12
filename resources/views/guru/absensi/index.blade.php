@extends('layouts.app')

@section('title', 'Manajemen Presensi Kelas')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Presensi & Kehadiran Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">Pilih kelas yang diampu untuk mencatat presensi harian atau melihat riwayat kehadiran</p>
        </div>

        <x-button variant="secondary" type="button" onclick="location.href='{{ route('dashboard') }}'">
            Kembali
        </x-button>
    </div>

    <x-card class="p-0 overflow-hidden bg-white border border-slate-200/80 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Mata Pelajaran</th>
                        <th class="px-5 py-3.5">Kelas</th>
                        <th class="px-5 py-3.5">Semester & TA</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mengajarList as $m)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $m->mapel->nama_mapel }}</div>
                                <span class="text-xs text-slate-400 font-mono">{{ $m->mapel->kode_mapel }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700">
                                    {{ $m->kelas->nama_kelas }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                Semester {{ ucfirst($m->semester) }} &bull; TA {{ $m->tahun_ajaran }}
                            </td>
                            <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('guru.absensi.history', $m->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                                    <i class="ti ti-history text-sm"></i> Riwayat
                                </a>
                                <a href="{{ route('guru.absensi.form', $m->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                                    <i class="ti ti-plus text-sm"></i> Input Hari Ini
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-slate-400">
                                Belum ada penugasan mengajar untuk Anda. Silakan hubungi Administrator.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
@endsection