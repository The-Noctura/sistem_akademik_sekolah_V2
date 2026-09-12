<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\TefaProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PublicController extends Controller
{
    public function home()
    {
        $programs = $this->programsData();

        $news = collect();
        if (Schema::hasTable('berita')) {
            $news = Berita::where('status', 'publikasi')->latest()->take(3)->get();
        }

        if ($news->isEmpty()) {
            $news = collect($this->newsData());
        }

        $tefaProducts = collect();
        if (Schema::hasTable('tefa_products')) {
            $tefaProducts = TefaProduct::where('status_stok', 'tersedia')->latest()->take(4)->get();
        }

        return view('public.home', compact('programs', 'news', 'tefaProducts'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function programs()
    {
        $programs = $this->programsData();
        return view('public.programs', compact('programs'));
    }

    public function programShow(string $code)
    {
        $program = $this->findProgram(strtoupper($code));
        abort_if(!$program, 404);
        $others = collect($this->programsData())->where('code', '!=', $program['code'])->values()->all();
        return view('public.program-show', compact('program', 'others'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function facilities()
    {
        return view('public.facilities');
    }

    public function news(Request $request)
    {
        $kategori = $request->query('kategori');
        $query = Berita::query()->where('status', 'publikasi');

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $news = $query->latest()->paginate(9)->withQueryString();
        $categories = ['Prestasi', 'PPDB', 'Kerjasama', 'Akademik', 'Kegiatan', 'Umum'];

        return view('public.news', compact('news', 'categories', 'kategori'));
    }

    public function newsShow(string $slug)
    {
        $berita = Berita::where('slug', $slug)->where('status', 'publikasi')->firstOrFail();
        $berita->increment('views');

        $others = Berita::where('status', 'publikasi')
            ->where('id', '!=', $berita->id)
            ->latest()
            ->take(3)
            ->get();

        return view('public.news-show', compact('berita', 'others'));
    }

    public function tefa(Request $request)
    {
        $selectedJurusan = $request->query('jurusan');
        $query = TefaProduct::query();

        if ($selectedJurusan) {
            $query->where('jurusan_code', $selectedJurusan);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $jurusanList = TefaProduct::$jurusanMap;

        return view('public.tefa', compact('products', 'jurusanList', 'selectedJurusan'));
    }

    private function findProgram(string $code): ?array
    {
        foreach ($this->programsData() as $p)
            if ($p['code'] === $code)
                return $p;
        return null;
    }

    private function programsData()
    {
        return [
            [
                'code' => 'TPM',
                'slug' => 'teknik-mesin-tp',
                'name' => 'Teknik Pemesinan',
                'short' => 'TP',
                'icon' => 'ti-settings',
                'color' => 'slate',
                'desc' => 'Keterampilan pemesinan, CNC & manufaktur presisi untuk industri mesin.',
                'tagline' => 'TP & DGM — Program Keahlian Unggulan',
                'about' => [
                    'Teknik Mesin merupakan salah satu jurusan di SMK yang mempelajari berbagai ilmu dan keterampilan di bidang permesinan, manufaktur, serta teknologi industri. Siswa tidak hanya belajar teori, tetapi juga mendapatkan banyak praktik menggunakan berbagai mesin dan peralatan kerja.',
                    'Konsentrasi Keahlian Teknik Pemesinan (TP) fokus pada proses manufaktur, pengolahan bahan logam, serta pembuatan komponen mesin menggunakan alat perkakas konvensional maupun modern seperti mesin bubut, frais, gerinda, dan teknologi CNC. Siswa mengenal gambar teknik, pengukuran, penggunaan alat kerja, pengelasan, serta proses pembuatan dan perawatan komponen mesin dengan mengutamakan keselamatan kerja dan ketelitian.',
                ],
                'concentrations' => [
                    ['title' => 'Teknik Pemesinan (TP)', 'desc' => 'Manufaktur logam presisi: bubut, frais, gerinda, CNC, metrologi & perawatan mesin.', 'points' => ['CNC Milling & Bubut', 'Metrologi Industri', 'CAD/CAM', 'Pengelasan & Fabrikasi']],
                ],
                'vision' => 'Terwujudnya program keahlian Teknik Mesin yang unggul, profesional, dan berkarakter PANCAWALUYA untuk menghasilkan tenaga kerja siap serap DU/DI.',
                'missions' => ['Menyelenggarakan diklat kejuruan teknik mesin profesional sesuai standar industri.', 'Membekali keterampilan operasi mesin konvensional & CNC serta teknologi manufaktur terkini.', 'Membentuk karakter disiplin, berintegritas, bertanggung jawab berlandaskan iman & takwa.', 'Menumbuhkan jiwa wirausaha & kemandirian adaptif era global.', 'Menjalin kerja sama berkelanjutan dengan dunia usaha/industri via PKL & penyaluran kerja.'],
                'careers' => ['Operator CNC', 'Teknisi Maintenance', 'Quality Control', 'Drafter CAD', 'Design/Manufacturing Engineer', 'Wirausaha bengkel manufaktur'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi LSP P1',
            ],
            [
                'code' => 'TGM',
                'slug' => 'teknik-mesin-dgm',
                'name' => 'Desain Gambar Mesin',
                'short' => 'DGM',
                'icon' => 'ti-ruler-measure',
                'color' => 'emerald',
                'desc' => 'Desain & gambar teknik mesin 2D/3D dengan CAD profesional.',
                'tagline' => 'TP & DGM — Program Keahlian Unggulan',
                'about' => [
                    'Konsentrasi Keahlian Desain Gambar Mesin mempelajari cara merancang, mensimulasikan, dan membuat gambar teknik komponen/produk mesin menggunakan standar teknik manual serta software digital modern berbasis CAD (Computer-Aided Design) seperti AutoCAD, SolidWorks, atau Inventor dan master CAM sesuai Standar Industri.',
                    'Siswa dilatih berpikir teliti, kreatif, dan sistematis dalam menerjemahkan ide menjadi gambar kerja yang siap diproduksi di industri manufaktur.',
                ],
                'concentrations' => [
                    ['title' => 'Desain Gambar Mesin (DGM)', 'desc' => 'Perancangan & simulasi produk mesin 2D/3D hingga gambar produksi.', 'points' => ['AutoCAD & Inventor', 'Gambar Manufaktur', 'Desain 3D & Simulasi', 'Master CAM']],
                ],
                'vision' => 'Terwujudnya program keahlian Teknik Mesin yang unggul, profesional, dan berkarakter PANCAWALUYA untuk menghasilkan tenaga kerja siap serap DU/DI.',
                'missions' => ['Menguasai gambar teknik & CAD/CAM sesuai standar industri.', 'Melatih ketelitian, kreativitas & problem solving desain.', 'Menerapkan K3 & budaya kerja industri.', 'Mengembangkan teaching factory produk desain.', 'Memperluas kemitraan industri untuk magang & rekruitmen.'],
                'careers' => ['Drafter CAD', 'Desain Engineer', 'CAD/CAM Operator', 'Quality Engineering', 'Konsultan Desain Manufaktur'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi LSP P1',
            ],
            [
                'code' => 'TKRO',
                'slug' => 'teknik-otomotif',
                'name' => 'Teknik Kendaraan Ringan Otomotif',
                'short' => 'TKR',
                'icon' => 'ti-car',
                'color' => 'red',
                'desc' => 'Perawatan & perbaikan kendaraan ringan modern, EFI & hybrid.',
                'tagline' => 'Program Keahlian Unggulan',
                'about' => [
                    'Teknik Kendaraan Ringan Otomotif (TKRO) membekali siswa kompetensi perawatan, perbaikan, dan diagnosa kendaraan ringan modern mencakup engine, chassis, kelistrikan, EFI, dan teknologi hybrid.',
                    'Pembelajaran 70% praktik di bengkel standar industri dengan engine trainer, lift, dan diagnostic tool, serta penguatan soft skill layanan pelanggan dan kewirausahaan bengkel.',
                ],
                'concentrations' => [
                    ['title' => 'Teknik Kendaraan Ringan', 'desc' => 'Servis & troubleshooting kendaraan bensin/diesel modern.', 'points' => ['Engine EFI & Hybrid', 'Chassis, Rem & Suspensi', 'Kelistrikan & Diagnostik', 'Manajemen Bengkel']],
                ],
                'vision' => 'Menghasilkan lulusan TKRO yang kompeten, berkarakter, dan siap kerja di industri otomotif nasional.',
                'missions' => ['Menguasai teknologi engine & sistem kendaraan modern.', 'Menerapkan SOP K3 & layanan prima.', 'Mengembangkan teaching factory bengkel sekolah.', 'Menjalin kemitraan AHASS & industri otomotif untuk PKL & rekrutmen.'],
                'careers' => ['Teknisi Bengkel Resmi', 'Service Advisor', 'Quality Control Otomotif', 'Wirausaha Bengkel', 'Instruktur Otomotif'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi Kompetensi Otomotif',
            ],
            [
                'code' => 'EIND',
                'slug' => 'teknik-elektronika',
                'name' => 'Teknik Elektronika Industri',
                'short' => 'TEI',
                'icon' => 'ti-cpu',
                'color' => 'blue',
                'desc' => 'Penguasaan elektronika industri, PLC, sensor & IoT untuk otomatisasi manufaktur.',
                'tagline' => 'TEI & MEKA — Program Keahlian Unggulan',
                'about' => [
                    'Teknik Elektronika Industri (TEI) mempelajari elektronika analog-digital, mikrokontroler, PLC, sensor-aktuator, dan IoT untuk otomatisasi manufaktur. Pembelajaran berbasis proyek dengan lab PLC, robotik, dan sistem kontrol industri.',
                    'Lulusan dipersiapkan sebagai tenaga teknisi elektronika industri yang mampu merancang, merakit, dan memelihara sistem otomasi di pabrik modern.',
                ],
                'concentrations' => [
                    ['title' => 'Teknik Elektronika Industri (TEI)', 'desc' => 'Otomasi industri & sistem kontrol berbasis PLC/IoT.', 'points' => ['PLC & Mikrokontroler', 'Sensor & Aktuator', 'IoT Industri', 'Instalasi & Troubleshooting']],
                ],
                'vision' => 'Menjadi program keahlian elektronika industri yang unggul, adaptif teknologi, dan berdaya saing global.',
                'missions' => ['Menguasai elektronika & otomasi sesuai kebutuhan industri 4.0.', 'Menerapkan budaya K3 & kerja presisi.', 'Mengembangkan inovasi IoT & teaching factory.', 'Memperkuat link & match dengan industri elektronika.'],
                'careers' => ['Teknisi Elektronika Industri', 'PLC Programmer', 'Maintenance Elektronika', 'IoT Technician', 'Wirausaha Elektronik'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi LSP P1',
            ],
            [
                'code' => 'MKA',
                'slug' => 'mekatronika',
                'name' => 'Mekatronika',
                'short' => 'MEKA',
                'icon' => 'ti-robot',
                'color' => 'orange',
                'desc' => 'Integrasi mekanik, elektronika & informatika untuk robotika.',
                'tagline' => 'TEI & MEKA — Program Keahlian Unggulan',
                'about' => [
                    'Mekatronika mengintegrasikan mekanik, elektronika, dan informatika untuk merancang sistem robotik dan automasi. Siswa belajar pneumatik, hidrolik, PLC, robotika, dan pemrograman kontrol.',
                    'Praktik dilakukan di lab mekatronika dengan trainer robot lengan, conveyor, dan sistem automasi industri mini.',
                ],
                'concentrations' => [
                    ['title' => 'Mekatronika (MEKA)', 'desc' => 'Sistem automasi & robotika industri terintegrasi.', 'points' => ['Robotika & Pneumatik', 'PLC & Kontrol Otomatis', 'Pemrograman Mikrokontroler', 'Maintenance Sistem Automasi']],
                ],
                'vision' => 'Menghasilkan lulusan mekatronika yang kreatif, inovatif, dan siap menghadapi industri automasi.',
                'missions' => ['Mengintegrasikan mekanik-elektronika-informatika dalam proyek nyata.', 'Membentuk karakter Pancawaluya & etos industri.', 'Mengembangkan teaching factory automasi.', 'Menjalin kemitraan industri manufaktur & robotik.'],
                'careers' => ['Mekatronika Technician', 'Automation Engineer', 'Robot Operator', 'Maintenance Automasi', 'Technopreneur'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi Kompetensi Mekatronika',
            ],
            [
                'code' => 'TEKS',
                'slug' => 'teknik-tekstil',
                'name' => 'Teknologi Penyempurnaan Tekstil',
                'short' => 'TPT',
                'icon' => 'ti-shirt',
                'color' => 'amber',
                'desc' => 'Teknologi proses tekstil dari pemintalan hingga finishing.',
                'tagline' => 'Program Keahlian Unggulan',
                'about' => [
                    'Program Keahlian Teknik Penyempurnaan Tekstil mempelajari pengolahan lanjut pada tekstil mentah — serat, benang, kain — secara kimia, mekanik, maupun gabungannya agar memiliki sifat sesuai kebutuhan: pretreatment, pencelupan (dyeing), pencapan (printing), dan penyempurnaan khusus (finishing).',
                    'Kurikulum mengacu pada Kepmenaker RI No.266/2020 SKKNI Bidang Penyempurnaan Tekstil dan Permenperin No.35/2022 KKNI Industri Penyempurnaan Tekstil, dengan 70% praktik di lab tekstil standar industri.',
                ],
                'concentrations' => [
                    ['title' => 'Teknik Penyempurnaan Tekstil (TPT)', 'desc' => 'Proses kimia-mekanik tekstil hingga produk siap pakai.', 'points' => ['Pretreatment & Dyeing', 'Printing & Colour Matching', 'Finishing (Tentering, Calendering)', 'Quality Control Tekstil']],
                ],
                'vision' => 'Mewujudkan lulusan TPT yang unggul, berkarakter kebangsaan, kompetitif dan adaptabel.',
                'missions' => ['Menanamkan karakter Pancawaluya & akhlak mulia.', 'Menyelenggarakan pembelajaran kompetensi adaptif industri tekstil.', 'Membangun disiplin & budaya kerja industri.', 'Mengembangkan Teaching Factory & kewirausahaan.', 'Memperluas kemitraan dunia kerja nasional/internasional.'],
                'careers' => ['Operator Mesin Pencelupan', 'Operator Printing', 'Operator Finishing', 'Quality Control Tekstil', 'Colour Matching Specialist'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi SKKNI Tekstil',
            ],
            [
                'code' => 'TKJ',
                'slug' => 'tjkt',
                'name' => 'Teknik Jaringan Komputer & Telekomunikasi',
                'short' => 'TJKT',
                'icon' => 'ti-network',
                'color' => 'sky',
                'desc' => 'Jaringan fiber optic, server, cloud & keamanan siber.',
                'tagline' => 'Program Keahlian Unggulan • 70% Praktik',
                'about' => [
                    'TJKT mengadopsi KKNI Level II Teknik Komputer Jaringan dengan bobot 70% praktik & 30% teori berbasis Hybrid Learning (synchronous & asynchronous) agar relevan dengan industri telekomunikasi terkini.',
                    'Guru produktif 6 orang berlatar industri & magang relevan, lab dirancang standar industri (2 lab jaringan, 1 ruang perawatan komputer, 1 Teaching Factory) untuk mencetak profesional jaringan dalam & luar negeri.',
                ],
                'concentrations' => [
                    ['title' => 'TJKT', 'desc' => 'Jaringan, server & telekomunikasi modern.', 'points' => ['Fiber Optic & Mikrotik', 'Server, Cloud & Virtualisasi', 'Cyber Security', 'IoT & Network Engineering']],
                ],
                'vision' => 'Menjadikan lulusan TJKT yang unggul berdasarkan karakter Pancawaluya.',
                'missions' => ['Menerapkan K3LH & kedisiplinan lab (Cager).', 'Menanamkan etika profesi & integritas IT (Bener).', 'Menumbuhkan empati & jiwa layanan (Bager).', 'Menyelenggarakan keahlian jaringan & sistem berkualitas (Pinter).', 'Mendorong proaktif, kreatif & problem solving (Singer).'],
                'careers' => ['Teknisi Jaringan', 'Network Administrator', 'NOC / IT Support', 'RT/RW Net & ISP Lokal', 'CCTV & Hosting Consultant', 'Wirausaha IT'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi Mikrotik & LSP P1',
            ],
            [
                'code' => 'RPL',
                'slug' => 'pplg',
                'name' => 'Pengembangan Perangkat Lunak & Gim',
                'short' => 'PPLG',
                'icon' => 'ti-code',
                'color' => 'violet',
                'desc' => 'Pengembangan web, mobile & desktop dengan stack modern.',
                'tagline' => 'Program Keahlian Unggulan',
                'about' => [
                    'PPLG membekali kompetensi rekayasa perangkat lunak: analisis kebutuhan, desain UI/UX, pemrograman web/mobile/desktop, basis data, API, dan pengembangan gim. Pembelajaran berbasis proyek & Teaching Factory produk digital nyata.',
                    'Siswa dilatih kolaborasi tim (Agile/Scrum), version control, dan deployment cloud, serta penguatan karakter kreatif, disiplin, dan adaptif teknologi.',
                ],
                'concentrations' => [
                    ['title' => 'PPLG', 'desc' => 'Software engineering end-to-end hingga rilis produk.', 'points' => ['Web & Mobile Dev', 'Database & API', 'UI/UX & Gim', 'Cloud Deployment & DevOps']],
                ],
                'vision' => 'Menjadi program PPLG yang unggul, kreatif, dan menghasilkan talenta digital siap industri.',
                'missions' => ['Menguasai stack modern & best practice software engineering.', 'Menerapkan budaya kerja profesional & K3LH.', 'Mengembangkan karya digital & Teaching Factory.', 'Memperkuat kemitraan DUDIKA & startup.', 'Mendorong technopreneurship siswa.'],
                'careers' => ['Web/Mobile Developer', 'UI/UX Designer', 'Game Developer', 'Database Administrator', 'Startup Founder / Freelancer'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi LSP P1 & Industri',
            ],
            [
                'code' => 'MM',
                'slug' => 'broadcasting-perfilman',
                'name' => 'Broadcasting dan Perfilman',
                'short' => 'BP',
                'icon' => 'ti-movie',
                'color' => 'pink',
                'desc' => 'Produksi film, broadcasting, sinematografi & editing video profesional.',
                'tagline' => 'Program Keahlian Unggulan',
                'about' => [
                    'Membekali kompetensi produksi siaran, film, televisi, fotografi, videografi, audio, editing, dan konten digital. Pembelajaran teori-praktik berbasis proyek dengan mengembangkan kreativitas, keterampilan teknis, kerja sama, disiplin, serta budaya kerja industri dan pemanfaatan teknologi digital media.',
                    'Lulusan dipersiapkan untuk bekerja, melanjutkan pendidikan, maupun berwirausaha di industri penyiaran, perfilman, media digital, fotografi, videografi, production house, dan industri kreatif lainnya.',
                ],
                'concentrations' => [
                    ['title' => 'Broadcasting dan Perfilman (BP)', 'desc' => 'Produksi audiovisual dari pra-produksi hingga pasca.', 'points' => ['Sinematografi & Penyutradaraan', 'Kamera, Lighting & Audio', 'Editing, VFX & Motion Graphic', 'Live Streaming OBS/vMix']],
                ],
                'vision' => 'Menjadi Program Keahlian Broadcasting dan Perfilman yang unggul dalam menyiapkan Generasi Pancawaluya yang berkarakter kebangsaan, kompetitif, adaptabel, kreatif, dan profesional di bidang penyiaran serta perfilman menuju 2030.',
                'missions' => ['Mengembangkan kompetensi BP sesuai kebutuhan industri.', 'Membentuk karakter Pancawaluya & jiwa kebangsaan.', 'Mengembangkan kreativitas & penguasaan teknologi digital.', 'Menerapkan budaya kerja profesional, K3 & etika profesi.', 'Memperkuat kemitraan DUDIKA & TEFA.', 'Menghasilkan karya audiovisual kreatif & bernilai.', 'Mempersiapkan lulusan kompetitif & adaptabel (kerja/wirausaha/kuliah).'],
                'careers' => ['Presenter/Reporter, Camera Operator, Floor Director', 'Sutradara, Penulis Naskah, Editor, Kru Produksi', 'Photographer/Videographer Event', 'Video Editor, Content Creator, Motion Graphic', 'Operator OBS/vMix Live Streaming', 'Creative Crew Periklanan & Production House'],
                'duration' => '3 tahun • PKL 4 bulan kelas XII • Sertifikasi Broadcasting',
            ],
        ];
    }

    private function newsData()
    {
        return [
            ['title' => 'SMKN 1 Katapang Raih Akreditasi A dari BAN-SM', 'date' => '31 Des 2018', 'cat' => 'Prestasi', 'img' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600', 'excerpt' => 'Komitmen mutu pendidikan vokasi diakui melalui akreditasi A (Unggul) tingkat nasional.'],
            ['title' => 'PPDB 2025/2026 Dibuka: 9 Kompetensi Keahlian', 'date' => '15 Jun 2025', 'cat' => 'PPDB', 'img' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600', 'excerpt' => 'Pendaftaran online untuk 17 rombel. Jurusan favorit TKRO, TKJ, RPL & Broadcasting Perfilman.'],
            ['title' => 'Kerja Sama Industri: PKL di PT Pindad & PT LEN', 'date' => '10 Mei 2025', 'cat' => 'Kerjasama', 'img' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600', 'excerpt' => 'Siswa kelas XII magang industri untuk penguatan kompetensi link & match dunia kerja.'],
        ];
    }
}
