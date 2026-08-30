@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Manajemen Kelas</h1>
        <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.kelas.create') }}'">Tambah Kelas</x-button>
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Nama Kelas</th>
                <th class="text-left px-4 py-3 font-medium">Tingkat</th>
                <th class="text-left px-4 py-3 font-medium">Wali Kelas</th>
                <th class="text-left px-4 py-3 font-medium">Tahun Ajaran</th>
                <th class="text-right px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>
        @foreach($kelas as $item)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $item->nama_kelas }}</td>
            <td class="px-4 py-3">{{ $item->tingkat }}</td>
            <td class="px-4 py-3">{{ $item->waliKelas->nama ?? '-' }}</td>
            <td class="px-4 py-3">{{ $item->tahun_ajaran }}</td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='{{ route('admin.kelas.edit', $item) }}'">Edit</x-button>
                <form method="POST" action="{{ route('admin.kelas.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
                    @csrf @method('DELETE')
                    <x-button variant="danger" type="submit">Hapus</x-button>
                </form>
            </td>
        </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $kelas->links() }}</div>
@endsection