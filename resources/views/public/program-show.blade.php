@extends('layouts.public')
@section('title', $program['name'].' - SMKN 1 Katapang')
@section('content')
<section class="bg-slate-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 via-transparent to-indigo-900/30"></div>
    <div class="absolute -right-20 -top-20 w-[420px] h-[420px] rounded-full bg-accent/20 blur-3xl"></div>
    <div class="max-w-7xl mx-auto px-4 py-10 relative">
        <a href="{{ route('public.programs') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-300 hover:text-white mb-4"><i class="ti ti-arrow-left text-xs"></i> Kembali ke Program Keahlian</a>
        <div class="flex flex-wrap items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white text-slate-900 flex items-center justify-center shadow"><i class="ti {{ $program['icon'] }} text-2xl"></i></div>
            <div class="flex-1 min-w-[240px]">
                <div class="text-xs tracking-widest text-sky-300 font-semibold">{{ $program['short'] }} • {{ $program['tagline'] }}</div>
                <h1 class="text-3xl md:text-4xl font-bold mt-1 leading-tight" style="font-family: Plus Jakarta Sans,sans-serif">{{ $program['name'] }}</h1>
                <p class="text-slate-300 mt-2 max-w-2xl text-sm leading-relaxed">{{ $program['desc'] }}</p>
                <div class="mt-3 inline-flex items-center gap-2 text-xs bg-white/10 border border-white/15 rounded-full px-3 py-1.5 backdrop-blur"><i class="ti ti-clock"></i> {{ $program['duration'] }}</div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('public.contact') }}" class="px-5 py-2.5 rounded-xl bg-accent hover:bg-accent-hover text-white text-sm font-semibold">Daftar PPDB</a>
                <a href="{{ route('public.contact') }}" class="px-5 py-2.5 rounded-xl bg-white text-slate-900 text-sm font-semibold hover:bg-slate-100">Konsultasi</a>
            </div>
        </div>
    </div>
</section>

<section class="py-8 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border p-6">
                <h2 class="font-bold text-lg flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-accent-soft text-accent flex items-center justify-center"><i class="ti ti-book"></i></span> Tentang Jurusan</h2>
                <div class="mt-3 space-y-3 text-sm leading-relaxed text-slate-600">
                    @foreach($program['about'] as $para)
                    <p>{{ $para }}</p>
                    @endforeach
                </div>
            </div>

            @if(!empty($program['concentrations']))
            <div class="bg-white rounded-2xl border p-6">
                <h2 class="font-bold flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center"><i class="ti ti-layers-intersect text-sm"></i></span> Konsentrasi Keahlian</h2>
                <div class="mt-4 grid sm:grid-cols-{{ count($program['concentrations']) > 1 ? '2' : '1' }} gap-4">
                    @foreach($program['concentrations'] as $c)
                    <div class="rounded-xl border bg-slate-50 p-4">
                        <div class="font-semibold text-sm">{{ $c['title'] }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $c['desc'] }}</div>
                        <ul class="mt-3 space-y-1.5 text-xs text-slate-600">
                            @foreach($c['points'] as $pt)
                            <li class="flex gap-1.5"><i class="ti ti-check text-emerald-600 mt-0.5"></i> {{ $pt }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-accent text-white rounded-2xl p-6">
                    <h3 class="font-bold flex items-center gap-2"><i class="ti ti-target"></i> Visi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-blue-50">{{ $program['vision'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border p-6">
                    <h3 class="font-bold flex items-center gap-2"><i class="ti ti-list-check text-accent"></i> Misi</h3>
                    <ul class="mt-2 space-y-1.5 text-sm text-slate-600 list-disc pl-5">
                        @foreach($program['missions'] as $m)
                        <li>{{ $m }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-bold flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ti ti-briefcase text-sm"></i></span> Prospek Karir</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($program['careers'] as $career)
                    <span class="px-3 py-1.5 rounded-full bg-slate-50 border text-xs font-medium">{{ $career }}</span>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500 mt-3">Lulusan juga dapat melanjutkan ke perguruan tinggi (PTN/PTS) atau berwirausaha sesuai bidang.</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-bold">Info Singkat</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Kode</dt><dd class="font-mono font-medium">{{ $program['code'] }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Singkatan</dt><dd class="font-medium">{{ $program['short'] }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Lama Belajar</dt><dd>3 Tahun</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">PKL</dt><dd>4 bulan (Kelas XII)</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Sertifikasi</dt><dd class="text-right text-xs">LSP P1 & Industri</dd></div>
                </dl>
                <a href="{{ route('public.contact') }}" class="mt-5 block w-full text-center px-4 py-2.5 rounded-xl bg-accent text-white text-sm font-medium hover:bg-accent-hover">Hubungi Panitia PPDB</a>
                <a href="{{ route('public.programs') }}" class="mt-2 block w-full text-center px-4 py-2.5 rounded-xl border text-sm font-medium hover:bg-slate-50">Lihat 9 Jurusan Lain</a>
            </div>

            <div class="bg-slate-900 text-white rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-accent/20 blur-2xl"></div>
                <h4 class="font-bold relative">Masih bingung pilih jurusan?</h4>
                <p class="text-sm text-slate-300 mt-1 relative">Konsultasi minat & peluang kerja gratis. Cocokkan dengan Pindad, LEN, Honda, atau industri kreatif.</p>
                <a href="{{ route('public.contact') }}" class="mt-4 inline-flex px-4 py-2 rounded-xl bg-white text-slate-900 text-sm font-semibold relative">Konsultasi Gratis</a>
            </div>

            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-bold text-sm">Jurusan Lain</h3>
                <div class="mt-3 space-y-2">
                    @foreach(array_slice($others,0,6) as $o)
                    <a href="{{ route('public.programs.show', $o['code']) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition">
                        <span class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0"><i class="ti {{ $o['icon'] }} text-sm"></i></span>
                        <div class="min-w-0">
                            <div class="text-sm font-medium leading-tight truncate">{{ $o['name'] }}</div>
                            <div class="text-xs text-slate-500">{{ $o['code'] }}</div>
                        </div>
                        <i class="ti ti-chevron-right text-slate-400 ml-auto"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
