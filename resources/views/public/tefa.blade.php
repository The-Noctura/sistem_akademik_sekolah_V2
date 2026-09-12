@extends('layouts.public')

@section('title', 'Katalog Produk TEFA - SMKN 1 Katapang')

@section('content')
    <!-- Hero Banner TEFA -->
    <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-20">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-950 via-slate-900 to-indigo-950 opacity-95"></div>
        <div class="absolute inset-0 opacity-[0.06]" style="background-image:linear-gradient(white 1px,transparent 1px),linear-gradient(90deg,white 1px,transparent 1px);background-size:32px 32px"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-4">
                <i class="ti ti-shopping-bag text-sm"></i> Teaching Factory (TEFA) SMKN 1 Katapang
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight max-w-3xl mx-auto leading-tight">
                Karya & Jasa Kejuruan <span class="bg-gradient-to-r from-emerald-300 to-teal-200 bg-clip-text text-transparent">Standar Industri</span>
            </h1>
            <p class="text-slate-300 mt-4 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Produk fisik berkualitas dan layanan jasa profesional hasil karya siswa di bawah bimbingan guru kejuruan serta mitra industri. Dapatkan langsung via pemesanan WhatsApp resmi sekolah.
            </p>
        </div>
    </section>

    <!-- Filter & Katalog Produk -->
    <section class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- Filter Bar Jurusan -->
            <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-8 scrollbar-none">
                <a href="{{ route('public.tefa') }}" 
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all shadow-sm {{ empty($selectedJurusan) ? 'bg-emerald-600 text-white shadow-emerald-600/20' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Semua Produk & Jasa
                </a>
                @foreach($jurusanList as $code => $name)
                    <a href="{{ route('public.tefa', ['jurusan' => $code]) }}" 
                       class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all shadow-sm {{ $selectedJurusan === $code ? 'bg-emerald-600 text-white shadow-emerald-600/20' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                        {{ $code }} &bull; {{ $name }}
                    </a>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $p)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                        <div>
                            <!-- Thumbnail with Badges -->
                            <div class="relative h-52 bg-slate-100 overflow-hidden">
                                @if($p->foto)
                                    <img src="{{ str_starts_with($p->foto, 'http') ? $p->foto : asset('storage/' . $p->foto) }}" 
                                         alt="{{ $p->nama_produk }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                                        <i class="ti ti-photo-off text-3xl mb-1"></i>
                                        <span class="text-xs">Foto belum tersedia</span>
                                    </div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex gap-1.5">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-slate-900/85 text-white backdrop-blur-md">
                                        {{ $p->jurusan_code }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-500 text-white shadow-sm">
                                        {{ $p->kategori }}
                                    </span>
                                </div>

                                @if($p->status_stok === 'habis')
                                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center">
                                        <span class="px-3 py-1 rounded-full bg-rose-600 text-white text-xs font-bold uppercase tracking-wider shadow">
                                            Habis / Pre-Order
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Content Details -->
                            <div class="p-5">
                                <span class="text-xs font-semibold text-emerald-600 mb-1 block">
                                    {{ $p->jurusan_name }}
                                </span>
                                <h3 class="font-bold text-base text-slate-900 line-clamp-1 group-hover:text-emerald-600 transition-colors">
                                    {{ $p->nama_produk }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                    {{ $p->deskripsi ?? 'Produk Teaching Factory resmi buatan siswa SMKN 1 Katapang.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Price & WhatsApp Action -->
                        <div class="p-5 pt-0 border-t border-slate-100 mt-2">
                            <div class="flex items-center justify-between my-3">
                                <span class="text-xs text-slate-400">Harga Estimasi</span>
                                <span class="text-lg font-extrabold text-slate-900 font-mono">
                                    {{ $p->formatted_harga }}
                                </span>
                            </div>

                            <a href="{{ $p->whatsapp_url }}" target="_blank" 
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold transition-all shadow-md shadow-emerald-600/20 active:scale-[0.98]">
                                <i class="ti ti-brand-whatsapp text-lg"></i>
                                Pesan via WhatsApp
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                        <i class="ti ti-shopping-bag-x text-5xl text-slate-300 block mb-2"></i>
                        <h3 class="font-bold text-slate-800 text-base">Belum Ada Produk Ditampilkan</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                            Katalog TEFA jurusan {{ $selectedJurusan ?? 'ini' }} sedang dalam persiapan produksi. Silakan hubungi kontak sekolah untuk informasi pemesanan khusus.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>

            <!-- Panduan Order TEFA -->
            <div class="mt-16 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="max-w-3xl mx-auto text-center">
                    <h3 class="text-lg font-bold text-slate-900">Cara Pemesanan Produk / Jasa Teaching Factory</h3>
                    <p class="text-xs text-slate-500 mt-1">Layanan profesional terstandarisasi untuk masyarakat umum, instansi, dan industri</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 text-left">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">1</div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">Pilih Produk atau Jasa</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Pilih barang manufaktur, tekstil, digital, atau layanan servis kejuruan.</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">2</div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">Hubungi via WhatsApp</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Klik tombol WhatsApp untuk otomatis terhubung dengan Admin unit TEFA.</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">3</div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">Produksi & Pengiriman</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Pesanan dikerjakan oleh siswa di bawah supervisi instruktur profesional.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

