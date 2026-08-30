@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Edit Jadwal</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.jadwal.update', $jadwal) }}">
            @csrf
            @method('PUT')

            <x-form-select name="mengajar_id" label="Mengajar" :options="$mengajarList" :selected="$jadwal->mengajar_id" />
            <x-form-select name="hari" label="Hari" :options="['senin'=>'Senin','selasa'=>'Selasa','rabu'=>'Rabu','kamis'=>'Kamis','jumat'=>'Jumat','sabtu'=>'Sabtu']" :selected="$jadwal->hari" />
            <x-form-input name="jam_mulai" label="Jam Mulai" type="time" :value="$jadwal->jam_mulai" />
            <x-form-input name="jam_selesai" label="Jam Selesai" type="time" :value="$jadwal->jam_selesai" />
            <x-form-input name="ruangan" label="Ruangan" :value="$jadwal->ruangan" />

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-card>
@endsection