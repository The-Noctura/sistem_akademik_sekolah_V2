@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Manajemen Mengajar</h1>
        <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.mengajar.create') }}'">Tambah Mengajar</x-button>
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Guru</th>
                <th class="text-left px-4 py-3 font-medium">Mata Pelajaran</th>
                <th class="text-left px-4 py-3 font-medium">Kelas</th>
                <th class="text-left px-4 py-3 font-medium">Tahun Ajaran</th>
                <th class="text-left px-4 py-3 font-medium">Semester</th>
                <th class="text-right px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>
        @foreach($mengajar as $item)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $item->guru->nama }}</td>
            <td class="px-4 py-3">{{ $item->mapel->nama_mapel }}</td>
            <td class="px-4 py-3">{{ $item->kelas->nama_kelas }}</td>
            <td class="px-4 py-3">{{ $item->tahun_ajaran }}</td>
            <td class="px-4 py-3">{{ $item->semester }}</td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='{{ route('admin.mengajar.edit', $item) }}'">Edit</x-button>
                <form method="POST" action="{{ route('admin.mengajar.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus data mengajar ini?')">
                    @csrf @method('DELETE')
                    <x-button variant="danger" type="submit">Hapus</x-button>
                </form>
            </td>
        </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $mengajar->links() }}</div>
@endsection