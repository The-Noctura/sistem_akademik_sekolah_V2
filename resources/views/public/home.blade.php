@extends('layouts.public')
@section('title','SMKN 1 Katapang - Beranda | Sekolah Vokasi Unggul Kab. Bandung')
@section('content')
{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 text-white">
    <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle at 20% 20%, white 1px, transparent 1px);background-size:32px 32px"></div>
    <div class="absolute -top-24 -right-24 w-[520px] h-[520px] rounded-full bg-accent/20 blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-[520px] h-[520px] rounded-full bg-cyan-400/20 blur-3xl"></div>
    <div class="max-w-7xl mx-auto px-4 py-14 lg:py-20 relative">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-3 py-1 text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> PPDB 2025/2026 Telah Dibuka • 9 Kompetensi Keahlian
                </div>
                <h1 class="mt-4 text-4xl lg:text-[48px] font-bold leading-[0.95] tracking-tight" style="font-family: Plus Jakarta Sans,sans-serif">
                    Mencetak Generasi <span class="text-sky-300">Vokasi</span> Siap Kerja & Berdaya Saing
                </h1>
                <p class="mt-4 text-slate-200 text-lg leading-relaxed max-w-xl">SMKN 1 Katapang - Sekolah Negeri Akreditasi A di Katapang, Kab. Bandung. 99 guru profesional, 740 siswa, link & match industri untuk masa depan gemilang.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('public.programs') }}" class="px-6 py-3 rounded-xl bg-accent hover:bg-accent-hover text-white font-semibold inline-flex items-center gap-2">Lihat 9 Jurusan <i class="ti ti-arrow-right"></i></a>
                    <a href="{{ route('public.contact') }}" class="px-6 py-3 rounded-xl bg-white text-slate-900 font-semibold inline-flex items-center gap-2">Daftar PPDB <i class="ti ti-external-link"></i></a>
                </div>
                <div class="mt-8 grid grid-cols-3 gap-4 max-w-lg">
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center backdrop-blur">
                        <div class="text-2xl font-bold">9</div><div class="text-xs text-slate-300">Kompetensi</div>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center backdrop-blur">
                        <div class="text-2xl font-bold">A</div><div class="text-xs text-slate-300">Akreditasi</div>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center backdrop-blur">
                        <div class="text-2xl font-bold">740+</div><div class="text-xs text-slate-300">Siswa Aktif</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="rounded-[28px] overflow-hidden shadow-2xl border border-white/20 bg-white">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=900" alt="SMKN 1 Katapang" class="w-full h-[420px] object-cover">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=SMKN+1+Katapang&background=2563EB&color=fff" class="w-10 h-10 rounded-xl">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Kampus Vokasi Modern</div>
                                <div class="text-xs text-slate-500">Jl. Ceuri Kopo KM 13.5</div>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">ISO 9001:2008</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl border border-slate-200 p-4 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-accent flex items-center justify-center text-white"><i class="ti ti-award text-xl"></i></div>
                    <div>
                        <div class="text-sm font-semibold">Akreditasi A</div>
                        <div class="text-xs text-slate-500">BAN-SM No. 1214/BAN-SM/SK/2018</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Keunggulan --}}
<section class="py-14 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
            <div>
                <div class="text-xs font-semibold tracking-widest text-accent">KEUNGGULAN KAMI</div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Kenapa Memilih SMKN 1 Katapang?</h2>
            </div>
            <a href="{{ route('public.about') }}" class="text-sm font-medium text-accent hover:underline">Tentang sekolah →</a>
        </div>
        <div class="grid md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php $features=[
                ['icon'=>'ti-certificate','title'=>'Akreditasi A','desc'=>'Mutu terjamin BAN-SM 2018'],
                ['icon'=>'ti-users','title'=>'99 Guru Profesional','desc'=>'Tenaga pendidik bersertifikasi'],
                ['icon'=>'ti-building-factory','title'=>'Link & Match Industri','desc'=>'PKL di Pindad, LEN, Honda'],
                ['icon'=>'ti-laptop','title'=>'Fasilitas Modern','desc'=>'Lab CNC, TKJ, RPL, Multimedia'],
                ['icon'=>'ti-briefcase','title'=>'Siap Kerja','desc'=>'3 tahun + sertifikasi kompetensi'],
                ['icon'=>'ti-heart','title'=>'Karakter & Disiplin','desc'=>'Profil Pelajar Pancasila'],
            ]; @endphp
            @foreach($features as $f)
            <div class="bg-white rounded-2xl p-5 border border-slate-200">
                <div class="w-10 h-10 rounded-xl bg-accent-soft flex items-center justify-center text-accent mb-3"><i class="ti {{ $f['icon'] }} text-lg"></i></div>
                <div class="font-semibold text-sm">{{ $f['title'] }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ $f['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Program Keahlian --}}
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex px-3 py-1 rounded-full bg-accent-soft text-accent text-xs font-semibold">9 KOMPETENSI KEAHLIAN</div>
            <h2 class="mt-3 text-3xl font-bold tracking-tight">Pilih Masa Depanmu di Sini</h2>
            <p class="mt-2 text-slate-500">Kurikulum Merdeka + sistem Block. Jurusan favorit: TKRO, TKJ, RPL & Multimedia selalu melebihi kuota.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($programs as $p)
            <div class="group rounded-2xl border border-slate-200 p-5 hover:shadow-lg hover:border-accent/20 transition bg-white">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center"><i class="ti {{ $p['icon'] }} text-lg"></i></div>
                    <span class="text-xs font-mono px-2 py-1 rounded-lg bg-slate-100">{{ $p['code'] }}</span>
                </div>
                <h3 class="mt-4 font-semibold">{{ $p['name'] }}</h3>
                <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $p['desc'] }}</p>
                <a href="{{ route('public.programs') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-accent group-hover:gap-2 transition-all">Pelajari <i class="ti ti-arrow-right text-xs"></i></a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('public.programs') }}" class="inline-flex px-6 py-3 rounded-xl border border-slate-200 font-medium hover:bg-slate-50">Lihat detail semua jurusan</a>
        </div>
    </div>
</section>

{{-- Sambutan Kepala Sekolah --}}
<section class="py-14 bg-slate-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-accent/20 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 relative grid lg:grid-cols-3 gap-8 items-center">
        <div class="lg:col-span-1">
            <div class="rounded-3xl overflow-hidden border border-white/20">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=600" alt="Kepala Sekolah" class="w-full h-[380px] object-cover">
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="text-accent font-semibold text-xs tracking-widest">SAMBUTAN KEPALA SEKOLAH</div>
            <h3 class="text-2xl md:text-3xl font-bold mt-2 leading-tight">Mewujudkan Lulusan Kompeten, Berkarakter & Siap Bersaing di Era Industri 4.0</h3>
            <p class="mt-4 text-slate-300 leading-relaxed">SMKN 1 Katapang berkomitmen menghadirkan pendidikan vokasi berkualitas dengan penguatan soft skills, praktik industri, dan pembelajaran berbasis proyek. Kami menggandeng dunia usaha agar kompetensi siswa relevan dengan kebutuhan kerja nyata.</p>
            <p class="mt-3 text-slate-300 leading-relaxed">Dengan 9 pilihan kompetensi dan fasilitas modern, siswa dibimbing untuk berprestasi akademik, beretika, dan mandiri sesuai Profil Pelajar Pancasila.</p>
            <div class="mt-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white text-slate-900 flex items-center justify-center font-bold">HH</div>
                <div>
                    <div class="font-semibold">Hendra Hermansah, S.Pd., M.M.</div>
                    <div class="text-xs text-slate-400">Kepala SMKN 1 Katapang</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Berita --}}
<section class="py-14 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <h2 class="text-2xl font-bold">Berita & Agenda Terbaru</h2>
            <a href="{{ route('public.news') }}" class="text-sm font-medium text-accent">Lihat semua →</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($news as $n)
            <article class="bg-white rounded-2xl overflow-hidden border border-slate-200 hover:shadow-md transition">
                <img src="{{ $n['img'] }}" class="w-full h-44 object-cover">
                <div class="p-5">
                    <div class="flex items-center gap-2 text-xs"><span class="px-2 py-1 rounded-full bg-accent-soft text-accent font-medium">{{ $n['cat'] }}</span><span class="text-slate-500">{{ $n['date'] }}</span></div>
                    <h3 class="font-semibold mt-2 leading-tight">{{ $n['title'] }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ $n['excerpt'] }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="rounded-[28px] bg-accent p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-white/10 blur-2xl"></div>
            <div>
                <h3 class="text-2xl font-bold">Siap Bergabung dengan SMKN 1 Katapang?</h3>
                <p class="text-blue-100 mt-1">Daftar PPDB online atau hubungi panitia untuk konsultasi jurusan sesuai minat & peluang kerja.</p>
            </div>
            <div class="flex gap-3 relative">
                <a href="{{ route('public.contact') }}" class="px-6 py-3 rounded-xl bg-white text-slate-900 font-semibold">Hubungi Kami</a>
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-semibold">Sistem Akademik</a>
            </div>
        </div>
    </div>
</section>
@endsection
