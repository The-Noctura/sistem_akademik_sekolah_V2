@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Manajemen Mata Pelajaran</h1>
        <div class="flex gap-3">
            <x-button variant="secondary" type="button" onclick="location.href='{{ route('dashboard') }}'">Kembali</x-button>
            <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.mapel.create') }}'">Tambah Mapel</x-button>
        </div>
    </div>

    <div class="flex gap-2 mb-4">
        @php $statuses = ['' => 'Semua', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] @endphp
        @foreach($statuses as $key => $label)
            <a href="{{ request()->url() . ($key ? '?status=' . $key : '') }}" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->query('status') === $key ? 'bg-accent text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Nama Mapel</th>
                <th class="text-left px-4 py-3 font-medium">Kode Mapel</th>
                <th class="text-left px-4 py-3 font-medium">Status</th>
                <th class="text-right px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>
        @foreach($mapel as $item)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $item->nama_mapel }}</td>
            <td class="px-4 py-3">{{ $item->kode_mapel }}</td>
            <td class="px-4 py-3">
                <x-badge :variant="$item->status === 'aktif' ? 'success' : 'error'">
                    {{ ucfirst($item->status) }}
                </x-badge>
            </td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='{{ route('admin.mapel.edit', $item) }}'">Edit</x-button>
                <form method="POST" action="{{ route('admin.mapel.destroy', $item) }}" class="inline ml-2" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status mata pelajaran ini?')">
                    @csrf @method('DELETE')
                    <x-button variant="{{ $item->status === 'aktif' ? 'danger' : 'success' }}" type="submit">
                        {{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </x-button>
                </form>
            </td>
        </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $mapel->links() }}</div>
@endsection