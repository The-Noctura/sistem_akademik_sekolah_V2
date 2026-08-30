@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Edit Kelas</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">
            @csrf
            @method('PUT')

            <x-form-input name="nama_kelas" label="Nama Kelas" :value="$kelas->nama_kelas" />
            <x-form-input name="tingkat" label="Tingkat" :value="$kelas->tingkat" />
            <x-form-select name="wali_kelas_id" label="Wali Kelas" :options="$guruList" :selected="$kelas->wali_kelas_id" />
            <x-form-input name="tahun_ajaran" label="Tahun Ajaran" :value="$kelas->tahun_ajaran" />

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-card>
@endsection