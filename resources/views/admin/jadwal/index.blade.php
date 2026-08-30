@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Manajemen Jadwal</h1>
        <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.jadwal.create') }}'">Tambah Jadwal</x-button>
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Guru - Mapel - Kelas</th>
                <th class="text-left px-4 py-3 font-medium">Hari</th>
                <th class="text-left px-4 py-3 font-medium">Jam Mulai</th>
                <th class="text-left px-4 py-3 font-medium">Jam Selesai</th>
                <th class="text-left px-4 py-3 font-medium">Ruangan</th>
                <th class="text-right px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>
        @foreach($jadwal as $item)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $item->mengajar->guru->nama }} - {{ $item->mengajar->mapel->nama_mapel }} - {{ $item->mengajar->kelas->nama_kelas }}</td>
            <td class="px-4 py-3">{{ ucfirst($item->hari) }}</td>
            <td class="px-4 py-3">{{ $item->jam_mulai }}</td>
            <td class="px-4 py-3">{{ $item->jam_selesai }}</td>
            <td class="px-4 py-3">{{ $item->ruangan }}</td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='{{ route('admin.jadwal.edit', $item) }}'">Edit</x-button>
                <form method="POST" action="{{ route('admin.jadwal.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus jadwal ini?')">
                    @csrf @method('DELETE')
                    <x-button variant="danger" type="submit">Hapus</x-button>
                </form>
            </td>
        </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $jadwal->links() }}</div>
@endsection