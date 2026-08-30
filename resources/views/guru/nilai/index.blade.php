@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Input Nilai</h1>
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Mata Pelajaran</th>
                <th class="text-left px-4 py-3 font-medium">Kelas</th>
                <th class="text-left px-4 py-3 font-medium">Semester</th>
                <th class="text-left px-4 py-3 font-medium">Tahun Ajaran</th>
                <th class="text-right px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>
        @foreach($mengajarList as $m)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $m->mapel->nama_mapel }}</td>
            <td class="px-4 py-3">{{ $m->kelas->nama_kelas }}</td>
            <td class="px-4 py-3">{{ $m->semester }}</td>
            <td class="px-4 py-3">{{ $m->tahun_ajaran }}</td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='{{ route('guru.nilai.form', $m->id) }}'">Input Nilai</x-button>
            </td>
        </tr>
        @endforeach
    </x-table>
@endsection