@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Nilai Raport Mandiri</h1>
        <x-button variant="primary" type="button" onclick="location.href='{{ route('siswa.nilai-mandiri.create') }}'">Tambah Nilai</x-button>
    </div>

    @if(empty($dataBySemester))
        <x-card>
            <p class="text-sm text-slate-500">Belum ada nilai raport. <a href="{{ route('siswa.nilai-mandiri.create') }}" class="text-accent hover:underline">Tambahkan sekarang</a></p>
        </x-card>
    @else
        <div class="space-y-6">
            @php
                $rataRataKeseluruhan = null;
                $semesterDenganRata = 0;
            @endphp

            @foreach($dataBySemester as $semester => $daftarNilai)
                @php
                    $rataRataSemester = \App\Models\NilaiMandiriSiswa::getRataRataSemester($siswa->id, $semester);
                    if ($rataRataSemester) {
                        $semesterDenganRata++;
                    }
                @endphp
                <div>
                    <x-card class="border-2 border-accent/30 mb-4">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 flex items-center justify-center bg-accent/10 rounded-full">
                                    <span class="text-xl font-bold text-accent">S{{ $semester }}</span>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-700">Semester {{ $semester }}</h2>
                                    <p class="text-sm text-slate-500">Mata Pelajaran: {{ count($daftarNilai) }}</p>
                                </div>
                            </div>
                            @if($rataRataSemester)
                                <div class="bg-accent/10 px-6 py-3 rounded-lg">
                                    <div class="text-center">
                                        <p class="text-xs text-slate-600 mb-1">Rata-rata Semester</p>
                                        <p class="text-3xl font-bold text-accent">{{ $rataRataSemester }}</p>
                                        @if($rataRataSemester >= 85)
                                            <p class="text-xs text-green-600 mt-1">Sangat Baik</p>
                                        @elseif($rataRataSemester >= 75)
                                            <p class="text-xs text-blue-600 mt-1">Baik</p>
                                        @elseif($rataRataSemester >= 65)
                                            <p class="text-xs text-yellow-600 mt-1">Cukup</p>
                                        @else
                                            <p class="text-xs text-red-600 mt-1">Perlu Perbaikan</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <x-table>
                            <x-slot:head>
                                <tr>
                                    <th class="text-left px-4 py-3 font-medium">Mata Pelajaran</th>
                                    <th class="text-center px-4 py-3 font-medium">Nilai Raport</th>
                                    <th class="text-right px-4 py-3 font-medium">Aksi</th>
                                </tr>
                            </x-slot:head>
                            @foreach($daftarNilai as $nilai)
                                <tr class="hover:bg-surface">
                                    <td class="px-4 py-3">{{ $nilai['nama_mapel'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="font-semibold text-accent">{{ $nilai['nilai'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-button variant="secondary" size="sm" type="button" onclick="location.href='{{ route('siswa.nilai-mandiri.edit', $nilai['id']) }}'">Edit</x-button>
                                            <form method="POST" action="{{ route('siswa.nilai-mandiri.destroy', $nilai['id']) }}" style="display:inline;" onsubmit="return confirm('Hapus nilai ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="danger" size="sm" type="submit">Hapus</x-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </x-card>
                </div>
            @endforeach

            @php
                $rataRataKeseluruhan = \App\Models\NilaiMandiriSiswa::getRataRataKeseluruhan($siswa->id);
            @endphp

            @if($rataRataKeseluruhan)
                <div class="mt-8 p-6 bg-gradient-to-r from-accent/10 to-accent/5 rounded-lg border-2 border-accent/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 mb-2">Rata-rata Nilai Keseluruhan</p>
                            <p class="text-4xl font-bold text-accent">{{ $rataRataKeseluruhan }}</p>
                            <p class="text-xs text-slate-500 mt-1">Dari {{ $semesterDenganRata }} semester(s)</p>
                        </div>
                        <div class="text-right">
                            @if($rataRataKeseluruhan >= 85)
                                <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">Sangat Baik</span>
                            @elseif($rataRataKeseluruhan >= 75)
                                <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold">Baik</span>
                            @elseif($rataRataKeseluruhan >= 65)
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">Cukup</span>
                            @else
                                <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-full font-semibold">Kurang</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-lg font-bold text-slate-700 mb-4">Distribusi Akreditasi</h2>
                    <x-card>
                        @php
                            $distribusi = \App\Models\NilaiMandiriSiswa::getDistribusiAkreditasi($siswa->id);
                            $persentase = \App\Models\NilaiMandiriSiswa::getPersentaseAkreditasi($siswa->id);
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex items-center justify-center">
                                <svg class="w-40 h-40 transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-200" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                                    @php
                                        $offset = 0;
                                        $colors = [
                                            'A+' => '#22c55e',
                                            'A' => '#4ade80',
                                            'B' => '#facc15',
                                            'C' => '#fb923c',
                                            'D' => '#ef4444',
                                        ];
                                        foreach (['A+', 'A', 'B', 'C', 'D'] as $grade) {
                                            $persen = $persentase[$grade];
                                            if ($persen > 0) {
                                                $dashArray = ($persen / 100) * 100;
                                                echo '<path class="hover:opacity-80 transition-opacity" stroke-linecap="round" style="stroke-dasharray: ' . $dashArray . ', 100; stroke-dashoffset: ' . $offset . '; stroke: ' . $colors[$grade] . '" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" /><text x="18" y="' . (2 + $offset/3) . '" text-anchor="middle" font-size="2" fill="' . $colors[$grade] . '" font-weight="bold">' . $grade . '</text>';
                                                $offset -= ($persen / 100) * 100;
                                            }
                                        }
                                    @endphp
                                </svg>
                                <div class="flex flex-col gap-2 ml-6">
                                    @foreach(['A+', 'A', 'B', 'C', 'D'] as $grade)
                                        @php
                                            $count = $distribusi[$grade];
                                            $persen = $persentase[$grade];
                                            $bgClass = match($grade) {
                                                'A+' => 'bg-green-500',
                                                'A' => 'bg-green-400',
                                                'B' => 'bg-yellow-400',
                                                'C' => 'bg-orange-400',
                                                'D' => 'bg-red-500',
                                            };
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-full {{ $bgClass }}"></div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="font-semibold text-slate-700">Grade {{ $grade }}</span>
                                                    <span class="text-slate-600">{{ $count }} mapel ({{ $persen }}%)</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex flex-col justify-center space-y-4">
                                <div class="bg-slate-50 rounded-lg p-4 text-center">
                                    <p class="text-sm text-slate-500 mb-1">Total Mata Pelajaran</p>
                                    <p class="text-4xl font-bold text-accent">{{ $distribusi['total'] }}</p>
                                </div>
                                @if($rataRataKeseluruhan)
                                <div class="bg-gradient-to-br from-accent/10 to-accent/5 rounded-lg p-4 text-center border border-accent/30">
                                    <p class="text-sm text-slate-600 mb-2">Rata-rata Keseluruhan</p>
                                    <p class="text-3xl font-bold text-accent">{{ $rataRataKeseluruhan }}</p>
                                    @if($rataRataKeseluruhan >= 85)
                                        <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium mt-2">Sangat Baik</span>
                                    @elseif($rataRataKeseluruhan >= 75)
                                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium mt-2">Baik</span>
                                    @elseif($rataRataKeseluruhan >= 65)
                                        <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium mt-2">Cukup</span>
                                    @else
                                        <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium mt-2">Perlu Perbaikan</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </x-card>
                </div>
            @endif
        </div>
    @endif
@endsection
