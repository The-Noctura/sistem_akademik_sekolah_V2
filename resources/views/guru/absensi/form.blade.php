@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-2">Input Absensi</h1>
    <p class="text-sm text-slate-500 mb-6">
        {{ $mengajar->mapel->nama_mapel }} - {{ $mengajar->kelas->nama_kelas }} ({{ $mengajar->semester }} / {{ $mengajar->tahun_ajaran }})
    </p>

    <x-card>
        <form method="POST" action="{{ route('guru.absensi.store', $mengajar->id) }}">
            @csrf

            <div class="mb-4">
                <x-form-input name="tanggal" label="Tanggal" type="date" :value="old('tanggal', now()->format('Y-m-d'))" />
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-lg mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-surface border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium w-1/2">Nama Siswa</th>
                            <th class="text-center px-4 py-3 font-medium w-1/2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($siswaList as $siswa)
                        <tr class="hover:bg-surface">
                            <td class="px-4 py-3">{{ $siswa->nama }} ({{ $siswa->nis }})</td>
                            <td class="px-4 py-3 text-center">
                                <select name="status[{{ $siswa->id }}]"
                                    class="w-36 mx-auto border rounded-sm px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent border-slate-200"
                                    required>
                                    <option value="hadir" {{ old("status.$siswa->id") == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ old("status.$siswa->id") == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ old("status.$siswa->id") == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="alpa" {{ old("status.$siswa->id") == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-card>
@endsection