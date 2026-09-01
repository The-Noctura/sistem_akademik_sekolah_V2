# Laporan Pembuatan Website Publik SMKN 1 Katapang

> Perintah: "buatkan saya website sekolah jadi ga cuma pas klik web langsung harus login, jadi ada home about contact dll tentang sekolah SMKN 1 Katapang dengan UI/UX menarik"
> Tanggal: 1 September 2026
> Project: sistem_akademik_sekolah_V2 (Laravel 11 + Blade + MySQL)

---

## 1. Tujuan
Sebelumnya `GET /` → `redirect()->route('login')` (`routes/web.php:6`). Sekarang website punya 2 layer:
- **Publik (guest)** — bisa diakses tanpa login: Beranda, Profil, Program Keahlian, Fasilitas, Berita, Kontak
- **Akademik (auth)** — tetap butuh login: `/login` → `/dashboard` (role admin/guru/siswa)

Tetap taat design system `docs/04-design-system.md:14` (accent #2563EB, accent-soft #EFF6FF, surface #F8FAFC, font Inter) dan tanpa menghapus `docs/`.

## 2. File Baru (7 file)

| File | Fungsi |
|------|--------|
| `app/Http/Controllers/PublicController.php:1` | Controller publik, method `home()`, `about()`, `programs()`, `facilities()`, `news()`, `contact()`. Data 9 jurusan & 3 berita di-hardcode di `programsData()` & `newsData()` biar tidak perlu DB baru. |
| `resources/views/layouts/public.blade.php:1` | Layout publik: topbar hitam (alamat Jl. Ceuri Kopo KM13.5 + NPSN 20206214 + email), navbar sticky dengan logo + 6 menu + tombol "Sistem Akademik" (`route('login')`), mobile hamburger, footer 4 kolom (tentang, tautan, kontak, CTA PPDB). |
| `resources/views/public/home.blade.php:1` | **Beranda** — Hero gradient slategray→blue (badge PPDB 2025/2026, headline "Mencetak Generasi Vokasi", 3 stats: 9 Kompetensi/A/740+ siswa, gambar kampus + badge Akreditasi A ISO 9001:2008), section Keunggulan (6 card), Program Keahlian (grid 9 card + kode), Sambutan Kepsek (Hendra Hermansah), Berita 3 card, CTA PPDB. |
| `resources/views/public/about.blade.php:1` | **Profil** — Sejarah (1999 SMKN 4 Soreang dana LOAN OECF Jepang → 2000 pindah Jl. Ceuri Kopo → resmi SMKN 1 Katapang), timeline 1999/2000/A, Visi-Misi, 6 nilai budaya (Disiplin,Kreatif,dll), Identitas (NPSN, Akreditasi A 1214/BAN-SM/SK/2018, dll), daftar kepsek dari masa ke masa. |
| `resources/views/public/programs.blade.php:1` | **Program Keahlian** — 9 kartu: EIND (PLC/IoT), TPM (CNC), TGM (CAD 3D), TKRO (EFI/Hybrid), TEKS (Tekstil), TKJ (Fiber/Mikrotik), RPL (Web/Mobile), MM/DKV (Desain/Video), MKA (Robotika). + chip jumlah siswa per program & CTA konsultasi jurusan. |
| `resources/views/public/facilities.blade.php:1` | **Fasilitas** — 8 fasilitas (Lab TKJ/RPL, Bengkel TKRO, Lab Elektronika, Workshop Pemesinan, Studio Multimedia, Lab Tekstil, Perpustakaan, Lapangan), card PKL 6 bulan (Pindad/LEN/Honda), ekstrakurikuler 6 item, prestasi LKS. |
| `resources/views/public/news.blade.php:1` | **Berita** — grid 3 berita dummy (Akreditasi, PPDB, PKL) + card Kalender Akademik 2024/2025. |
| `resources/views/public/contact.blade.php:1` | **Kontak** — kartu alamat lengkap, jam operasional, akses cepat login, form "Kirim Pesan" (demo preventDefault), iframe Google Maps `SMKN 1 Katapang Kopo`. |

## 3. File Diubah (6 file)

| File | Perubahan |
|------|-----------|
| `routes/web.php:3` | Tambah `use PublicController`, ganti `GET /` jadi `PublicController@home` name `public.home`, tambah 5 route: `/tentang` (`public.about`), `/program-keahlian` (`public.programs`), `/fasilitas` (`public.facilities`), `/berita` (`public.news`), `/kontak` (`public.contact`). Route auth/admin/guru/siswa tetap. |
| `resources/views/layouts/guest.blade.php:1` | Dari `gray-100 centered card` jadi `split-screen`: kiri `bg-slate-900 gradient accent` (icon sekolah, headline Sistem Akademik, checklist fitur, link Kembali ke Website), kanan form centered. |
| `resources/views/auth/login.blade.php:1` | Ikon `ti-mail`/`ti-lock` di input, remember+forgot sejajar, tombol fullwidth, card demo `admin@sekolah.test / budi@sekolah.test / siswa1001@sekolah.test` (pass `password`). |
| `resources/views/admin/dashboard.blade.php:1` | Fix dobel-render (sebelumnya `<div grid md:grid-cols-3` tidak ditutup). Sekarang header + 4 stat cards (`guru_count/siswa_count/kelas_count/mapel_count`) + 5 `x-menu-card` + panduan cepat. |
| `app/Http/Controllers/DashboardController.php:1` | Tambah query stats dinamis: admin (count Guru/Siswa/Kelas/Mapel), guru (kelas/mapel/siswa dari `mengajar`), siswa (mapel/avg_nilai/persen_hadir dari `rekap_nilai` & `rekap_absensi`). |
| `resources/views/components/menu-card.blade.php:1` + `hero-card.blade.php:1` | Dibuat ulang (hilang karena `git stash drop`). `menu-card` pakai `rounded-2xl border hover:shadow-lg`, `hero-card` slate-900 + blur accent. |

Build: `npm run build` → `public/build/manifest.json:1`, `app-D6KV_wQO.css` (52.52kB).

## 4. Data SMKN 1 Katapang yang Dipakai (hasil web search)
Sumber: Dapo Kemdikbud, smkn1katapang.sch.id, DaftarSekolah.net
- Alamat: Jl. Ceuri Terusan Kopo KM 13.5 RT01 RW14, Katapang, Kab. Bandung 40921
- NPSN 20206214, Negeri, Akreditasi A (31 Des 2018, SK 1214/BAN-SM/SK/2018), ISO 9001:2008
- Berdiri 17 Nov 2000 (awal 1999 SMKN 4 Soreang, dana LOAN OECF Jepang)
- Kepala Sekolah: Hendra Hermansah, S.Pd., M.M. (sebelumnya Etti Mulyati dkk)
- Siswa 740 (versi Dapo terbaru 593), Guru 99
- 9 Kompetensi: Teknik Elektronika Industri, Teknik Pemesinan, Teknik Gambar Mesin, TKRO, Teknologi Penyempurnaan Tekstil, TKJ, RPL/PPLG, Multimedia/Broadcasting, Mekatronika — sistem Block

## 5. Cara Verifikasi

```bash
php artisan route:list | grep public
# GET /                  public.home
# GET /tentang           public.about
# GET /program-keahlian  public.programs
# GET /fasilitas         public.facilities
# GET /berita            public.news
# GET /kontak            public.contact

php artisan serve
# http://127.0.0.1:8000/                → Beranda publik
# http://127.0.0.1:8000/tentang          → Profil
# http://127.0.0.1:8000/program-keahlian → 9 jurusan
# http://127.0.0.1:8000/kontak            → Form + Maps
# Klik "Sistem Akademik" → /login → dashboard
# Login: admin@sekolah.test / password (budi@sekolah.test, siswa1001@sekolah.test)
php artisan view:clear && npm run build
```

## 6. Bug yang Diperbaiki di Tahap Akhir
- `InvalidArgumentException menu-card` → file hilang → dibuat ulang
- `admin/dashboard.blade.php` dobel render (grid tidak ditutup) → overwrite bersih, `view:clear`

## 7. Yang Belum / Saran Next
1. Berita masih dummy di controller → buat tabel `berita` + CRUD admin
2. Form kontak masih `preventDefault` → hubungkan `Mail::send` + validasi
3. Ganti gambar Unsplash dengan foto asli sekolah
4. Tambah SEO (sitemap, OG meta) & lightbox galeri
5. Halaman PPDB terpisah dengan flow pendaftaran

---
*File ini dibuat otomatis sebagai laporan perubahan atas perintah pembuatan halaman website publik.*
