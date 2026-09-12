@extends('layouts.public')

@section('title', $berita->judul . ' - SMKN 1 Katapang')

@section('content')
    <article class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            
            <a href="{{ route('public.news') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-accent mb-6 transition-colors">
                <i class="ti ti-arrow-left"></i> Kembali ke Daftar Berita
            </a>

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6 sm:p-10">
                
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-accent-soft text-accent">
                        {{ $berita->kategori }}
                    </span>
                    <span class="text-xs text-slate-400 font-mono">
                        {{ $berita->created_at->format('d F Y, H:i') }} WIB
                    </span>
                    <span class="text-xs text-slate-400">&bull;</span>
                    <span class="text-xs text-slate-400">{{ $berita->views }}x dibaca</span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-6">
                    {{ $berita->judul }}
                </h1>

                @if($berita->thumbnail)
                    <div class="rounded-2xl overflow-hidden mb-8 shadow-sm max-h-[440px] bg-slate-100">
                        <img src="{{ str_starts_with($berita->thumbnail, 'http') ? $berita->thumbnail : asset('storage/' . $berita->thumbnail) }}" 
                             alt="{{ $berita->judul }}" 
                             class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base space-y-4">
                    {!! nl2br(e($berita->konten)) !!}
                </div>

                <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <div>Penulis: <b class="text-slate-700">{{ $berita->penulis?->nama ?? 'Tim Humas SMKN 1 Katapang' }}</b></div>
                    <div>Bagikan: 
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . url()->current()) }}" target="_blank" class="text-emerald-600 font-semibold hover:underline ml-1">
                            <i class="ti ti-brand-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>

            </div>

            <!-- Other News Recommendation -->
            @if(isset($others) && $others->count() > 0)
                <div class="mt-12">
                    <h3 class="font-bold text-lg text-slate-900 mb-6">Berita Terkait Lainnya</h3>
                    <div class="grid sm:grid-cols-3 gap-5">
                        @foreach($others as $item)
                            <a href="{{ route('public.news.show', $item->slug) }}" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow hover:-translate-y-1 transition-all">
                                <span class="text-[10px] font-bold text-rose-600 uppercase">{{ $item->kategori }}</span>
                                <h4 class="font-bold text-xs text-slate-800 line-clamp-2 mt-1">{{ $item->judul }}</h4>
                                <span class="text-[11px] text-slate-400 font-mono mt-2 block">{{ $item->created_at->format('d M Y') }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </article>
@endsection

