@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Edit Data Mengajar</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.mengajar.update', $mengajar) }}">
            @csrf
            @method('PUT')

            <x-form-select name="guru_id" label="Guru" :options="$guruList" :selected="$mengajar->guru_id" />
            <x-form-select name="mapel_id" label="Mata Pelajaran" :options="$mapelList" :selected="$mengajar->mapel_id" />
            <x-form-select name="kelas_id" label="Kelas" :options="$kelasList" :selected="$mengajar->kelas_id" />
            <x-form-input name="tahun_ajaran" label="Tahun Ajaran" :value="$mengajar->tahun_ajaran" />
            <x-form-input name="semester" label="Semester" :value="$mengajar->semester" />

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-card>
@endsection