@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Manajemen Mata Pelajaran</h1>
        <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.mapel.create') }}'">Tambah Mapel</x-button>
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Nama Mapel</th>
                <th class="text-left px-4 py-3 font-medium">Kode Mapel</th>
                <th class="text-right px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>
        @foreach($mapel as $item)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $item->nama_mapel }}</td>
            <td class="px-4 py-3">{{ $item->kode_mapel }}</td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='{{ route('admin.mapel.edit', $item) }}'">Edit</x-button>
                <form method="POST" action="{{ route('admin.mapel.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus mapel ini?')">
                    @csrf @method('DELETE')
                    <x-button variant="danger" type="submit">Hapus</x-button>
                </form>
            </td>
        </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $mapel->links() }}</div>
@endsection