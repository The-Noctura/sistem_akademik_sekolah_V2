@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Manajemen User</h1>
        <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.users.create') }}'">Tambah User</x-button>
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Nama</th>
                <th class="text-left px-4 py-3 font-medium">Email</th>
                <th class="text-left px-4 py-3 font-medium">Role</th>
                <th class="text-right px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>
        @foreach($users as $user)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $user->nama }}</td>
            <td class="px-4 py-3">{{ $user->email }}</td>
            <td class="px-4 py-3"><x-badge>{{ $user->role }}</x-badge></td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='{{ route('admin.users.edit', $user) }}'">Edit</x-button>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus user ini?')">
                    @csrf @method('DELETE')
                    <x-button variant="danger" type="submit">Hapus</x-button>
                </form>
            </td>
        </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection