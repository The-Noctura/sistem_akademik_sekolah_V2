@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Tambah Data Mengajar</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.mengajar.store') }}">
            @csrf

            <x-form-select name="guru_id" label="Guru" :options="$guruList" :selected="old('guru_id')" />
            <x-form-select name="mapel_id" label="Mata Pelajaran" :options="$mapelList" :selected="old('mapel_id')" />
            <x-form-select name="kelas_id" label="Kelas" :options="$kelasList" :selected="old('kelas_id')" />
            <x-form-input name="tahun_ajaran" label="Tahun Ajaran" :value="old('tahun_ajaran')" />
            <x-form-input name="semester" label="Semester" :value="old('semester')" />

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-card>
@endsection