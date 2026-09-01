@extends('layouts.public')
@section('title','Fasilitas SMKN 1 Katapang')
@section('content')
<section class="bg-slate-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-xs tracking-widest text-sky-300 font-semibold">FASILITAS & EKSTRAKURIKULER</div>
        <h1 class="text-3xl font-bold mt-2">Belajar Nyaman, Praktik Maksimal</h1>
        <p class="text-slate-300 mt-2">Lab modern, bengkel, studio & lapangan untuk dukung 9 kompetensi.</p>
    </div>
</section>
<section class="py-10">
    <div class="max-w-7xl mx-auto px-4 space-y-10">
        <div>
            <h2 class="font-bold text-xl">Fasilitas Utama</h2>
            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                @php $facs=[
                    ['Laboratorium TKJ & RPL','ti-server','Jaringan fiber, server, lab coding'],
                    ['Bengkel TKRO','ti-car','Lift, engine trainer, diagnostic tool'],
                    ['Lab Elektronika & Mekatronika','ti-cpu','PLC, mikrokontroler, robotik'],
                    ['Workshop Pemesinan','ti-settings','Mesin bubut, milling, CNC'],
                    ['Studio Multimedia','ti-photo','Kamera, lighting, editing suite'],
                    ['Lab Tekstil','ti-shirt','Mesin penyempurnaan & QC'],
                    ['Perpustakaan','ti-books','Koleksi teknik & digital'],
                    ['Lapangan & Sport','ti-ball-football','Futsal, basket, upacara'],
                ]; @endphp
                @foreach($facs as $f)
                <div class="rounded-2xl border p-5 bg-white">
                    <div class="w-10 h-10 rounded-xl bg-accent-soft flex items-center justify-center text-accent"><i class="ti {{ $f[1] }}"></i></div>
                    <div class="font-semibold mt-3 text-sm">{{ $f[0] }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $f[2] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border overflow-hidden bg-white">
                <img src="https://images.unsplash.com/photo-1588075592446-265fd1e6e76f?w=800" class="w-full h-56 object-cover">
                <div class="p-6">
                    <h3 class="font-bold">Praktik Kerja Industri (PKL)</h3>
                    <p class="text-sm text-slate-600 mt-2">Kelas XI melaksanakan PKL 6 bulan di industri mitra (PT Pindad, PT LEN Industri, AHASS Honda, perusahaan tekstil & IT). Pembimbing industri + guru pendamping.</p>
                </div>
            </div>
            <div class="rounded-2xl border p-6 bg-slate-50">
                <h3 class="font-bold">Ekstrakurikuler</h3>
                <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                    <div class="bg-white rounded-xl border p-3 flex items-center gap-2"><i class="ti ti-flag text-accent"></i> Paskibra & Pramuka</div>
                    <div class="bg-white rounded-xl border p-3 flex items-center gap-2"><i class="ti ti-music text-accent"></i> Seni & Band</div>
                    <div class="bg-white rounded-xl border p-3 flex items-center gap-2"><i class="ti ti-ball-football text-accent"></i> Futsal & Basket</div>
                    <div class="bg-white rounded-xl border p-3 flex items-center gap-2"><i class="ti ti-code text-accent"></i> Coding Club</div>
                    <div class="bg-white rounded-xl border p-3 flex items-center gap-2"><i class="ti ti-layers-intersect text-accent"></i> Robotik</div>
                    <div class="bg-white rounded-xl border p-3 flex items-center gap-2"><i class="ti ti-book text-accent"></i> Rohis & PMR</div>
                </div>
                <div class="mt-6 rounded-xl bg-accent text-white p-4">
                    <div class="font-semibold">Prestasi</div>
                    <p class="text-sm text-blue-100 mt-1">Juara LKS Tingkat Kab./Prov., sertifikasi LSP P1, dan penyerapan lulusan >85% bekerja / kuliah / wirausaha.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
