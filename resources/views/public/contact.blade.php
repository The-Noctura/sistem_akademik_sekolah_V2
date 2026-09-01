@extends('layouts.public')
@section('title','Kontak SMKN 1 Katapang')
@section('content')
<section class="bg-slate-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-xs tracking-widest text-sky-300 font-semibold">KONTAK KAMI</div>
        <h1 class="text-3xl font-bold mt-2">Hubungi SMKN 1 Katapang</h1>
        <p class="text-slate-300 mt-2">PPDB, kerjasama industri, atau informasi akademik.</p>
    </div>
</section>
<section class="py-10">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-3 gap-6">
        <div class="space-y-4">
            <div class="rounded-2xl border p-5 bg-white">
                <h3 class="font-bold">Alamat Sekolah</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">Jl. Ceuri Terusan Kopo KM 13.5 RT 01 RW 14<br>Desa Katapang, Kec. Katapang<br>Kab. Bandung, Jawa Barat 40921</p>
                <div class="mt-3 flex items-center gap-2 text-sm"><i class="ti ti-phone text-accent"></i> (022) 5893737</div>
                <div class="flex items-center gap-2 text-sm"><i class="ti ti-mail text-accent"></i> smkn1katapang@yahoo.co.id</div>
                <div class="flex items-center gap-2 text-sm"><i class="ti ti-world text-accent"></i> www.smkn1katapang.sch.id</div>
            </div>
            <div class="rounded-2xl bg-accent text-white p-5">
                <h4 class="font-semibold">Jam Operasional</h4>
                <p class="text-sm text-blue-100 mt-1">Senin - Jumat: 07.00 - 15.00 WIB<br>Sabtu: 07.00 - 12.00 (Ekstra)</p>
            </div>
            <div class="rounded-2xl border p-5 bg-white">
                <h4 class="font-semibold">Akses Cepat</h4>
                <a href="{{ route('login') }}" class="mt-3 block text-center px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium">Login Sistem Akademik</a>
                <p class="text-xs text-slate-500 mt-2 text-center">Admin / Guru / Siswa gunakan akun yang diberikan sekolah</p>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-2xl border bg-white p-6">
                <h3 class="font-bold text-lg">Kirim Pesan</h3>
                <p class="text-sm text-slate-500">Form demo (belum terkoneksi email). Untuk kebutuhan nyata, hubungkan ke controller + mail.</p>
                <form class="mt-6 space-y-4" onsubmit="event.preventDefault(); alert('Terima kasih! Pesan demo terkirim.');">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Nama</label>
                            <input class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="Nama lengkap" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Email / HP</label>
                            <input class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="email / 08xx" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Keperluan</label>
                        <select class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">
                            <option>PPDB 2025/2026</option>
                            <option>Kerjasama Industri / PKL</option>
                            <option>Informasi Akademik</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Pesan</label>
                        <textarea rows="4" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="Tulis pesan..."></textarea>
                    </div>
                    <button class="w-full py-3 rounded-xl bg-accent text-white font-semibold hover:bg-accent-hover">Kirim Pesan</button>
                </form>
            </div>

            <div class="mt-6 rounded-2xl overflow-hidden border">
                <iframe class="w-full h-[300px]" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=SMKN%201%20Katapang%20Jl%20Ceuri%20Terusan%20Kopo%20KM%2013.5&z=15&output=embed"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection
