@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Tambah User</h1>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah User Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Buat akun akses untuk Admin, Guru, atau Siswa</p>
        </div>
        <x-button variant="secondary" onclick="history.back()">
            <i class="ti ti-arrow-left mr-1"></i> Kembali
        </x-button>
    </div>

    <x-card>
        <form method="POST" action="{{ route('admin.users.store') }}" x-data="{
            role: '{{ old('role', 'guru') }}',
            password: '',
            confirmPassword: ''
        }">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <x-form-input name="nama" label="Nama Lengkap" :value="old('nama')" required />
                <x-form-input name="email" label="Alamat Email" type="email" :value="old('email')" required />
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <x-form-input name="password" label="Password" type="password" x-model="password" required />
                <div>
                    <x-form-input name="password_confirmation" label="Konfirmasi Password" type="password"
                        x-model="confirmPassword" required />
                    <div x-show="confirmPassword.length > 0" class="mt-1 flex items-center gap-2 text-xs" x-transition>
                        <span x-show="password === confirmPassword"
                            class="text-emerald-600 flex items-center gap-1 font-medium">
                            <i class="ti ti-check"></i> Password cocok
                        </span>
                        <span x-show="password !== confirmPassword"
                            class="text-red-600 flex items-center gap-1 font-medium">
                            <i class="ti ti-x"></i> Password tidak cocok
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-1">Peran / Role</label>
                <select name="role" x-model="role"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent">
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bagian Khusus Guru -->
            <div x-show="role === 'guru'" x-transition
                class="p-4 bg-accent-soft/40 border border-accent/20 rounded-xl mb-5">
                <h3 class="font-semibold text-sm text-accent mb-3 flex items-center gap-1.5">
                    <i class="ti ti-id-badge-2 text-base"></i> Profil Tambahan Guru
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <x-form-input name="nip" label="NIP / No. Identitas Pegawai" :value="old('nip')"
                        placeholder="Misal: 198501012010011001" />
                    <x-form-input name="no_hp" label="No. Handphone / WhatsApp (Opsional)" :value="old('no_hp')"
                        placeholder="08xxxxxxxxxx" />
                </div>
            </div>

            <!-- Bagian Khusus Siswa -->
            <div x-show="role === 'siswa'" x-transition
                class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-xl mb-5">
                <h3 class="font-semibold text-sm text-emerald-800 mb-3 flex items-center gap-1.5">
                    <i class="ti ti-school text-base"></i> Profil Akademik Siswa
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <x-form-input name="nis" label="NIS (Nomor Induk Siswa)" :value="old('nis')"
                        placeholder="Misal: 20261001" />
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kelas Asal</label>
                        <select name="kelas_id"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $id => $namaKelas)
                                <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>
                                    {{ $namaKelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="L" {{ old('jenis_kelamin', 'L') === 'L' ? 'selected' : '' }}>Laki-laki (L)
                            </option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (P)
                            </option>
                        </select>
                    </div>
                    <x-form-input name="tanggal_lahir" label="Tanggal Lahir" type="date" :value="old('tanggal_lahir')" />
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit"
                    x-bind:disabled="confirmPassword.length > 0 && password !== confirmPassword">
                    <i class="ti ti-device-floppy mr-1"></i> Simpan User
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
