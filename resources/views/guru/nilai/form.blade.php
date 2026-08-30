@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-2">Input Nilai</h1>
    <p class="text-sm text-slate-500 mb-6">
        {{ $mengajar->mapel->nama_mapel }} - {{ $mengajar->kelas->nama_kelas }} ({{ $mengajar->semester }} / {{ $mengajar->tahun_ajaran }})
    </p>

    <x-card>
        <form method="POST" action="{{ route('guru.nilai.store', $mengajar->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jenis Nilai</label>
                <select name="jenis" id="jenis"
                    class="w-full max-w-xs border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent border-slate-200">
                    <option value="tugas">Tugas</option>
                    <option value="uts">UTS</option>
                    <option value="uas">UAS</option>
                </select>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-lg mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-surface border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium w-1/2">Nama Siswa</th>
                            <th class="text-center px-4 py-3 font-medium w-1/2">Nilai (0-100)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($siswaList as $siswa)
                        <tr class="hover:bg-surface">
                            <td class="px-4 py-3">{{ $siswa->nama }} ({{ $siswa->nis }})</td>
                            <td class="px-4 py-3 text-center">
                                <input
                                    type="number"
                                    name="nilai[{{ $siswa->id }}]"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    value="{{ ${'nilai' . ucfirst($request->query('jenis', 'tugas'))}[$siswa->id] ?? ${'nilaiTugas'}[$siswa->id] ?? ${'nilaiUts'}[$siswa->id] ?? ${'nilaiUas'}[$siswa->id] ?? '' }}"
                                    class="w-24 mx-auto border rounded-sm px-2 py-1 text-sm text-center focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent border-slate-200"
                                    required
                                >
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