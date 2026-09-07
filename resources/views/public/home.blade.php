@extends('layouts.public')
@section('title','SMKN 1 Katapang - Beranda | Sekolah Vokasi Unggul Kab. Bandung')
@section('content')
<style>
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
@keyframes float2{0%,100%{transform:translateY(0) translateX(0)}50%{transform:translateY(-14px) translateX(6px)}}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
.reveal{opacity:0;transform:translateY(18px);transition:opacity .6s ease,transform .6s ease}
.reveal.active{opacity:1;transform:none}
.reveal-delay-1{transition-delay:.1s}
.reveal-delay-2{transition-delay:.2s}
.reveal-delay-3{transition-delay:.2s}
.hover-lift{transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease}
.hover-lift:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(15,23,42,.12)}
.img-zoom{transition:transform .6s ease}
.group:hover .img-zoom{transform:scale(1.06)}
</style>

{{-- HERO --}}
<section class="relative overflow-hidden bg-slate-900 text-white">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-slate-900 to-indigo-950"></div>
    <div class="absolute inset-0 opacity-[0.08]" style="background-image:linear-gradient(white 1px,transparent 1px),linear-gradient(90deg,white 1px,transparent 1px);background-size:36px 36px"></div>
    <div class="absolute -top-28 -right-28 w-[560px] h-[560px] rounded-full bg-accent/25 blur-[80px]" style="animation:float 7s ease-in-out infinite"></div>
    <div class="absolute -bottom-32 -left-28 w-[560px] h-[560px] rounded-full bg-cyan-400/20 blur-[80px]" style="animation:float2 9s ease-in-out infinite"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[900px] h-[420px] rounded-[100px] bg-white/[0.04] blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 py-12 lg:py-16 relative">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div class="reveal active">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/15 rounded-full pl-2 pr-3 py-1.5 text-xs backdrop-blur">
                    <span class="w-6 h-6 rounded-full bg-emerald-400 flex items-center justify-center"><i class="ti ti-sparkles text-[12px] text-slate-900"></i></span>
                    <span class="font-medium">PPDB 2025/2026 Dibuka</span><span class="w-1 h-1 rounded-full bg-white/50"></span><span class="text-slate-300">9 Kompetensi Keahlian</span>
                    <span class="ml-1 w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <h1 class="mt-5 text-[32px] lg:text-[48px] font-bold leading-[0.95] tracking-tight" style="font-family: Plus Jakarta Sans,sans-serif">
                    Mencetak Generasi <span class="bg-gradient-to-r from-sky-300 to-cyan-300 bg-clip-text text-transparent">Vokasi</span> Siap Kerja & Berdaya Saing
                </h1>
                <p class="mt-4 text-slate-300 text-[15px] lg:text-lg leading-relaxed max-w-xl">SMKN 1 Katapang — Negeri Akreditasi A di Katapang, Kab. Bandung. 99 guru profesional, 740 siswa, kurikulum Merdeka + Block, link & match industri Pindad, LEN, Honda.</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('public.programs') }}" class="group px-6 py-3 rounded-xl bg-accent hover:bg-accent-hover text-white font-semibold inline-flex items-center gap-2 transition hover:gap-3">Lihat 9 Jurusan <i class="ti ti-arrow-right transition"></i></a>
                    <a href="{{ route('public.contact') }}" class="px-6 py-3 rounded-xl bg-white text-slate-900 font-semibold inline-flex items-center gap-2 hover:bg-slate-100 transition">Daftar PPDB <i class="ti ti-external-link text-slate-500"></i></a>
                </div>
                <div class="mt-6 grid grid-cols-3 gap-3 max-w-lg">
                    <div class="bg-white/10 border border-white/15 rounded-2xl p-4 text-center backdrop-blur hover:bg-white/15 transition">
                        <div class="text-2xl font-bold">9</div><div class="text-xs text-slate-300">Kompetensi</div><div class="mt-2 h-1 rounded-full bg-white/20 overflow-hidden"><div class="h-full w-[90%] bg-sky-400 rounded-full"></div></div>
                    </div>
                    <div class="bg-white/10 border border-white/15 rounded-2xl p-4 text-center backdrop-blur hover:bg-white/15 transition">
                        <div class="text-2xl font-bold">A</div><div class="text-xs text-slate-300">Akreditasi BAN-SM</div><div class="mt-2 text-[10px] text-emerald-300 font-medium">● Terverifikasi 2018</div>
                    </div>
                    <div class="bg-white/10 border border-white/15 rounded-2xl p-4 text-center backdrop-blur hover:bg-white/15 transition">
                        <div class="text-2xl font-bold">740+</div><div class="text-xs text-slate-300">Siswa Aktif</div><div class="mt-2 text-[10px] text-slate-300">99 guru</div>
                    </div>
                </div>
            </div>
            <div class="relative reveal reveal-delay-1">
                <div class="p-[1.5px] rounded-[28px] bg-gradient-to-br from-white/30 via-white/10 to-transparent">
                    <div class="rounded-[26px] overflow-hidden shadow-2xl border border-white/15 bg-white">
                        <div class="relative overflow-hidden group">
                            <img src="/images/lapang.webp" alt="SMKN 1 Katapang - Lapangan" class="w-full h-[420px] object-cover img-zoom">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-60"></div>
                            <div class="absolute top-3 left-3 bg-white/95 backdrop-blur px-3 py-1.5 rounded-full text-xs font-semibold text-slate-900 flex items-center gap-1.5 shadow"><i class="ti ti-map-pin text-accent"></i> Katapang, Kab. Bandung</div>
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur text-white text-xs border border-white/15">Kampus Vokasi Modern • 740 siswa</span>
                                <span class="w-9 h-9 rounded-full bg-white flex items-center justify-center shadow"><i class="ti ti-photo text-slate-700"></i></span>
                            </div>
                        </div>
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="/images/logo_katapang-removebg-preview.png" class="w-10 h-10 rounded-xl object-cover border" alt="Logo SMKN 1 Katapang">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">SMKN 1 Katapang</div>
                                    <div class="text-xs text-slate-500">Jl. Ceuri Kopo KM 13.5 • NPSN 20206214</div>
                                </div>
                            </div>
                            <span class="hidden sm:inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">Terakreditasi A</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-5 -left-4 sm:-left-6 bg-slate-900 rounded-2xl shadow-xl border border-slate-700 p-3 pr-4 flex items-center gap-3" style="animation:float 5.5s ease-in-out infinite">
                    <div class="w-12 h-12 rounded-xl bg-accent flex items-center justify-center text-white shrink-0"><i class="ti ti-award text-xl"></i></div>
                    <div>
                        <div class="text-sm font-bold leading-none text-white">Akreditasi A</div>
                        <div class="text-xs text-slate-400">BAN-SM No. 1214/BAN-SM/SK/2018</div>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse ml-1 shadow shadow-emerald-400/50"></span>
                </div>
                <div class="absolute -top-3 -right-2 bg-white rounded-xl shadow-lg border border-slate-200 px-3 py-2 flex items-center gap-2 text-xs" style="animation:float2 6.5s ease-in-out infinite">
                    <span class="w-7 h-7 rounded-lg bg-sky-50 text-accent flex items-center justify-center"><i class="ti ti-users"></i></span>
                    <div><div class="font-semibold leading-none">17 Rombel</div><div class="text-slate-500 leading-none">2025/2026</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TRUST BAR --}}
<section class="border-y border-slate-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3 text-xs">
        <span class="font-semibold text-slate-500 tracking-widest">MITRA INDUSTRI</span>
        <div class="flex flex-wrap items-center gap-2 text-slate-600">
            <span class="px-3 py-1.5 rounded-full bg-slate-50 border font-medium">PT Pindad</span>
            <span class="px-3 py-1.5 rounded-full bg-slate-50 border font-medium">PT LEN Industri</span>
            <span class="px-3 py-1.5 rounded-full bg-slate-50 border font-medium">AHASS Honda</span>
            <span class="px-3 py-1.5 rounded-full bg-slate-50 border font-medium">Tekstil & IT Partner</span>
            <span class="hidden sm:inline text-slate-400 ml-2">• PKL 4 bulan kelas XII</span>
        </div>
        <span class="text-slate-500 hidden lg:inline">Link & Match • Sertifikasi LSP P1 • Penyerapan >85%</span>
    </div>
</section>

{{-- Keunggulan --}}
<section class="py-14 bg-slate-50 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image:radial-gradient(#0F172A 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="max-w-7xl mx-auto px-4 relative">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-8 reveal">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-accent"><span class="w-6 h-[1.5px] bg-accent rounded-full"></span> KEUNGGULAN KAMI</div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight mt-2">Kenapa Memilih SMKN 1 Katapang?</h2>
                <p class="text-sm text-slate-500 mt-1">Vokasi 3 tahun + praktik industri, bukan cuma teori.</p>
            </div>
            <a href="{{ route('public.about') }}" class="text-sm font-medium text-accent hover:underline inline-flex items-center gap-1">Tentang sekolah <i class="ti ti-arrow-right text-xs"></i></a>
        </div>
        <div class="grid md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php $features=[
                ['icon'=>'ti-certificate','title'=>'Akreditasi A','desc'=>'Mutu terjamin BAN-SM 2018','iconBg'=>'bg-emerald-50 text-emerald-600'],
                ['icon'=>'ti-users','title'=>'99 Guru Profesional','desc'=>'Tenaga pendidik bersertifikasi','iconBg'=>'bg-blue-50 text-accent'],
                ['icon'=>'ti-building-factory','title'=>'Link & Match Industri','desc'=>'PKL di Pindad, LEN, Honda','iconBg'=>'bg-amber-50 text-amber-600'],
                ['icon'=>'ti-movie','title'=>'Studio Broadcasting','desc'=>'Kamera cinema & editing suite','iconBg'=>'bg-violet-50 text-violet-600'],
                ['icon'=>'ti-briefcase','title'=>'Siap Kerja','desc'=>'3 tahun + sertifikasi kompetensi','iconBg'=>'bg-sky-50 text-sky-600'],
                ['icon'=>'ti-heart','title'=>'Karakter & Disiplin','desc'=>'Profil Pelajar Pancasila','iconBg'=>'bg-rose-50 text-rose-600'],
            ]; @endphp
            @foreach($features as $i=>$f)
            <div class="reveal bg-white rounded-2xl p-5 border border-slate-200 hover-lift" style="transition-delay: {{ $i*70 }}ms">
                <div class="w-10 h-10 rounded-xl {{ $f['iconBg'] }} flex items-center justify-center mb-3"><i class="ti {{ $f['icon'] }} text-lg"></i></div>
                <div class="font-semibold text-sm leading-tight">{{ $f['title'] }}</div>
                <div class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $f['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Program Keahlian --}}
<section class="py-14 bg-white relative">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center max-w-2xl mx-auto mb-10 reveal">
            <div class="inline-flex px-3 py-1 rounded-full bg-accent-soft text-accent text-xs font-semibold border border-blue-100">9 KOMPETENSI KEAHLIAN • SISTEM BLOCK</div>
            <h2 class="mt-3 text-3xl font-bold tracking-tight">Pilih Masa Depanmu di Sini</h2>
            <p class="mt-2 text-slate-500 text-sm leading-relaxed">Kurikulum Merdeka + sistem Block. Jurusan favorit: TKRO, TKJ, RPL & Broadcasting selalu melebihi kuota. Lama belajar 3 tahun, lulus + sertifikasi.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($programs as $i=>$p)
            <div class="reveal group rounded-2xl border border-slate-200 p-5 hover-lift bg-white relative overflow-hidden" style="transition-delay: {{ ($i%3)*80 }}ms">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-accent opacity-0 group-hover:opacity-100 transition"></div>
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center group-hover:bg-accent transition-colors"><i class="ti {{ $p['icon'] }} text-lg"></i></div>
                    <span class="text-xs font-mono px-2 py-1 rounded-lg bg-slate-100 border">{{ $p['code'] }}</span>
                </div>
                <h3 class="mt-4 font-semibold leading-tight">{{ $p['name'] }}</h3>
                <p class="text-sm text-slate-500 mt-1 leading-relaxed min-h-[42px]">{{ $p['desc'] }}</p>
                <a href="{{ route('public.programs') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-accent group-hover:gap-2 transition-all">Pelajari <i class="ti ti-arrow-right text-xs"></i></a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8 reveal">
            <a href="{{ route('public.programs') }}" class="inline-flex px-6 py-3 rounded-xl border border-slate-200 font-medium hover:bg-slate-50 hover:border-slate-300 transition">Lihat detail semua jurusan</a>
        </div>
    </div>
</section>

{{-- Sambutan Kepala Sekolah --}}
<section class="py-14 bg-slate-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-accent/20 via-transparent to-cyan-500/10"></div>
    <div class="absolute right-0 top-0 w-[520px] h-[520px] rounded-full bg-accent/15 blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 relative grid lg:grid-cols-3 gap-8 items-center">
        <div class="lg:col-span-1 reveal">
                <div class="rounded-3xl overflow-hidden border border-white/15 shadow-2xl relative bg-slate-800">
                    <img src="/images/kepsek.png" alt="Kepala Sekolah Hendra Hermansah" class="w-full h-[380px] object-cover">
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-slate-900/80 to-transparent p-4">
                        <div class="inline-flex items-center gap-2 bg-white text-slate-900 px-3 py-1.5 rounded-full text-xs font-semibold"><i class="ti ti-quote text-accent"></i> Kepemimpinan & Karakter</div>
                    </div>
                </div>
        </div>
        <div class="lg:col-span-2 reveal reveal-delay-1">
            <div class="inline-flex items-center gap-2 text-accent font-semibold text-xs tracking-widest"><span class="w-8 h-[2px] bg-accent rounded-full"></span> SAMBUTAN KEPALA SEKOLAH</div>
            <h3 class="text-2xl md:text-3xl font-bold mt-2 leading-tight">Mewujudkan Lulusan Kompeten, Berkarakter & Siap Bersaing di Era Industri 4.0</h3>
            <p class="mt-4 text-slate-300 leading-relaxed text-sm md:text-[15px]">SMKN 1 Katapang berkomitmen menghadirkan pendidikan vokasi berkualitas dengan penguatan soft skills, praktik industri, dan pembelajaran berbasis proyek. Kami menggandeng dunia usaha agar kompetensi siswa relevan dengan kebutuhan kerja nyata.</p>
            <p class="mt-3 text-slate-300 leading-relaxed text-sm md:text-[15px]">Dengan 9 pilihan kompetensi dan fasilitas modern, siswa dibimbing untuk berprestasi akademik, beretika, dan mandiri sesuai Profil Pelajar Pancasila.</p>
                <div class="mt-6 flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-3 pr-4 w-fit backdrop-blur">
                    <img src="/images/kepsek.png" class="w-10 h-10 rounded-full object-cover border border-white/20" alt="Hendra Hermansah">
                    <div>
                        <div class="font-semibold text-sm">Hendra Hermansah, S.Pd., M.M.</div>
                        <div class="text-xs text-slate-400">Kepala SMKN 1 Katapang</div>
                    </div>
                    <span class="ml-2 px-2 py-1 rounded-full bg-white text-slate-900 text-[10px] font-bold">A</span>
                </div>
        </div>
    </div>
</section>

{{-- Berita --}}
<section class="py-14 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 reveal">
            <div>
                <h2 class="text-2xl font-bold">Berita & Agenda Terbaru</h2>
                <p class="text-sm text-slate-500 mt-1">Update PPDB, prestasi & kerja sama industri</p>
            </div>
            <a href="{{ route('public.news') }}" class="hidden sm:inline-flex text-sm font-medium text-accent hover:underline">Lihat semua →</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($news as $i=>$n)
            <article class="reveal bg-white rounded-2xl overflow-hidden border border-slate-200 hover-lift group" style="transition-delay: {{ $i*80 }}ms">
                <div class="overflow-hidden relative">
                    <img src="{{ $n['img'] }}" class="w-full h-44 object-cover img-zoom">
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-white/95 backdrop-blur text-xs font-semibold border shadow-sm">{{ $n['cat'] }}</span>
                </div>
                <div class="p-5">
                    <div class="text-xs text-slate-500">{{ $n['date'] }}</div>
                    <h3 class="font-semibold mt-1 leading-tight line-clamp-2 group-hover:text-accent transition">{{ $n['title'] }}</h3>
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $n['excerpt'] }}</p>
                    <a href="{{ route('public.news') }}" class="mt-3 inline-flex text-sm font-medium text-accent gap-1 items-center">Baca <i class="ti ti-arrow-right text-xs"></i></a>
                </div>
            </article>
            @endforeach
        </div>
        <div class="text-center mt-4 sm:hidden"><a href="{{ route('public.news') }}" class="text-sm font-medium text-accent">Lihat semua →</a></div>
    </div>
</section>

{{-- CTA --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 reveal">
        <div class="rounded-[28px] bg-accent p-[1px]">
        <div class="rounded-[27px] bg-gradient-to-br from-accent to-blue-700 p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(white 1px, transparent 1px);background-size:22px 22px"></div>
            <div class="relative">
                <h3 class="text-2xl font-bold">Siap Bergabung dengan SMKN 1 Katapang?</h3>
                <p class="text-blue-100 mt-1 text-sm md:text-[15px]">Daftar PPDB online atau hubungi panitia untuk konsultasi jurusan sesuai minat & peluang kerja.</p>
                <div class="mt-3 flex items-center gap-2 text-xs text-blue-100"><i class="ti ti-check"></i> 9 kompetensi • Akreditasi A • PKL industri 4 bulan</div>
            </div>
            <div class="flex gap-3 relative shrink-0">
                <a href="{{ route('public.contact') }}" class="px-6 py-3 rounded-xl bg-white text-slate-900 font-semibold hover:bg-slate-100 transition">Hubungi Kami</a>
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-black transition">Sistem Akademik</a>
            </div>
        </div>
        </div>
    </div>
</section>

<script>
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('active')}),{threshold:.15});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
</script>
@endsection
