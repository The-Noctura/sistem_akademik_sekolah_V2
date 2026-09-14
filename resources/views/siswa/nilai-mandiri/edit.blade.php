@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Edit Nilai Raport</h1>

    <x-card class="max-w-lg">
        <form method="POST" action="{{ route('siswa.nilai-mandiri.update', $nilai->id) }}">
            @csrf
            @method('PUT')

            <x-form-input
                name="nama_mapel"
                label="Nama Mata Pelajaran"
                type="text"
                :value="$nilai->nama_mapel"
            />

            <x-form-select
                name="semester"
                label="Semester"
                :options="['1' => 'Semester 1', '2' => 'Semester 2', '3' => 'Semester 3', '4' => 'Semester 4', '5' => 'Semester 5']"
                :selected="$nilai->semester"
            />

            <x-form-input
                name="nilai"
                label="Nilai Rata-Rata Rapor (0-100)"
                type="number"
                step="0.01"
                min="0"
                max="100"
                :value="$nilai->nilai"
            />

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan Perubahan</x-button>
            </div>
        </form>
    </x-card>
@endsection
