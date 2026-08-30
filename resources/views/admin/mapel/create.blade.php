@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Tambah Mata Pelajaran</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.mapel.store') }}">
            @csrf

            <x-form-input name="nama_mapel" label="Nama Mapel" :value="old('nama_mapel')" />
            <x-form-input name="kode_mapel" label="Kode Mapel" :value="old('kode_mapel')" />

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-card>
@endsection