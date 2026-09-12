@extends('layouts.app')

@section('title', 'Tulis Berita Baru')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tulis Berita / Pengumuman</h1>
            <p class="text-sm text-slate-500 mt-1">Publikasikan informasi resmi untuk ditampilkan di website sekolah</p>
        </div>
        <x-button variant="secondary" onclick="history.back()">
            <i class="ti ti-arrow-left mr-1"></i> Kembali
        </x-button>
    </div>

    <x-card>
        <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <x-form-input name="judul" label="Judul Berita / Pengumuman" :value="old('judul')" placeholder="Contoh: SMKN 1 Katapang Buka Program Kemitraan Industri Baru" required />
            </div>

            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori Berita</label>
                    <select name="kategori" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent" required>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent">
                        <option value="publikasi" {{ old('status') === 'publikasi' ? 'selected' : '' }}>Publikasi Langsung</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <x-form-input name="thumbnail_url" label="URL Gambar Sampul / Thumbnail (Tautan Web)" :value="old('thumbnail_url')" placeholder="https://..." />
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Atau Unggah Berkas Gambar (Max 2MB)</label>
                    <input type="file" name="thumbnail_file" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-accent hover:file:bg-blue-100 cursor-pointer">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-1">Ringkasan Singkat (Lead / Excerpt)</label>
                <textarea name="ringkasan" rows="2" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent" placeholder="Tuliskan 1-2 kalimat ringkasan yang menarik...">{{ old('ringkasan') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Isi Konten Berita Lengkap</label>
                <textarea name="konten" rows="8" class="w-full rounded-lg border-slate-300 text-sm focus:ring-accent focus:border-accent" placeholder="Tuliskan isi berita lengkap di sini..." required>{{ old('konten') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <i class="ti ti-device-floppy mr-1"></i> Terbitkan Berita
                </x-button>
            </div>
        </form>
    </x-card>
@endsection

