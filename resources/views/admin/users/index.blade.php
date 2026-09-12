@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola akun akses untuk Administrator, Guru, dan Siswa</p>
        </div>

        <div class="flex gap-2">
            <x-button variant="secondary" type="button" onclick="location.href='{{ route('dashboard') }}'">
                Kembali
            </x-button>
            <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.users.create') }}'">
                <i class="ti ti-plus mr-1"></i> Tambah User Baru
            </x-button>
        </div>
    </div>

    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Nama & Identitas</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Peran (Role)</th>
                        <th class="px-5 py-3.5">Info Tambahan</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-slate-900">{{ $user->nama }}</div>
                                @if($user->role === 'guru' && $user->guru?->nip)
                                    <div class="text-xs text-slate-500 font-mono">NIP: {{ $user->guru->nip }}</div>
                                @elseif($user->role === 'siswa' && $user->siswa?->nis)
                                    <div class="text-xs text-slate-500 font-mono">NIS: {{ $user->siswa->nis }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-3.5">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-200">
                                        <i class="ti ti-shield-check text-xs"></i> Admin
                                    </span>
                                @elseif($user->role === 'guru')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="ti ti-id-badge-2 text-xs"></i> Guru
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="ti ti-school text-xs"></i> Siswa
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500">
                                @if($user->role === 'siswa' && $user->siswa?->kelas)
                                    <span class="font-medium text-slate-700">Kelas: {{ $user->siswa->kelas->nama_kelas }}</span>
                                @elseif($user->role === 'guru' && $user->guru?->no_hp)
                                    <span>WA: {{ $user->guru->no_hp }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $user->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <x-button variant="secondary" type="button" onclick="location.href='{{ route('admin.users.edit', $user) }}'">
                                    <i class="ti ti-edit text-xs mr-0.5"></i> Edit
                                </x-button>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline ml-1" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 text-xs font-medium rounded-lg border {{ $user->status === 'aktif' ? 'border-rose-200 text-rose-600 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                        {{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada user yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
