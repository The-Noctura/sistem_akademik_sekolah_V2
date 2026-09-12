@extends('layouts.app')

@section('title', 'Kelola Nilai - ' . $mengajar->kelas->nama_kelas)

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('guru.nilai.index') }}" class="hover:text-accent">Penilaian</a>
                    <span>&bull;</span>
                    <span>{{ $mengajar->kelas->nama_kelas }}</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Penilaian: {{ $mengajar->mapel->nama_mapel }}</h1>
                <p class="text-sm text-slate-500 mt-1">Kelas {{ $mengajar->kelas->nama_kelas }} &bull; Semester {{ ucfirst($mengajar->semester) }} &bull; TA {{ $mengajar->tahun_ajaran }}</p>
            </div>
            <x-button variant="secondary" onclick="location.href='{{ route('guru.nilai.index') }}'">
                <i class="ti ti-arrow-left mr-1"></i> Kembali ke Daftar Kelas
            </x-button>
        </div>

        <x-card>
            <div class="flex items-center gap-2 mb-6">
                <a href="{{ route('guru.nilai.form', ['mengajar' => $mengajar->id, 'jenis' => 'tugas']) }}"
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-sm {{ $jenis === 'tugas' ? 'bg-accent text-white shadow-accent/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    Nilai Tugas
                </a>
                <a href="{{ route('guru.nilai.form', ['mengajar' => $mengajar->id, 'jenis' => 'uts']) }}"
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-sm {{ $jenis === 'uts' ? 'bg-accent text-white shadow-accent/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    Nilai UTS
                </a>
                <a href="{{ route('guru.nilai.form', ['mengajar' => $mengajar->id, 'jenis' => 'uas']) }}"
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-sm {{ $jenis === 'uas' ? 'bg-accent text-white shadow-accent/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    Nilai UAS
                </a>
            </div>

            <form method="POST" action="{{ route('guru.nilai.store', $mengajar->id) }}">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">

                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900">
                            Input / Edit Nilai: <span class="uppercase text-accent">{{ $jenis }}</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Rentang nilai 0 s/d 100. Kosongkan jika siswa belum mengumpulkan.</p>
                    </div>
                    <x-button variant="primary" type="submit">
                        <i class="ti ti-device-floppy mr-1"></i> Simpan Semua Nilai {{ strtoupper($jenis) }}
                    </x-button>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl overflow-hidden mb-6">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 w-12 text-center">No</th>
                                <th class="px-4 py-3">Nama Siswa & NIS</th>
                                <th class="px-4 py-3 text-center bg-blue-50/50 text-accent font-bold w-40">Input {{ strtoupper($jenis) }}</th>
                                <th class="px-4 py-3 text-center text-xs text-slate-400">Tugas</th>
                                <th class="px-4 py-3 text-center text-xs text-slate-400">UTS</th>
                                <th class="px-4 py-3 text-center text-xs text-slate-400">UAS</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-slate-700">Rata-Rata</th>
                                <th class="px-4 py-3 text-right text-xs text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($siswaList as $index => $siswa)
                                @php
                                    $valCurrent = old("nilai.{$siswa->id}", match ($jenis) {
                                        'tugas' => $nilaiTugas[$siswa->id] ?? '',
                                        'uts' => $nilaiUts[$siswa->id] ?? '',
                                        'uas' => $nilaiUas[$siswa->id] ?? '',
                                        default => '',
                                    });
                                    $t = $nilaiTugas[$siswa->id] ?? '-';
                                    $u = $nilaiUts[$siswa->id] ?? '-';
                                    $a = $nilaiUas[$siswa->id] ?? '-';
                                    $rata = $rekapNilai[$siswa->id] ?? '-';
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-4 py-3 text-center text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900">{{ $siswa->nama }}</div>
                                        <span class="text-xs text-slate-400 font-mono">{{ $siswa->nis }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center bg-blue-50/30">
                                        <input type="number"
                                               name="nilai[{{ $siswa->id }}]"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               value="{{ $valCurrent }}"
                                               placeholder="-"
                                               inputmode="numeric"
                                               class="nilai-input w-24 text-center rounded-lg border-slate-300 font-bold text-sm focus:ring-accent focus:border-accent py-1">
                                    </td>
                                    <td class="px-4 py-3 text-center text-xs {{ $jenis === 'tugas' ? 'font-bold text-accent' : 'text-slate-500' }}">{{ $t }}</td>
                                    <td class="px-4 py-3 text-center text-xs {{ $jenis === 'uts' ? 'font-bold text-accent' : 'text-slate-500' }}">{{ $u }}</td>
                                    <td class="px-4 py-3 text-center text-xs {{ $jenis === 'uas' ? 'font-bold text-accent' : 'text-slate-500' }}">{{ $a }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-xs font-mono text-emerald-700">
                                        {{ $rata !== '-' ? number_format((float) $rata, 2) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if(!empty($valCurrent))
                                            <button type="button"
                                                    onclick="if(confirm('Reset nilai {{ strtoupper($jenis) }} untuk {{ $siswa->nama }}?')) document.getElementById('reset-form-{{ $siswa->id }}').submit();"
                                                    class="text-rose-500 hover:text-rose-700 text-xs hover:underline"
                                                    title="Kosongkan nilai ini">
                                                Reset
                                            </button>
                                        @else
                                            <span class="text-slate-300 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('guru.nilai.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                        &larr; Selesai & Kembali
                    </a>
                    <x-button variant="primary" type="submit">
                        <i class="ti ti-device-floppy mr-1"></i> Simpan Semua Nilai {{ strtoupper($jenis) }}
                    </x-button>
                </div>
            </form>

            @foreach($siswaList as $siswa)
                <form id="reset-form-{{ $siswa->id }}"
                      action="{{ route('guru.nilai.destroy-nilai', ['mengajar' => $mengajar->id, 'siswa' => $siswa->id, 'jenis' => $jenis]) }}"
                      method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </x-card>
    </div>

    <script>
        document.addEventListener('keydown', function (event) {
            const target = event.target;
            if (!(target instanceof HTMLInputElement)) {
                return;
            }

            if (!target.name || !target.name.startsWith('nilai[') || event.key !== 'Enter') {
                return;
            }

            event.preventDefault();

            const inputs = Array.from(document.querySelectorAll('input[name^="nilai["]'));
            const index = inputs.indexOf(target);

            if (index >= 0 && index < inputs.length - 1) {
                const next = inputs[index + 1];
                next.focus();
                next.select();
            }
        });
    </script>
@endsection
