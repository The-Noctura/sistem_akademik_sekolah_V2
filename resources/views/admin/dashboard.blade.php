@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Selamat datang, {{ auth()->user()->nama }}</h1>
            <p class="text-slate-500 mt-1">Kelola data akademik sekolah dari dashboard ini</p>
        </div>

        <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 rounded-2xl p-6 sm:p-8 text-white mb-8 shadow-lg shadow-blue-900/10">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-xs font-semibold uppercase tracking-wider text-blue-100 mb-3">
                    <i class="ti ti-shield-check text-sm"></i>
                    Administrator Control Center
                </div>

                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->nama }}!</h2>
                <p class="text-blue-100/80 mt-1 max-w-2xl text-sm sm:text-base">
                    Kelola data akademik, penugasan mengajar, jadwal pelajaran, produk TEFA jurusan, dan publikasi berita sekolah dari satu panel kendali.
                </p>

                <div class="flex flex-wrap gap-2.5 mt-5">
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white text-slate-800 text-xs font-semibold hover:bg-blue-50 transition-all shadow-sm">
                        <i class="ti ti-user-plus text-base text-accent"></i>
                        Tambah User
                    </a>
                    <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/15 backdrop-blur-md border border-white/20 text-white text-xs font-semibold hover:bg-white/25 transition-all">
                        <i class="ti ti-pencil-plus text-base"></i>
                        Tulis Berita
                    </a>
                    <a href="{{ route('admin.tefa.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/15 backdrop-blur-md border border-white/20 text-white text-xs font-semibold hover:bg-white/25 transition-all">
                        <i class="ti ti-shopping-bag-plus text-base text-emerald-300"></i>
                        Produk TEFA
                    </a>
                    <a href="{{ route('admin.jadwal.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/15 backdrop-blur-md border border-white/20 text-white text-xs font-semibold hover:bg-white/25 transition-all">
                        <i class="ti ti-calendar-plus text-base text-amber-300"></i>
                        Atur Jadwal
                    </a>
                </div>
            </div>

            <div class="absolute -right-8 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
            <x-card class="bg-white p-4 border border-slate-200/80 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Total Guru</p>
                        <p class="text-2xl font-bold text-accent mt-1">{{ $stats['guru_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="ti ti-users text-xl text-blue-600"></i>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-white p-4 border border-slate-200/80 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Total Siswa</p>
                        <p class="text-2xl font-bold text-accent mt-1">{{ $stats['siswa_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <i class="ti ti-user text-xl text-emerald-600"></i>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-white p-4 border border-slate-200/80 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Total Kelas</p>
                        <p class="text-2xl font-bold text-accent mt-1">{{ $stats['kelas_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="ti ti-school text-xl text-amber-600"></i>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-white p-4 border border-slate-200/80 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Mata Pelajaran</p>
                        <p class="text-2xl font-bold text-accent mt-1">{{ $stats['mapel_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center">
                        <i class="ti ti-book text-xl text-violet-600"></i>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-white p-4 border border-slate-200/80 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Produk TEFA</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['tefa_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <i class="ti ti-shopping-bag text-lg"></i>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-white p-4 border border-slate-200/80 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Berita</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['berita_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="ti ti-news text-lg"></i>
                    </div>
                </div>
            </x-card>
        </div>

        <div>
            <h2 class="font-semibold mb-4">Menu Utama</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-menu-card href="{{ route('admin.users.index') }}" icon="users" title="Manajemen User" description="Kelola akun guru dan siswa" />
                <x-menu-card href="{{ route('admin.kelas.index') }}" icon="school" title="Manajemen Kelas" description="Kelola data kelas dan wali kelas" />
                <x-menu-card href="{{ route('admin.mapel.index') }}" icon="book" title="Manajemen Mapel" description="Kelola daftar mata pelajaran" />
                <x-menu-card href="{{ route('admin.mengajar.index') }}" icon="link" title="Manajemen Mengajar" description="Hubungkan guru, mapel, dan kelas" />
                <x-menu-card href="{{ route('admin.jadwal.index') }}" icon="calendar" title="Manajemen Jadwal" description="Atur jadwal pelajaran per kelas" />
                <x-menu-card href="{{ route('admin.tefa.index') }}" icon="shopping-bag" title="Produk TEFA" description="Kelola katalog dan produk jurusan" />
                <x-menu-card href="{{ route('admin.berita.index') }}" icon="news" title="Berita" description="Kelola konten dan pengumuman sekolah" />
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <x-card class="border border-slate-200/80 p-0 overflow-hidden shadow-sm bg-white">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="ti ti-history text-base"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-sm text-slate-900">Audit Trail: Log Perubahan Nilai Terkini</h2>
                                <p class="text-[11px] text-slate-500">Pencatatan otomatis via MySQL Trigger saat nilai siswa diedit</p>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($recentLogs as $log)
                            @php
                                $lama = json_decode($log->data_lama, true);
                                $baru = json_decode($log->data_baru, true);
                            @endphp
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        <i class="ti ti-edit"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-800">
                                            Perubahan Nilai oleh <span class="text-indigo-600">{{ $log->user_nama }}</span>
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            Record #{{ $log->record_id }} &bull; {{ date('d M Y, H:i', strtotime($log->waktu)) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-mono line-through">
                                        {{ $lama['nilai'] ?? '-' }}
                                    </span>
                                    <i class="ti ti-arrow-right text-slate-400 text-xs"></i>
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-mono font-bold">
                                        {{ $baru['nilai'] ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-xs">
                                <i class="ti ti-clipboard-check text-2xl text-slate-300 block mb-1"></i>
                                Belum ada riwayat perubahan nilai yang tercatat.
                            </div>
                        @endforelse
                    </div>
                </x-card>

                <div>
                    <h3 class="font-bold text-sm text-slate-800 mb-3 flex items-center gap-1.5">
                        <i class="ti ti-grid-dots text-accent"></i>
                        Modul Manajemen Master Data
                    </h3>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <a href="{{ route('admin.users.index') }}" class="p-4 rounded-xl bg-white border border-slate-200/80 hover:border-accent hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-3 group-hover:bg-accent group-hover:text-white transition-colors">
                                <i class="ti ti-users text-lg"></i>
                            </div>
                            <h4 class="font-bold text-xs text-slate-800">Manajemen User</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">Akun admin, guru, & siswa</p>
                        </a>

                        <a href="{{ route('admin.kelas.index') }}" class="p-4 rounded-xl bg-white border border-slate-200/80 hover:border-amber-500 hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-3 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <i class="ti ti-school text-lg"></i>
                            </div>
                            <h4 class="font-bold text-xs text-slate-800">Data Rombel Kelas</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">Tingkat & penentuan wali kelas</p>
                        </a>

                        <a href="{{ route('admin.mapel.index') }}" class="p-4 rounded-xl bg-white border border-slate-200/80 hover:border-violet-500 hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center mb-3 group-hover:bg-violet-500 group-hover:text-white transition-colors">
                                <i class="ti ti-book text-lg"></i>
                            </div>
                            <h4 class="font-bold text-xs text-slate-800">Mata Pelajaran</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">Daftar kurikulum pelajaran</p>
                        </a>

                        <a href="{{ route('admin.mengajar.index') }}" class="p-4 rounded-xl bg-white border border-slate-200/80 hover:border-emerald-500 hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                <i class="ti ti-link text-lg"></i>
                            </div>
                            <h4 class="font-bold text-xs text-slate-800">Penugasan Mengajar</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">Hubungkan Guru + Mapel + Kelas</p>
                        </a>

                        <a href="{{ route('admin.jadwal.index') }}" class="p-4 rounded-xl bg-white border border-slate-200/80 hover:border-cyan-500 hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center mb-3 group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                                <i class="ti ti-calendar text-lg"></i>
                            </div>
                            <h4 class="font-bold text-xs text-slate-800">Jadwal Pelajaran</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">Atur hari, jam, & ruangan</p>
                        </a>

                        <a href="{{ route('admin.tefa.index') }}" class="p-4 rounded-xl bg-white border border-slate-200/80 hover:border-teal-500 hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-3 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                                <i class="ti ti-shopping-bag text-lg"></i>
                            </div>
                            <h4 class="font-bold text-xs text-slate-800">Produk TEFA</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">Katalog karya jurusan SMK</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <x-card class="bg-white border border-slate-200/80 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                            <i class="ti ti-news text-rose-600"></i>
                            Berita Terkini
                        </h3>
                        <a href="{{ route('admin.berita.index') }}" class="text-xs text-accent font-semibold hover:underline">
                            Kelola Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentNews as $item)
                            <a href="{{ route('admin.berita.edit', $item) }}" class="block p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700">
                                        {{ $item->kategori }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $item->created_at->format('d M Y') }}</span>
                                </div>
                                <h4 class="font-bold text-xs text-slate-800 line-clamp-1">{{ $item->judul }}</h4>
                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $item->ringkasan ?? Str::limit(strip_tags($item->konten), 80) }}</p>
                            </a>
                        @empty
                            <div class="text-center py-6 text-slate-400 text-xs">
                                <p>Belum ada berita yang diterbitkan.</p>
                                <a href="{{ route('admin.berita.create') }}" class="inline-block mt-2 text-accent font-semibold hover:underline">
                                    + Tulis Berita Pertama
                                </a>
                            </div>
                        @endforelse
                    </div>
                </x-card>

                <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white shadow-sm">
                    <div class="flex items-center gap-2 text-amber-400 mb-2 font-semibold text-xs">
                        <i class="ti ti-bulb text-base"></i>
                        Tips Urutan Alur SIAKAD
                    </div>
                    <ol class="text-xs text-slate-300 space-y-1.5 list-decimal list-inside leading-relaxed">
                        <li>Buat data <b>Guru & Siswa</b> di Manajemen User.</li>
                        <li>Buat data <b>Kelas</b> dan tentukan Wali Kelas.</li>
                        <li>Tentukan <b>Penugasan Mengajar</b> (Guru+Mapel+Kelas).</li>
                        <li>Atur <b>Jadwal Pelajaran</b> per penugasan mengajar.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
