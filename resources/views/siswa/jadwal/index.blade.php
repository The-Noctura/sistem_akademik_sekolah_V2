@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Jadwal Pelajaran</h1>
    </div>

    @if($jadwal->isEmpty())
        <p class="text-slate-500 text-center py-8">Belum ada jadwal untuk kelas Anda.</p>
    @else
        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $hari)
            @if($jadwal->has($hari))
            <x-card :title="ucfirst($hari)" class="mb-4">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th class="text-left px-4 py-3 font-medium">Mata Pelajaran</th>
                            <th class="text-left px-4 py-3 font-medium">Guru</th>
                            <th class="text-left px-4 py-3 font-medium">Jam Mulai</th>
                            <th class="text-left px-4 py-3 font-medium">Jam Selesai</th>
                            <th class="text-left px-4 py-3 font-medium">Ruangan</th>
                        </tr>
                    </x-slot:head>
                    @foreach($jadwal[$hari] as $j)
                    <tr class="hover:bg-surface">
                        <td class="px-4 py-3">{{ $j->mengajar->mapel->nama_mapel }}</td>
                        <td class="px-4 py-3">{{ $j->mengajar->guru->nama }}</td>
                        <td class="px-4 py-3">{{ $j->jam_mulai }}</td>
                        <td class="px-4 py-3">{{ $j->jam_selesai }}</td>
                        <td class="px-4 py-3">{{ $j->ruangan }}</td>
                    </tr>
                    @endforeach
                </x-table>
            </x-card>
            @endif
        @endforeach
    @endif
@endsection