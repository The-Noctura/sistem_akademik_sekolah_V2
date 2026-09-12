@extends('layouts.app')

@section('title', 'Riwayat Presensi - ' . $mengajar->kelas->nama_kelas)

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="{{ route('guru.absensi.index') }}" class="hover:text-accent">Presensi</a>
                <span>&bull;</span>
                <span>{{ $mengajar->kelas->nama_kelas }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Presensi: {{ $mengajar->mapel->nama_mapel }}</h1>
            <p class="text-sm text-slate-500 mt-1">Kelas {{ $mengajar->kelas->nama_kelas }} &bull; Semester {{ ucfirst($mengajar->semester) }} &bull; TA {{ $mengajar->tahun_ajaran }}</p>
        </div>
        <div class="flex gap-2">
            <x-button variant="secondary" onclick="location.href='{{ route('guru.absensi.index') }}'">
                <i class="ti ti-arrow-left mr-1"></i> Pilih Kelas Lain
            </x-button>
            <x-button variant="primary" onclick="location.href='{{ route('guru.absensi.form', $mengajar->id) }}'">
                <i class="ti ti-plus mr-1"></i> Input Presensi Baru
            </x-button>
        </div>
    </div>

    <x-card class="p-0 overflow-hidden bg-white border border-slate-200/80 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal Pertemuan</th>
                        <th class="px-5 py-3.5 text-center">Hadir</th>
                        <th class="px-5 py-3.5 text-center">Izin</th>
                        <th class="px-5 py-3.5 text-center">Sakit</th>
                        <th class="px-5 py-3.5 text-center">Alpa</th>
                        <th class="px-5 py-3.5 text-center">Total</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $item)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ date('l, d F Y', strtotime($item->tanggal)) }}</div>
                            <span class="text-xs text-slate-400 font-mono">{{ $item->tanggal }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                {{ $item->hadir }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                {{ $item->izin }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                {{ $item->sakit }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700">
                                {{ $item->alpa }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center font-bold text-slate-700 text-xs">
                            {{ $item->total }} Siswa
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('guru.absensi.form', ['mengajar' => $mengajar->id, 'tanggal' => $item->tanggal]) }}" 
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="ti ti-edit"></i> Edit / Koreksi
                            </a>
                            <form method="POST" action="{{ route('guru.absensi.destroy-date', ['mengajar' => $mengajar->id, 'tanggal' => $item->tanggal]) }}" 
                                  onsubmit="return confirm('Hapus seluruh presensi tanggal {{ $item->tanggal }}? Rekap kehadiran akan otomatis dihitung ulang.')" 
                                  class="inline ml-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs" title="Hapus Presensi Tanggal Ini">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                            <i class="ti ti-calendar-off text-3xl block mb-2 text-slate-300"></i>
                            Belum ada riwayat presensi yang dicatat untuk kelas ini.
                            <div class="mt-3">
                                <a href="{{ route('guru.absensi.form', $mengajar->id) }}" class="text-accent font-semibold text-xs hover:underline">
                                    + Input Presensi Pertama Kali
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-4">{{ $history->links() }}</div>
@endsection

