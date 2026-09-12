@extends('layouts.public')
@section('title', 'Berita & Pengumuman - SMKN 1 Katapang')

@section('content')
<section class="bg-slate-900 text-white py-14 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-900 via-slate-900 to-indigo-950 opacity-90"></div>
    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center sm:text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 text-xs font-semibold uppercase tracking-wider mb-2">
            <i class="ti ti-news text-sm"></i> Kabar & Informasi Terkini
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Berita, Prestasi & Pengumuman</h1>
        <p class="text-slate-300 text-sm mt-2 max-w-2xl">
            Ikuti perkembangan terkini seputar kegiatan akademik, prestasi siswa, kerja sama industri, dan informasi resmi SMKN 1 Katapang.
        </p>
    </div>
</section>

<section class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        @if(isset($categories))
            <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-8 scrollbar-none">
                <a href="{{ route('public.news') }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ empty($kategori) ? 'bg-accent text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('public.news', ['kategori' => $cat]) }}"
                       class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ ($kategori ?? '') === $cat ? 'bg-accent text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($news as $n)
                @php
                    $isModel = $n instanceof \App\Models\Berita;
                    $title = $isModel ? $n->judul : ($n['title'] ?? '');
                    $cat = $isModel ? $n->kategori : ($n['cat'] ?? 'Umum');
                    $date = $isModel ? $n->created_at->format('d M Y') : ($n['date'] ?? '');
                    $excerpt = $isModel ? $n->ringkasan : ($n['excerpt'] ?? '');
                    $thumb = $isModel ? ($n->thumbnail ? (str_starts_with($n->thumbnail, 'http') ? $n->thumbnail : asset('storage/' . $n->thumbnail)) : '/images/lapang.webp') : ($n['img'] ?? '/images/lapang.webp');
                    $readUrl = $isModel ? route('public.news.show', $n->slug) : '#';
                @endphp

                <article class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 bg-slate-100 overflow-hidden">
                            <img src="{{ $thumb }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-white/95 backdrop-blur text-xs font-bold text-slate-800 border shadow-sm">
                                {{ $cat }}
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-xs text-slate-400 font-mono mb-2 flex items-center gap-1.5">
                                <i class="ti ti-calendar text-slate-400"></i> {{ $date }}
                            </div>
                            <h3 class="font-bold text-base text-slate-900 group-hover:text-accent transition-colors line-clamp-2 leading-snug">
                                {{ $title }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                {{ $excerpt }}
                            </p>
                        </div>
                    </div>
                    <div class="p-5 pt-0">
                        @if($isModel)
                            <a href="{{ $readUrl }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-accent group-hover:gap-2 transition-all">
                                Baca Selengkapnya <i class="ti ti-arrow-right text-xs"></i>
                            </a>
                        @else
                            <span class="text-xs text-slate-400 font-medium">Arsip Berita</span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                    <i class="ti ti-news-off text-4xl text-slate-300 block mb-2"></i>
                    <h3 class="font-bold text-slate-800 text-base">Belum Ada Berita Ditemukan</h3>
                    <p class="text-xs text-slate-500 mt-1">Silakan pilih kategori lain atau kunjungi kembali nanti.</p>
                </div>
            @endforelse
        </div>

        @if(method_exists($news, 'links'))
            <div class="mt-8">
                {{ $news->links() }}
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 mt-8">
        <div class="rounded-2xl bg-white border p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-bold">Kalender Akademik 2024/2025</h4>
                <p class="text-sm text-slate-500">Lihat jadwal PAS, PKL, dan ujian kompetensi.</p>
            </div>
            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-accent text-white font-medium">Buka Sistem Akademik</a>
        </div>
    </div>
</section>
@endsection
