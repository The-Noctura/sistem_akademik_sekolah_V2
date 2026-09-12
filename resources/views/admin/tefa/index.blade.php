@extends('layouts.app')

@section('title', 'Manajemen Produk TEFA')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Katalog Produk TEFA Jurusan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola barang dan jasa hasil karya siswa Teaching Factory (TEFA) per jurusan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('public.tefa') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="ti ti-external-link text-sm"></i> Lihat di Web Publik
            </a>
            <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.tefa.create') }}'">
                <i class="ti ti-plus mr-1"></i> Tambah Produk TEFA
            </x-button>
        </div>
    </div>

    <!-- Filter Tab Jurusan -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 scrollbar-none">
        <a href="{{ route('admin.tefa.index') }}" 
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ empty($jurusan) ? 'bg-accent text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua Jurusan
        </a>
        @foreach($jurusanList as $code => $name)
            <a href="{{ route('admin.tefa.index', ['jurusan' => $code]) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $jurusan === $code ? 'bg-accent text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                {{ $code }} &bull; {{ $name }}
            </a>
        @endforeach
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($products as $p)
            <x-card class="p-0 overflow-hidden bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <!-- Product Image -->
                    <div class="relative h-44 bg-slate-100 overflow-hidden group">
                        @if($p->foto)
                            <img src="{{ str_starts_with($p->foto, 'http') ? $p->foto : asset('storage/' . $p->foto) }}" 
                                 alt="{{ $p->nama_produk }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                <i class="ti ti-photo-off text-3xl"></i>
                            </div>
                        @endif
                        <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold tracking-wider uppercase bg-slate-900/80 text-white backdrop-blur-md">
                            {{ $p->jurusan_code }}
                        </span>
                        <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $p->status_stok === 'tersedia' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                            {{ $p->status_stok }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="p-4">
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-medium mb-1 capitalize">
                            <i class="ti {{ $p->kategori === 'jasa' ? 'ti-tools' : 'ti-package' }} text-slate-500"></i>
                            {{ $p->kategori }} &bull; {{ $p->jurusan_name }}
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 line-clamp-1 mb-1">{{ $p->nama_produk }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-2 mb-3 leading-relaxed">{{ $p->deskripsi ?? 'Belum ada deskripsi detail.' }}</p>
                        <div class="font-bold text-accent text-base font-mono">{{ $p->formatted_harga }}</div>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="px-4 py-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ $p->whatsapp_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-emerald-600 font-semibold hover:underline">
                        <i class="ti ti-brand-whatsapp text-sm"></i> Test WA
                    </a>
                    <div class="flex gap-1.5">
                        <x-button variant="secondary" onclick="location.href='{{ route('admin.tefa.edit', $p) }}'" class="!px-2.5 !py-1 text-xs">
                            <i class="ti ti-edit"></i>
                        </x-button>
                        <form method="POST" action="{{ route('admin.tefa.destroy', $p) }}" onsubmit="return confirm('Hapus produk TEFA ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </x-card>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                <i class="ti ti-shopping-bag-x text-4xl text-slate-300 block mb-2"></i>
                <h3 class="font-bold text-slate-800 text-base">Belum Ada Produk TEFA</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Katalog Teaching Factory masih kosong untuk filter ini. Tambahkan produk atau jasa kejuruan pertama sekarang.
                </p>
                <x-button variant="primary" class="mt-4" onclick="location.href='{{ route('admin.tefa.create') }}'">
                    + Tambah Produk TEFA
                </x-button>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection

