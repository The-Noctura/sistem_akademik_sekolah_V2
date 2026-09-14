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
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-700">Semester {{ $semester }}</h2>
                        @if($rataRataSemester)
                            <div class="bg-accent/10 px-4 py-2 rounded-md">
                                <span class="text-sm text-slate-600">Rata-rata Semester: </span>
                                <span class="text-xl font-bold text-accent">{{ $rataRataSemester }}</span>
                            </div>
                        @endif
                    </div>

                    <x-card>
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
            @endif
        </div>
    @endif
@endsection
