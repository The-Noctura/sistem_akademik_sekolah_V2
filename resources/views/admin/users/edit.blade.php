@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Edit User</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <x-form-input name="nama" label="Nama" :value="$user->nama" />
            <x-form-input name="email" label="Email" type="email" :value="$user->email" />
            <x-form-input name="password" label="Password (kosongkan jika tidak diubah)" type="password" />
            <x-form-input name="password_confirmation" label="Konfirmasi Password" type="password" />
            <x-form-select name="role" label="Role" :options="['admin' => 'Admin', 'guru' => 'Guru', 'siswa' => 'Siswa']" :selected="$user->role" />

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-card>
@endsection