@extends('layouts.public')
@section('title','Berita SMKN 1 Katapang')
@section('content')
<section class="bg-slate-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-xs tracking-widest text-sky-300 font-semibold">BERITA & AGENDA</div>
        <h1 class="text-3xl font-bold mt-2">Informasi Terbaru</h1>
    </div>
</section>
<section class="py-10 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-6">
        @foreach($news as $n)
        <article class="bg-white rounded-2xl overflow-hidden border hover:shadow-md transition">
            <img src="{{ $n['img'] }}" class="w-full h-48 object-cover">
            <div class="p-5">
                <div class="flex items-center gap-2 text-xs"><span class="px-2 py-1 rounded-full bg-accent-soft text-accent font-medium">{{ $n['cat'] }}</span><span class="text-slate-500">{{ $n['date'] }}</span></div>
                <h3 class="font-semibold mt-2">{{ $n['title'] }}</h3>
                <p class="text-sm text-slate-500 mt-1">{{ $n['excerpt'] }}</p>
                <a href="#" class="text-sm text-accent font-medium mt-3 inline-flex">Baca selengkapnya →</a>
            </div>
        </article>
        @endforeach
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
