@extends('layouts.app')

@section('title', 'Manajemen Berita')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Berita & Pengumuman</h1>
            <p class="text-sm text-slate-500 mt-1">Publikasikan informasi kegiatan sekolah, prestasi, dan pengumuman resmi</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('public.news') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="ti ti-external-link text-sm"></i> Berita di Web Publik
            </a>
            <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.berita.create') }}'">
                <i class="ti ti-plus mr-1"></i> Tulis Berita Baru
            </x-button>
        </div>
    </div>

    <!-- Filter Kategori -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 scrollbar-none">
        <a href="{{ route('admin.berita.index') }}" 
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ empty($kategori) ? 'bg-accent text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua Kategori
        </a>
        @foreach($kategoriList as $kat)
            <a href="{{ route('admin.berita.index', ['kategori' => $kat]) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $kategori === $kat ? 'bg-accent text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                {{ $kat }}
            </a>
        @endforeach
    </div>

    <x-card class="p-0 overflow-hidden bg-white border border-slate-200/80 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Thumbnail & Judul Berita</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Penulis</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($beritaList as $b)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-10 rounded-lg bg-slate-100 overflow-hidden shrink-0">
                                    @if($b->thumbnail)
                                        <img src="{{ str_starts_with($b->thumbnail, 'http') ? $b->thumbnail : asset('storage/' . $b->thumbnail) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <i class="ti ti-photo text-base"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-xs text-slate-900 line-clamp-1">{{ $b->judul }}</h4>
                                    <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $b->ringkasan }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                {{ $b->kategori }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            {{ $b->penulis?->nama ?? 'Admin' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $b->status === 'publikasi' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                {{ ucfirst($b->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500 font-mono">
                            {{ $b->created_at->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <x-button variant="secondary" onclick="location.href='{{ route('admin.berita.edit', $b) }}'" class="!px-2.5 !py-1 text-xs">
                                <i class="ti ti-edit"></i> Edit
                            </x-button>
                            <form method="POST" action="{{ route('admin.berita.destroy', $b) }}" onsubmit="return confirm('Hapus berita ini?')" class="inline ml-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-xs rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 font-medium">
                                    <i class="ti ti-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-400">Belum ada berita yang ditulis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-4">{{ $beritaList->links() }}</div>
@endsection

