@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Input Nilai Raport</h1>

    <x-card>
        <form method="POST" action="{{ route('siswa.nilai-mandiri.store') }}" id="formNilai">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Semester</label>
                <select name="semester" id="semester" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent">
                    <option value="">Pilih Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                    <option value="3">Semester 3</option>
                    <option value="4">Semester 4</option>
                    <option value="5">Semester 5</option>
                </select>
                @error('semester')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-slate-700">Daftar Mata Pelajaran</label>
                    <button type="button" onclick="tambahBaris()" class="bg-accent text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-accent/90">+ Tambah Mapel</button>
                </div>

                <div id="containerNilai" class="space-y-3">
                    <div class="nilaiRow flex gap-3 items-end">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-slate-600 mb-1">Mata Pelajaran</label>
                            <input type="text" name="nama_mapel[]" placeholder="Contoh: Matematika" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-accent" required>
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-medium text-slate-600 mb-1">Nilai</label>
                            <input type="number" name="nilai[]" placeholder="0-100" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-accent" required>
                        </div>
                        <button type="button" onclick="hapusBaris(this)" class="bg-red-500 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-red-600">Hapus</button>
                    </div>
                </div>
                @error('nama_mapel')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                @error('nilai')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan Semua</x-button>
            </div>
        </form>
    </x-card>

    <script>
        function tambahBaris() {
            const container = document.getElementById('containerNilai');
            const baruHtml = `
                <div class="nilaiRow flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Mata Pelajaran</label>
                        <input type="text" name="nama_mapel[]" placeholder="Contoh: Matematika" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-accent" required>
                    </div>
                    <div class="w-24">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nilai</label>
                        <input type="number" name="nilai[]" placeholder="0-100" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-accent" required>
                    </div>
                    <button type="button" onclick="hapusBaris(this)" class="bg-red-500 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-red-600">Hapus</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', baruHtml);
        }

        function hapusBaris(btn) {
            const row = btn.closest('.nilaiRow');
            row.remove();
        }
    </script>
@endsection
