@extends('layouts.app')

@section('title', 'Edit Produk TEFA')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Produk TEFA</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi dan ketersediaan: {{ $tefa->nama_produk }}</p>
        </div>
        <x-button variant="secondary" onclick="history.back()">
            <i class="ti ti-arrow-left mr-1"></i> Kembali
        </x-button>
    </div>

    <x-card>
        <form method="POST" action="{{ route('admin.tefa.update', $tefa) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-5">
                <x-form-input name="nama_produk" label="Nama Produk / Jasa" :value="old('nama_produk', $tefa->nama_produk)" required />

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan Penghasil (TEFA)</label>
                    <select name="jurusan_code" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent" required>
                        @foreach($jurusanList as $code => $name)
                            <option value="{{ $code }}" {{ old('jurusan_code', $tefa->jurusan_code) === $code ? 'selected' : '' }}>
                                {{ $code }} &bull; {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent">
                        <option value="barang" {{ old('kategori', $tefa->kategori) === 'barang' ? 'selected' : '' }}>Barang / Produk Fisik</option>
                        <option value="jasa" {{ old('kategori', $tefa->kategori) === 'jasa' ? 'selected' : '' }}>Jasa / Layanan Kejuruan</option>
                    </select>
                </div>

                <x-form-input name="harga" label="Harga (Rp)" type="number" step="1000" :value="old('harga', $tefa->harga)" required />

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Ketersediaan / Stok</label>
                    <select name="status_stok" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent">
                        <option value="tersedia" {{ old('status_stok', $tefa->status_stok) === 'tersedia' ? 'selected' : '' }}>Tersedia / Siap Dipesan</option>
                        <option value="habis" {{ old('status_stok', $tefa->status_stok) === 'habis' ? 'selected' : '' }}>Habis / Pre-Order</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5 mb-4">
                <x-form-input name="nomor_wa" label="Nomor WhatsApp Pemesanan" :value="old('nomor_wa', $tefa->nomor_wa)" required />
                <x-form-input name="foto_url" label="URL Foto Produk (Jika pakai tautan web)" :value="old('foto_url', str_starts_with($tefa->foto ?? '', 'http') ? $tefa->foto : '')" />
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-1">Ganti Berkas Foto (Biarkan kosong jika tidak diganti)</label>
                <input type="file" name="foto_file" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-accent hover:file:bg-blue-100 cursor-pointer">
                @if($tefa->foto)
                    <div class="mt-2 flex items-center gap-2 text-xs text-slate-500">
                        <span>Foto saat ini:</span>
                        <a href="{{ str_starts_with($tefa->foto, 'http') ? $tefa->foto : asset('storage/' . $tefa->foto) }}" target="_blank" class="text-accent underline font-mono">Lihat Foto</a>
                    </div>
                @endif
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent">{{ old('deskripsi', $tefa->deskripsi) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <i class="ti ti-device-floppy mr-1"></i> Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-card>
@endsection

