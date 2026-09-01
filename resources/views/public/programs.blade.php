@extends('layouts.public')
@section('title','Program Keahlian SMKN 1 Katapang - 9 Kompetensi')
@section('content')
<section class="bg-slate-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-xs tracking-widest text-sky-300 font-semibold">KOMPETENSI KEAHLIAN</div>
        <h1 class="text-3xl font-bold mt-2">9 Program Keahlian</h1>
        <p class="text-slate-300 mt-2 max-w-2xl">Sistem pembelajaran Block dalam 5 Program Keahlian. Lama belajar 3 tahun, lulus siap kerja dengan sertifikasi kompetensi & PKL industri.</p>
    </div>
</section>

<section class="py-10 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap gap-2 mb-6">
            <span class="px-3 py-1 rounded-full bg-white border text-xs">Teknik Mesin : 100 siswa</span>
            <span class="px-3 py-1 rounded-full bg-white border text-xs">Teknik Otomotif : 90 siswa</span>
            <span class="px-3 py-1 rounded-full bg-white border text-xs">Teknik Elektronika : 124 siswa</span>
            <span class="px-3 py-1 rounded-full bg-white border text-xs">Teknik Tekstil : 68 siswa</span>
            <span class="px-3 py-1 rounded-full bg-white border text-xs">PPLG : 72 siswa</span>
            <span class="px-3 py-1 rounded-full bg-white border text-xs">TJKT : 71 siswa</span>
            <span class="px-3 py-1 rounded-full bg-white border text-xs">Broadcasting : 68 siswa</span>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($programs as $p)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition">
                <div class="h-2 bg-accent"></div>
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center"><i class="ti {{ $p['icon'] }} text-xl"></i></div>
                        <div>
                            <div class="text-xs font-mono text-slate-500">{{ $p['code'] }}</div>
                            <h3 class="font-bold leading-tight">{{ $p['name'] }}</h3>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mt-3 leading-relaxed">{{ $p['desc'] }}</p>
                    <ul class="mt-4 space-y-1.5 text-xs text-slate-500">
                        @php
                        $details = [
                            'EIND'=> ['PLC & Mikrokontroler','Sensor & Aktuator','IoT Industri'],
                            'TPM'=> ['CNC Milling & Bubut','Metrologi Industri','CAD/CAM'],
                            'TGM'=> ['AutoCAD & Inventor','Gambar Manufaktur','Desain 3D'],
                            'TKRO'=> ['Engine EFI & Hybrid','Chassis & Kelistrikan','Diagnostik'],
                            'TEKS'=> ['Pencelupan & Printing','Quality Control','Manajemen Produksi'],
                            'TKJ'=> ['Fiber Optic & Mikrotik','Server & Cloud','Cyber Security'],
                            'RPL'=> ['Web & Mobile Dev','Database & API','UI/UX'],
                            'MM'=> ['Desain Grafis','Videografi & Animasi','Broadcasting'],
                            'MKA'=> ['Robotika','Pneumatik & PLC','Automasi'],
                        ];
                        @endphp
                        @foreach($details[$p['code']] ?? ['Kurikulum Merdeka','PKL Industri','Sertifikasi'] as $d)
                        <li class="flex gap-1.5"><i class="ti ti-check text-emerald-500 mt-0.5"></i> {{ $d }}</li>
                        @endforeach
                    </ul>
                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('public.contact') }}" class="flex-1 text-center px-3 py-2 rounded-xl bg-accent text-white text-sm font-medium">Daftar</a>
                        <a href="#" class="px-3 py-2 rounded-xl border text-sm">Kurikulum</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10 bg-white rounded-2xl border p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-bold">Bingung pilih jurusan?</h4>
                <p class="text-sm text-slate-500">Konsultasi minat & peluang kerja (Pindad → Mesin, LEN → Elektronika, Honda → TKRO)</p>
            </div>
            <a href="{{ route('public.contact') }}" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-medium">Konsultasi PPDB Gratis</a>
        </div>
    </div>
</section>
@endsection
