<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\TefaProduct;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TefaAndBeritaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin?->id;

        // Seed Produk TEFA Berbagai Jurusan
        $products = [
            [
                'nama_produk'  => 'Jasa Tune Up & Ganti Oli Kendaraan Ringan',
                'jurusan_code' => 'TKRO',
                'kategori'     => 'jasa',
                'harga'        => 75000,
                'foto'         => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=600',
                'deskripsi'    => 'Servis berkala mesin bensin/diesel, pembersihan throttle body, injector cleaner, dan pengecekan rem & suspensi standar bengkel resmi.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344550',
            ],
            [
                'nama_produk'  => 'Pembuatan Komponen Presisi CNC Bubut & Milling',
                'jurusan_code' => 'TPM',
                'kategori'     => 'barang',
                'harga'        => 150000,
                'foto'         => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=600',
                'deskripsi'    => 'Menerima pesanan suku cadang mesin, poros (shaft), roda gigi, dan part logam presisi berbahan baja/aluminium dengan toleransi mikron.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344551',
            ],
            [
                'nama_produk'  => 'Jasa Desain 3D CAD & Modeling SolidWorks/Inventor',
                'jurusan_code' => 'TGM',
                'kategori'     => 'jasa',
                'harga'        => 200000,
                'foto'         => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?w=600',
                'deskripsi'    => 'Jasa pembuatan gambar teknik 2D drafting manufaktur, 3D rendering part, dan simulasi perakitan mesin untuk kebutuhan industri & skripsi.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344552',
            ],
            [
                'nama_produk'  => 'Jasa Pembuatan Website Profil Sekolah & Toko Online',
                'jurusan_code' => 'PPLG',
                'kategori'     => 'jasa',
                'harga'        => 750000,
                'foto'         => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600',
                'deskripsi'    => 'Layanan pengembangan website responsif, landing page promosi, sistem informasi, dan aplikasi kasir berbasis web modern.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344553',
            ],
            [
                'nama_produk'  => 'Kain Printing Motif Batik Khas Katapang (2 Meter)',
                'jurusan_code' => 'TEKS',
                'kategori'     => 'barang',
                'harga'        => 120000,
                'foto'         => 'https://images.unsplash.com/photo-1607344645866-009c320c5ab8?w=600',
                'deskripsi'    => 'Kain katun primisima premium dengan proses pencapan tekstil pigmen ramah lingkungan, warna cerah dan tahan cuci.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344554',
            ],
            [
                'nama_produk'  => 'Jasa Instalasi Jaringan LAN, WiFi Kantor & CCTV',
                'jurusan_code' => 'TJKT',
                'kategori'     => 'jasa',
                'harga'        => 350000,
                'foto'         => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600',
                'deskripsi'    => 'Penarikan kabel UTP/Fiber Optic, konfigurasi router Mikrotik, setup access point, dan perakitan kamera pengawas CCTV kantor/sekolah.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344555',
            ],
            [
                'nama_produk'  => 'Jasa Dokumentasi Video Shooting & Live Streaming Event',
                'jurusan_code' => 'MM',
                'kategori'     => 'jasa',
                'harga'        => 1200000,
                'foto'         => 'https://images.unsplash.com/photo-1533750349088-cd871a92f312?w=600',
                'deskripsi'    => 'Liputan multi-kamera full HD, operator switcher vMix/OBS, recording audio jernih untuk acara wisuda, seminar, dan pernikahan.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344556',
            ],
            [
                'nama_produk'  => 'Modul Trainer Trainer IoT & Otomasi Mikrokontroler',
                'jurusan_code' => 'EIND',
                'kategori'     => 'barang',
                'harga'        => 450000,
                'foto'         => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600',
                'deskripsi'    => 'Paket kit belajar elektronika industri berbasis ESP32/Arduino lengkap dengan relay, sensor suhu, LCD display, dan modul IoT blynk.',
                'status_stok'  => 'tersedia',
                'nomor_wa'     => '081223344557',
            ],
        ];

        foreach ($products as $p) {
            TefaProduct::updateOrCreate(['nama_produk' => $p['nama_produk']], $p);
        }

        // Seed Berita Sekolah
        $newsItems = [
            [
                'judul'     => 'SMKN 1 Katapang Sukses Gelar Expo Teaching Factory & Job Fair 2026',
                'slug'      => 'smkn-1-katapang-sukses-gelar-expo-tefa-job-fair-2026',
                'kategori'  => 'Prestasi',
                'thumbnail' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800',
                'ringkasan' => 'Pameran produk karya 9 kompetensi keahlian dan perekrutan tenaga kerja bersama 25 mitra industri nasional.',
                'konten'    => "SMK Negeri 1 Katapang kembali membuktikan kualitas pendidikan vokasi dengan menggelar Expo Teaching Factory (TEFA) dan Bursa Kerja Khusus (BKK) Job Fair 2026.\n\nKegiatan ini dihadiri langsung oleh perwakilan Dinas Pendidikan Provinsi Jawa Barat serta puluhan perwakilan industri terkemuka seperti PT Pindad, PT LEN Industri, dan AHASS Honda.\n\nKepala Sekolah, Hendra Hermansah, S.Pd., M.M., menyampaikan bahwa TEFA bukan sekadar praktik belajar, melainkan sarana inkubasi siswa agar menghasilkan produk nyata berdaya saing pasar.",
                'status'    => 'publikasi',
                'views'     => 142,
            ],
            [
                'judul'     => 'Penerimaan Peserta Didik Baru (PPDB) 2026/2027 Resmi Dibuka',
                'slug'      => 'ppdb-2026-2027-resmi-dibuka',
                'kategori'  => 'PPDB',
                'thumbnail' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800',
                'ringkasan' => 'Pendaftaran calon peserta didik baru untuk 17 rombongan belajar pada 9 konsentrasi keahlian unggulan.',
                'konten'    => "Penerimaan Peserta Didik Baru (PPDB) SMKN 1 Katapang Tahun Pelajaran 2026/2027 resmi dibuka secara bertahap melalui sistem daring provinsi.\n\nTahun ini, SMKN 1 Katapang membuka kuota untuk 17 rombongan belajar yang terbagi ke dalam 9 program keahlian. Calon siswa diharapkan mempersiapkan berkas rapor serta sertifikat prestasi bagi jalur kejuaraan.",
                'status'    => 'publikasi',
                'views'     => 328,
            ],
            [
                'judul'     => 'Perluasan Kerjasama Link & Match Industri Bersama Perusahaan BUMN',
                'slug'      => 'perluasan-kerjasama-link-and-match-industri-bumn',
                'kategori'  => 'Kerjasama',
                'thumbnail' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800',
                'ringkasan' => 'Penandatanganan MoU program magang bersertifikat dan penyaluran lulusan langsung ke dunia industri.',
                'konten'    => "Dalam rangka memperkuat sinkronisasi kurikulum dengan kebutuhan industri 4.0, SMKN 1 Katapang menandatangani nota kesepahaman (MoU) kemitraan strategis dengan beberapa BUMN sektor manufaktur dan elektronika.\n\nKerjasama ini mencakup program magang siswa selama 4 bulan, pelatihan guru tamu industri, serta rekrutmen prioritas bagi lulusan terbaik.",
                'status'    => 'publikasi',
                'views'     => 95,
            ],
        ];

        foreach ($newsItems as $n) {
            Berita::updateOrCreate(
                ['slug' => $n['slug']],
                array_merge($n, ['penulis_id' => $adminId])
            );
        }
    }
}

