@extends('layouts.public')
@section('title','Profil SMKN 1 Katapang - Sejarah, Visi Misi & Identitas')
@section('content')
<section class="bg-slate-900 text-white py-12 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-accent/30 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 relative">
        <div class="max-w-3xl">
            <div class="text-xs tracking-widest text-sky-300 font-semibold">PROFIL SEKOLAH</div>
            <h1 class="text-4xl font-bold mt-2 leading-tight" style="font-family: Plus Jakarta Sans,sans-serif">SMK Negeri 1 Katapang</h1>
            <p class="text-slate-300 mt-3">Sekolah Menengah Kejuruan Negeri di lingkungan Dinas Pendidikan Provinsi Jawa Barat. Berdiri 17 November 2000, SK Pendirian 217/O/2000.</p>
        </div>
    </div>
</section>

<section class="py-10">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h2 class="text-xl font-bold flex items-center gap-2"><i class="ti ti-history text-accent"></i> Sejarah & Pendirian</h2>
                <div class="mt-4 space-y-3 text-sm leading-relaxed text-slate-600">
                    <p>SMKN 1 Katapang didirikan pada tahun <b>1999</b> dengan nama awal <b>SMKN 4 Soreang</b>. Pendirian didukung dana proyek LOAN OECF dari Pemerintah Jepang untuk pembangunan Unit Gedung Baru (UGB).</p>
                    <p>Tahun pelajaran 1999/2000 menerima siswa baru untuk 3 program: Teknologi Penyempurnaan Tekstil, Teknik Elektro, dan Mesin Perkakas. Pada 2000/2001 menempati gedung baru di <b>Jl. Ceuri Terusan Kopo KM 13,5, Desa Katapang</b> dan berganti nama menjadi SMKN 4 Katapang, lalu resmi menjadi <b>SMKN 1 Katapang</b> akhir tahun 2000.</p>
                    <p>Hari ini sekolah memiliki 740 siswa dibimbing 99 guru profesional dan telah mengantongi <b>Akreditasi A (31 Des 2018, No.1214/BAN-SM/SK/2018)</b> serta sertifikasi <b>ISO 9001:2008</b>.</p>
                </div>
                <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-xl bg-slate-50 border p-3"><div class="text-lg font-bold">1999</div><div class="text-xs text-slate-500">Berdiri</div></div>
                    <div class="rounded-xl bg-slate-50 border p-3"><div class="text-lg font-bold">2000</div><div class="text-xs text-slate-500">Menjadi SMKN 1 Katapang</div></div>
                    <div class="rounded-xl bg-slate-50 border p-3"><div class="text-lg font-bold">A</div><div class="text-xs text-slate-500">Akreditasi</div></div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-accent text-white rounded-2xl p-6">
                    <h3 class="font-bold flex items-center gap-2"><i class="ti ti-target"></i> Visi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-blue-50">Mewujudkan lulusan yang kompeten, berkarakter, berwawasan lingkungan, dan berdaya saing global sesuai kebutuhan dunia kerja & industri.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <h3 class="font-bold flex items-center gap-2"><i class="ti ti-list-check"></i> Misi</h3>
                    <ul class="mt-2 space-y-1.5 text-sm text-slate-600 list-disc pl-5">
                        <li>Menyelenggarakan pendidikan vokasi mutu & link-match industri</li>
                        <li>Mengembangkan kurikulum Merdeka & pembelajaran Block</li>
                        <li>Penguatan karakter Profil Pelajar Pancasila</li>
                        <li>Meningkatkan sarana praktik & sertifikasi kompetensi</li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h3 class="font-bold">Nilai & Budaya Sekolah</h3>
                <div class="grid sm:grid-cols-3 gap-3 mt-4">
                    @php $vals=[['Disiplin','ti-clock'],['Kreatif','ti-bulb'],['Mandiri','ti-user-check'],['Gotong Royong','ti-users-group'],['Bernalar Kritis','ti-brain'],['Beriman','ti-heart']]; @endphp
                    @foreach($vals as $v)
                    <div class="rounded-xl bg-slate-50 border p-4 text-center"><i class="ti {{ $v[1] }} text-accent text-xl"></i><div class="text-sm font-medium mt-1">{{ $v[0] }}</div></div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h3 class="font-bold">Identitas Sekolah</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">NPSN</dt><dd class="font-mono font-medium">20206214</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Status</dt><dd class="font-medium">Negeri</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Jenjang</dt><dd>SMK - Dikmen</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Akreditasi</dt><dd><span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">A</span></dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Kepala Sekolah</dt><dd class="text-right">Hendra Hermansah, M.M.</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Operator</dt><dd>Yahmin Barokah</dd></div>
                </dl>
                <div class="mt-4 p-3 rounded-xl bg-accent-soft border border-blue-100 text-xs text-slate-600">
                    <div class="font-semibold text-accent">Alamat Lengkap</div>
                    Jl. Ceuri Terusan Kopo KM 13.5 RT 01 RW 14, Katapang, Kec. Katapang, Kab. Bandung, Jawa Barat 40921
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h3 class="font-bold">Pimpinan dari Masa ke Masa</h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li>• Drs. Carman Rahmat, M.Pd</li>
                    <li>• Drs. H. Asep Rusmana, M.M.Pd</li>
                    <li>• Dra. Hj. Adah Rodiyah, M.M.Pd</li>
                    <li>• Drs. Agus Rukmantara, M.M</li>
                    <li>• Dra. Ety Mulyati, M.Pd</li>
                    <li>• Hendra Hermansah, S.Pd., M.M. <span class="text-accent">(Saat ini)</span></li>
                </ul>
            </div>

            <div class="rounded-2xl overflow-hidden border">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=700" class="w-full h-48 object-cover">
                <div class="p-4 bg-white">
                    <div class="font-semibold">Lokasi Strategis</div>
                    <p class="text-xs text-slate-500">Akses mudah dari Kopo - Katapang, dekat kawasan industri Bandung Selatan.</p>
                    <a href="{{ route('public.contact') }}" class="mt-2 inline-flex text-sm text-accent font-medium">Lihat peta →</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
