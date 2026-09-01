# Laporan Perubahan UI & Website Publik SMKN 1 Katapang

Tanggal: 1 Sep 2026

## Ringkasan
Menambahkan website publik sekolah (sewajarnya website sekolah) agar tidak langsung redirect ke `/login`, plus mempercantik UI login & dashboard. Tetap mengikuti design system (`docs/04-design-system.md` - warna accent #2563EB, Inter) dan tanpa menghapus folder `docs/`.

## File Baru
- `app/Http/Controllers/PublicController.php` — controller publik (home, about, programs, facilities, news, contact) + data 9 jurusan SMKN 1 Katapang & berita dummy
- `resources/views/layouts/public.blade.php` — layout publik: topbar (alamat/NPSN), navbar sticky + mobile menu, footer 4 kolom + CTA PPDB
- `resources/views/public/home.blade.php` — hero gradient + stats, keunggulan 6 card, grid 9 program keahlian, sambutan kepala sekolah, berita 3 card, CTA
- `resources/views/public/about.blade.php` — sejarah (1999 SMKN 4 Soreang → 2000 SMKN 1 Katapang), visi/misi, identitas NPSN 20206214, akreditasi A
- `resources/views/public/programs.blade.php` — detail 9 kompetensi: EIND, TPM, TGM, TKRO, TEKS, TKJ, RPL, MM, MKA + konsultasi jurusan
- `resources/views/public/facilities.blade.php` — fasilitas lab/bengkel/studio + PKL + ekstrakurikuler & prestasi
- `resources/views/public/news.blade.php` — grid berita + kalender akademik
- `resources/views/public/contact.blade.php` — alamat Jl. Ceuri Terusan Kopo KM 13.5, kontak, form demo, iframe Google Maps

## File Diubah
- `routes/web.php` — `GET /` sekarang ke `PublicController@home` (name `public.home`), tambah `/tentang`, `/program-keahlian`, `/fasilitas`, `/berita`, `/kontak`. Route `/login` & `/dashboard` tetap.
- `resources/views/layouts/guest.blade.php` — dari card centered jadi split-screen: kiri branding gradient SMKN 1 Katapang, kanan form login
- `resources/views/auth/login.blade.php` — ikon mail/lock di input, remember+forgot sejajar, info akun demo (admin/budi/siswa) di dalam card
- `resources/views/admin/dashboard.blade.php` — header + 4 stat cards (guru/siswa/kelas/mapel) + grid menu 5 + panduan cepat
- `resources/views/guru/dashboard.blade.php` — header + 3 stat cards (kelas/mapel/siswa) + grid menu 3
- `resources/views/siswa/dashboard.blade.php` — header + 3 stat cards (mapel/rata-rata/kehadiran) + grid menu 3
- `app/Http/Controllers/DashboardController.php` — query statistik dinamis untuk admin/guru/siswa (avg nilai & persentase hadir dari rekap)
- `public/build/*` — rebuild vite (app-D6KV_wQO.css)

## Data Sekolah yang Digunakan
SMKN 1 Katapang - JL. Ceuri Ters Kopo Km 13.5, Katapang, Kab. Bandung 40921 - NPSN 20206214 - Akreditasi A (1214/BAN-SM/SK/2018) - ISO 9001:2008 - Kepala Sekolah Hendra Hermansah, S.Pd., M.M. - 740 siswa, 99 guru, 9 kompetensi (EIND, TP, TGM, TKRO, TEKS, TKJ, RPL, Multimedia, Mekatronika).

## Cara Test
```bash
php artisan serve
# Publik: http://127.0.0.1:8000/ (beranda), /tentang, /program-keahlian, /fasilitas, /berita, /kontak
# Akademik: klik "Sistem Akademik" di navbar → /login
# Login demo: admin@sekolah.test / budi@sekolah.test / siswa1001@sekolah.test (password: password)
# Verifikasi: php artisan route:list
```

## Screenshot Checklist (jika CSS tidak rapi)
Jika login terlihat berantakan seperti screenshot 70% zoom, pastikan sudah `npm run build` dan `php artisan view:clear`. File manifest sudah terbuild.

## Saran Lanjutan
1. Tambah tabel `berita` + CRUD admin agar berita tidak statis di controller
2. Form kontak hubungkan ke `Mail` + validasi + rate limit
3. SEO: sitemap.xml & meta OG untuk tiap halaman publik
4. Galeri foto asli sekolah (ganti Unsplash)
5. PWA & lightbox untuk fasilitas
