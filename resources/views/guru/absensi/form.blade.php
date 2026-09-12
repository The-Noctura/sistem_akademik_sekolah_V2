@extends('layouts.app')

@section('title', 'Presensi Siswa - ' . $mengajar->kelas->nama_kelas)

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('guru.absensi.index') }}" class="hover:text-accent">Presensi</a>
                    <span>&bull;</span>
                    <a href="{{ route('guru.absensi.history', $mengajar->id) }}" class="hover:text-accent">Riwayat</a>
                    <span>&bull;</span>
                    <span>{{ $mengajar->kelas->nama_kelas }}</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Presensi: {{ $mengajar->mapel->nama_mapel }}</h1>
                <p class="text-sm text-slate-500 mt-1">Kelas {{ $mengajar->kelas->nama_kelas }} &bull; Semester {{ ucfirst($mengajar->semester) }} &bull; TA {{ $mengajar->tahun_ajaran }}</p>
            </div>

            <x-button variant="secondary" onclick="location.href='{{ route('guru.absensi.history', $mengajar->id) }}'">
                <i class="ti ti-history mr-1"></i> Lihat Riwayat Presensi
            </x-button>
        </div>

        @if(!empty($existingAbsensi))
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2">
                <i class="ti ti-info-circle text-amber-600 text-base"></i>
                <span>Data presensi untuk tanggal <b>{{ date('d F Y', strtotime($tanggal)) }}</b> sudah pernah diisi. Anda sedang dalam mode <b>Edit / Koreksi</b>.</span>
            </div>
        @endif

        <x-card>
            <div x-data="{
                setAll(status) {
                    document.querySelectorAll('select[name^=\'status[\']').forEach(el => el.value = status);
                }
            }">
                <form method="POST" action="{{ route('guru.absensi.store', $mengajar->id) }}">
                    @csrf

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-5 border-b border-slate-100">
                        <div class="w-full sm:w-64">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Presensi</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $tanggal) }}"
                                   class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent"
                                   onchange="location.href='{{ route('guru.absensi.form', $mengajar->id) }}?tanggal=' + this.value" required>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500 hidden sm:inline">Setel Cepat:</span>
                            <button type="button" @click="setAll('hadir')" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold hover:bg-emerald-100 transition-colors border border-emerald-200">
                                Semua Hadir
                            </button>
                            <button type="button" @click="setAll('izin')" class="px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition-colors border border-blue-200">
                                Semua Izin
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-xl overflow-hidden mb-6">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-3 w-12 text-center">No</th>
                                    <th class="px-5 py-3">Nama Siswa & Identitas</th>
                                    <th class="px-5 py-3 text-center w-48">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($siswaList as $index => $siswa)
                                    @php
                                        $selectedStatus = old("status.{$siswa->id}", $existingAbsensi[$siswa->id] ?? 'hadir');
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-5 py-3 text-center text-slate-400 text-xs">{{ $index + 1 }}</td>
                                        <td class="px-5 py-3">
                                            <div class="font-bold text-slate-900">{{ $siswa->nama }}</div>
                                            <div class="text-xs text-slate-400 font-mono">NIS: {{ $siswa->nis }} &bull; {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            <select name="status[{{ $siswa->id }}]"
                                                    class="w-36 mx-auto rounded-lg border-slate-300 text-xs font-semibold focus:ring-accent focus:border-accent py-1.5"
                                                    required>
                                                <option value="hadir" {{ $selectedStatus === 'hadir' ? 'selected' : '' }}>🟢 Hadir</option>
                                                <option value="izin" {{ $selectedStatus === 'izin' ? 'selected' : '' }}>🔵 Izin</option>
                                                <option value="sakit" {{ $selectedStatus === 'sakit' ? 'selected' : '' }}>🟡 Sakit</option>
                                                <option value="alpa" {{ $selectedStatus === 'alpa' ? 'selected' : '' }}>🔴 Alpa</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <a href="{{ route('guru.absensi.history', $mengajar->id) }}" class="text-xs text-slate-500 hover:text-slate-800">
                            &larr; Batalkan & Kembali ke Riwayat
                        </a>
                        <x-button variant="primary" type="submit">
                            <i class="ti ti-device-floppy mr-1"></i> Simpan Data Presensi
                        </x-button>
                    </div>
                </form>
            </div>
        </x-card>
    </div>
@endsection
