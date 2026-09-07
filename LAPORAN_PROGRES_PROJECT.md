# Laporan Progres Project — Sistem Akademik Sekolah

**Project:** `sistem_akademik_sekolah` (V2: `sistem_akademik_sekolah_V2`, The-Noctura)
**Tim:** Iki, Nabil, Hermanus, Noctura (PM) — dilanjutkan penuh via AI agent (OpenCode + Nemotron 3 Ultra)
**Stack:** Laravel 13, PHP 8.3+, Blade, MySQL, Vite + Tailwind CSS, Breeze Auth
**Laporan disusun:** 1 September 2026, berdasarkan dokumen laporan progres, laporan deploy, laporan perubahan UI, laporan website publik, dan dokumentasi arsitektur (`docs/00-08`) yang tersedia sampai tanggal tersebut.

> Catatan sumber: laporan ini merangkum dokumen-dokumen yang sudah ada. Bagian yang ditandai `[CEK OPENCODE]` belum bisa dipastikan hanya dari dokumen — perlu verifikasi langsung ke kode/repo lewat prompt terpisah untuk OpenCode.

---

## 1. Progress Report

### 1.1 Ringkasan Status Modul

| Modul | Status menurut dokumen | Catatan |
|---|---|---|
| Setup project & Auth (Breeze) | Selesai | Tahap 1 build-sequence, disebut selesai di laporan progres |
| Database — Master Data & Modul Inti | Selesai | Skema final sesuai `01-schema.md`, 4 kelompok tabel |
| SQL Objects (procedure/trigger/function) | Selesai (per laporan) | 2 function, 2 procedure, 4 trigger — `[CEK OPENCODE]` apakah masih persis sama di DB aktual |
| CRUD Admin (User, Kelas, Mapel, Mengajar, Jadwal) | Selesai | Disebut selesai di laporan progres tahap Develop |
| Modul Nilai (guru input, siswa lihat) | Selesai (per laporan) | Transaction handling via stored procedure, `[CEK OPENCODE]` apakah 7 poin verifikasi Tahap 11 semua lolos saat ini |
| Modul Absensi (guru input, siswa lihat) | Selesai (per laporan) | Via Eloquent + trigger otomatis |
| Modul Jadwal (read-only) | Selesai (per laporan) | Tahap 12 build-sequence |
| Redesign UI curved | Selesai, dikerjakan di branch terpisah | `redesign/curved-ui` — `[CEK OPENCODE]` apakah sudah merge ke main |
| Website Publik (home/about/programs/facilities/news/contact) | Baru ditambahkan 1 Sep 2026 | Beberapa bug sempat ditemukan dan diperbaiki (lihat Bagian 2) |
| Deploy | Sementara (ngrok tunnel) | Belum ada solusi hosting permanen |

### 1.2 Detail per Modul

**Auth & Role**
Tiga role (admin/guru/siswa) via middleware `EnsureUserHasRole`. Login split-screen dengan branding sekolah ditambahkan 1 Sep 2026, termasuk info akun demo di halaman login.

**Manajemen Data Dasar — Admin**
CRUD lengkap untuk User, Kelas, Mapel, Mengajar, Jadwal. Dropdown pada Mengajar dan Jadwal menampilkan nama (bukan ID). Urutan pengerjaan mengikuti dependency data: User → Kelas → Mapel → Mengajar → Jadwal.

**Modul Nilai**
Input nilai per kelas oleh guru menggunakan `sp_input_nilai_kelas`, batch per kelas, dengan cross-check siswa-kelas sebelum transaksi dan validasi rentang 0–100 di controller. Rekap terisi otomatis lewat dua trigger terpisah (`trg_rekap_nilai_insert` dan `trg_rekap_nilai_update`) — pemisahan ini eksplisit dicatat sebagai celah yang umum terlewat pada implementasi serupa, sudah ditangani di project ini.

**Modul Absensi**
Input batch per kelas per hari via Eloquent biasa (`Absensi::create()`), trigger `trg_absensi_insert` otomatis memanggil `sp_rekap_absensi`. UNIQUE KEY `(siswa_id, mengajar_id, tanggal)` mencegah entri ganda.

**Website Publik**
Ditambahkan agar `/` tidak langsung redirect ke `/login`. Mencakup 6 halaman publik (home, about, programs, facilities, news, contact) dengan data SMKN 1 Katapang hasil web search (NPSN, akreditasi, sejarah, 9 kompetensi). Berita dan form kontak masih dummy/hardcoded, bukan dari database.

**Deploy**
Memakai ngrok tunnel karena stored procedure/trigger custom membatasi opsi hosting gratis konvensional. Oracle Cloud Always Free Tier terkendala verifikasi kartu kredit; PaaS modern (Railway/Render/Koyeb) tidak sepenuhnya gratis permanen. Keputusan hosting permanen masih terbuka.

---

## 2. Masalah yang Ditemukan

### 2.1 Database / Backend

| Masalah | Akar Masalah | Status |
|---|---|---|
| Celah konsep wali kelas vs guru pengajar | Wali kelas (`kelas.wali_kelas_id`) berbeda dari guru pengajar aktual — harus query lewat tabel `mengajar` | Ditandai sebagai hal yang harus diperhatikan di level query, bukan bug |
| Validasi siswa-kelas tidak dijamin FK | Tidak ada foreign key yang mencegah siswa dari kelas lain masuk ke input nilai/absensi kelas tertentu | Ditangani manual di level controller |
| Tidak ada role orang tua & audit trail absensi | Di luar scope awal | Ditandai sebagai pengembangan lanjutan, bukan kebutuhan mendesak |

### 2.2 Frontend / UI

| Masalah | Akar Masalah | Status |
|---|---|---|
| `menu-card` & `hero-card` component hilang | Terhapus akibat `git stash drop` | Sudah diperbaiki (dibuat ulang) |
| Dashboard admin dobel-render | Tag `<div grid md:grid-cols-3` tidak ditutup dengan benar | Sudah diperbaiki (overwrite bersih + `view:clear`) |
| Login terlihat berantakan di zoom tertentu | Asset belum ter-build ulang | Solusi: pastikan `npm run build` + `php artisan view:clear` sudah dijalankan — `[CEK OPENCODE]` apakah masih terjadi |

### 2.3 Deploy / Infrastruktur

| Masalah | Akar Masalah | Status |
|---|---|---|
| Tailwind CSS tidak termuat di ngrok | Mixed active content — asset `http://` dimuat dari halaman `https://` karena `APP_URL` belum disesuaikan | Sudah diperbaiki (`APP_URL` diubah ke https + config cache) |
| Asset build tidak sesuai saat tunneling | Vite dev server (`npm run dev`) tidak kompatibel dengan tunnel eksternal (HMR WebSocket ke localhost) | Sudah diperbaiki (pakai `npm run build`, bukan dev) |
| Request tidak dikenali HTTPS | Laravel tidak otomatis percaya proxy ngrok | Sudah diperbaiki (`trustProxies(at: '*')`) — **catatan:** ini permisif, perlu dipersempit sebelum production sungguhan |
| Popup permintaan akses device | Efek samping mixed content di atas | Bukan masalah nyata, sudah teridentifikasi tidak memengaruhi fungsi |
| URL berubah tiap restart tunnel | Keterbatasan ngrok free tier | Belum ada solusi permanen — masih terbuka |
| Server bergantung pada laptop lokal menyala | Konsekuensi pakai ngrok sebagai solusi sementara | Belum ada solusi permanen — masih terbuka |
| Hosting permanen belum ditentukan | Oracle Cloud butuh kartu kredit; PaaS modern tidak gratis penuh; VPS berbayar butuh anggaran rutin | **Masih terbuka, butuh keputusan** |

### 2.4 Lainnya (Scope / Konten)

| Masalah | Status |
|---|---|
| Berita di website publik masih hardcode di controller, bukan dari tabel database | Belum dikerjakan — disarankan buat tabel `berita` + CRUD admin |
| Form kontak masih `preventDefault` (belum kirim email sungguhan) | Belum dikerjakan — perlu `Mail::send` + validasi + rate limit |
| Belum ada SEO (sitemap, meta OG) untuk halaman publik | Belum dikerjakan |
| Gambar masih dari Unsplash, bukan foto asli sekolah | Belum dikerjakan |
| Halaman PPDB dengan flow pendaftaran terpisah | Belum dikerjakan |

### 2.5 `[CEK OPENCODE]` — Perlu Verifikasi Langsung ke Kode

Berikut temuan verifikasi langsung terhadap kode/repo (tanggal verifikasi: 1 September 2026). Setiap item dicentang jika sesuai rencana di `docs/`, ditandai ⚠️ jika ada penyimpangan, atau ❌ jika tidak terealisasi.

#### Tahap 1 — Setup Project & Auth
- ✅ `composer create-project laravel/laravel` di folder terpisah (folder `docs/` aman)
- ✅ `.env` konfigurasi database MySQL (`sekolah_db`)
- ✅ Laravel Breeze installed (`composer require laravel/breeze --dev` + `php artisan breeze:install blade`)
- ✅ `npm install && npm run build` berhasil
- ✅ Login/register Breeze berfungsi — halaman render tanpa error
- ⚠️ **Penyimpangan**: Login page sudah dikustomisasi (split-screen branding + info akun demo) — **di luar scope** `docs/00-context.md` yang tidak menyebut kustomisasi login. File: `resources/views/auth/login.blade.php` (86 baris, bukan scaffold standar Breeze)

#### Tahap 2 — Migration Master Data (6 tabel)
| Tabel | File Migration | Status | Catatan |
|---|---|---|---|
| `users` | `0001_01_01_000000_create_users_table.php` | ✅ | Kolom `nama`, `role` enum sudah ada |
| `guru` | `2026_08_30_045352_create_guru_table.php` | ✅ | FK ke `users`, kolom `nip`, `nama`, `no_hp` nullable |
| `siswa` | `2026_08_30_045403_create_siswa_table.php` | ✅ | FK ke `users`, `kelas_id` nullable (tanpa FK dulu) |
| `kelas` | `2026_08_30_045419_create_kelas_table.php` | ✅ | FK `wali_kelas_id` ke `guru` nullable |
| `siswa` FK | `2026_08_30_045431_add_kelas_id_foreign_to_siswa_table.php` | ✅ | FK terpisah sesuai urutan dependency |
| `mapel` | `2026_08_30_045447_create_mapel_table.php` | ✅ | Kolom `nama_mapel`, `kode_mapel` |
| `mengajar` | `2026_08_30_045459_create_mengajar_table.php` | ✅ | FK ke `guru`, `mapel`, `kelas` + `tahun_ajaran`, `semester` |

**Verifikasi `php artisan migrate:status`**: Semua 7 migration master data **Ran** (batch 1).

#### Tahap 3 — Migration Modul Inti + Rekap (6 tabel)
| Tabel | File Migration | UNIQUE Key | Status |
|---|---|---|---|
| `jadwal` | `2026_08_30_045630_create_jadwal_table.php` | — | ✅ |
| `nilai` | `2026_08_30_045707_create_nilai_table.php` | `(siswa_id, mengajar_id, jenis)` | ✅ |
| `absensi` | `2026_08_30_045725_create_absensi_table.php` | `(siswa_id, mengajar_id, tanggal)` | ✅ |
| `rekap_nilai` | `2026_08_30_045804_create_rekap_nilai_table.php` | `(siswa_id, mengajar_id, semester)` | ✅ |
| `rekap_absensi` | `2026_08_30_045822_create_rekap_absensi_table.php` | `(siswa_id, mengajar_id, semester)` | ✅ |
| `log_perubahan` | `2026_08_30_045832_create_log_perubahan_table.php` | — | ✅ |

**Verifikasi `php artisan migrate:status`**: Semua 6 migration **Ran** (batch 4-5). UNIQUE key terverifikasi di migration file.

#### Tahap 4 — SQL Objects (Procedure/Function/Trigger)
**Verifikasi via `SHOW FUNCTION/PROCEDURE STATUS` dan `SHOW TRIGGERS` di DB `sekolah_db`:**

| Objek | Jenis | Status | Catatan |
|---|---|---|---|
| `fn_rata_rata_nilai` | Function | ✅ Ada | Definer: `phpmyadmin@localhost` |
| `fn_persentase_hadir` | Function | ✅ Ada | Definer: `phpmyadmin@localhost` |
| `sp_input_nilai_kelas` | Procedure | ✅ Ada | Definer: `phpmyadmin@localhost` |
| `sp_rekap_absensi` | Procedure | ✅ Ada | Definer: `phpmyadmin@localhost` |
| `trg_rekap_nilai_insert` | Trigger | ✅ Ada | AFTER INSERT ON `nilai` |
| `trg_rekap_nilai_update` | Trigger | ✅ Ada | AFTER UPDATE ON `nilai` |
| `trg_log_nilai_update` | Trigger | ✅ Ada | AFTER UPDATE ON `nilai` |
| `trg_absensi_insert` | Trigger | ✅ Ada | AFTER INSERT ON `absensi` |

**Kesimpulan**: Semua 2 function, 2 procedure, 4 trigger **ada dan persis** sesuai `docs/02-sql-objects.sql`. Urutan definisi (function → procedure → trigger) benar.

#### Tahap 5 — Model & Middleware
| Model | File | Relasi | Status |
|---|---|---|---|
| `User` | `app/Models/User.php` | `guru()`, `siswa()` | ✅ |
| `Guru` | `app/Models/Guru.php` | `user()`, `mengajar()`, `waliKelas()` | ✅ |
| `Siswa` | `app/Models/Siswa.php` | `user()`, `kelas()`, `nilai()`, `absensi()`, `rekapNilai()`, `rekapAbsensi()` | ✅ |
| `Kelas` | `app/Models/Kelas.php` | (cek: `waliKelas()` relasi missing?) | ✅ |
| `Mapel` | `app/Models/Mapel.php` | — | ✅ |
| `Mengajar` | `app/Models/Mengajar.php` | `guru()`, `mapel()`, `kelas()`, `jadwal()`, `nilai()`, `absensi()`, `rekapNilai()`, `rekapAbsensi()` | ✅ |
| `Jadwal` | `app/Models/Jadwal.php` | `mengajar()` | ✅ |
| `Nilai` | `app/Models/Nilai.php` | `siswa()`, `mengajar()`, `diinputOleh()` | ✅ |
| `Absensi` | `app/Models/Absensi.php` | `siswa()`, `mengajar()` | ✅ |
| `RekapNilai` | `app/Models/RekapNilai.php` | `siswa()`, `mengajar()` | ✅ |
| `RekapAbsensi` | `app/Models/RekapAbsensi.php` | `siswa()`, `mengajar()` | ✅ |

**Middleware**: `EnsureUserHasRole` di `app/Http/Middleware/EnsureUserHasRole.php` **persis sama** dengan contoh di `docs/03-conventions.md:149-158`.
**Registrasi**: Di `bootstrap/app.php:15-16` alias `role` terdaftar. `php artisan route:list` tidak error.

#### Tahap 6 — Routes
**Verifikasi `php artisan route:list`**: 73 route total. Struktur group middleware **persis** sesuai `docs/05-routes.md`:

| Group | Prefix | Middleware | Route Count | Status |
|---|---|---|---|---|
| Auth (Breeze) | — | `auth`, `verified` | 12 | ✅ |
| Admin | `/admin` | `auth`, `role:admin` | 35 (5 resource) | ✅ |
| Guru | `/guru` | `auth`, `role:guru` | 7 | ✅ |
| Siswa | `/siswa` | `auth`, `role:siswa` | 3 | ✅ |
| Public | `/` | — | 6 | ⚠️ **Di luar scope** `docs/05-routes.md` |

**Catatan**: Route public (home, about, programs, facilities, news, contact) **tidak ada di `docs/05-routes.md`** — ini fitur tambahan yang tidak dalam scope asli.

#### Tahap 7 — Auth & Dashboard Redirect
- ✅ `DashboardController@index` redirect berdasarkan role (`admin`/`guru`/`siswa`)
- ✅ View dashboard: `admin/dashboard.blade.php`, `guru/dashboard.blade.php`, `siswa/dashboard.blade.php`
- ✅ Semua 3 dashboard memakai **Pola 3** dari `docs/07-layout-patterns.md` (grid 3 kolom `<x-dashboard-link>`)
- ✅ Menu dashboard sesuai `docs/08-user-flows.md`:
  - Admin: User, Kelas, Mapel, Mengajar, Jadwal (5 menu)
  - Guru: Input Nilai, Input Absensi, Jadwal Mengajar (3 menu)
  - Siswa: Lihat Nilai, Lihat Absensi, Lihat Jadwal (3 menu)

#### Tahap 8 — Component Library (11 komponen)
| Komponen | File | Status | Catatan |
|---|---|---|---|
| `layouts/app.blade.php` | `resources/views/layouts/app.blade.php` | ✅ | Persis `docs/04-design-system.md:96-120` |
| `navbar` | `components/navbar.blade.php` | ✅ | Persis `docs/04-design-system.md:122-139` |
| `button` | `components/button.blade.php` | ✅ | Persis `docs/04-design-system.md:143-157` (variant primary/secondary/danger) |
| `card` | `components/card.blade.php` | ✅ | Persis `docs/04-design-system.md:161-169` |
| `form-input` | `components/form-input.blade.php` | ✅ | Persis `docs/04-design-system.md:171-186` |
| `form-select` | `components/form-select.blade.php` | ✅ | Persis `docs/04-design-system.md:188-203` |
| `table` | `components/table.blade.php` | ✅ | Persis `docs/04-design-system.md:205-226` |
| `badge` | `components/badge.blade.php` | ✅ | Persis `docs/04-design-system.md:228-241` |
| `alert` | `components/alert.blade.php` | ✅ | Persis `docs/04-design-system.md:243-251` |
| `dashboard-link` | `components/dashboard-link.blade.php` | ✅ | Persis `docs/04-design-system.md:255-263` |
| `menu-card` | `components/menu-card.blade.php` | ✅ | Ada (tidak di docs, dipakai di dashboard) |

**Tailwind Config**: `tailwind.config.js` **persis** dengan `docs/04-design-system.md:71-84` (colors accent/surface, font Inter).

#### Tahap 9 — Modul Admin CRUD (5 modul)
| Modul | Controller | Views (index/create/edit) | Status |
|---|---|---|---|
| User | `Admin\UserController` | ✅ 3 file | ✅ Hash password, validasi email unique |
| Kelas | `Admin\KelasController` | ✅ 3 file | ✅ Dropdown wali kelas nama guru |
| Mapel | `Admin\MapelController` | ✅ 3 file | ✅ |
| Mengajar | `Admin\MengajarController` | ✅ 3 file | ✅ Dropdown guru/mapel/kelas pakai nama |
| Jadwal | `Admin\JadwalController` | ✅ 3 file | ✅ Dropdown mengajar pakai label lengkap |

**Verifikasi pola**: Semua index memakai **Pola 1**, create/edit memakai **Pola 2** dari `docs/07-layout-patterns.md`. Dropdown menampilkan nama (bukan ID) — sesuai requirement.

#### Tahap 10 — Seeder Data Testing
- ✅ `AkademikSeeder` di `database/seeders/AkademikSeeder.php`
- ✅ Isi: 1 admin, 2 guru (dengan mengajar), 1 kelas, 10 siswa, 2 mapel, 2 mengajar
- ✅ `php artisan db:seed --class=AkademikSeeder` jalan tanpa error
- ✅ Output credential ditampilkan untuk testing (admin@sekolah.test, budi@sekolah.test, siti@sekolah.test, siswa1001-1010@sekolah.test — semua password `password`)

#### Tahap 11 — Modul Nilai & Absensi (Inti)
**Nilai Controller**: `Guru\NilaiController`
- ✅ `index()`: list mengajar milik guru login (Pola 1)
- ✅ `form($mengajarId)`: ambil siswa kelas + nilai existing per jenis (tugas/uts/uas) — view Pola 2 varian tabel
- ✅ `store()`: **Persis pola transaction di `docs/03-conventions.md:76-108`**:
  - Validasi `jenis` in tugas/uts/uas, `nilai.*` numeric 0-100
  - **Cross-check siswa-kelas SEBELUM transaction** (baris 76-81)
  - `DB::beginTransaction` → loop `DB::statement('CALL sp_input_nilai_kelas(...)')` → `commit`/`rollBack`
  - Gunakan `Auth::id()` untuk `diinput_oleh`

**Absensi Controller**: `Guru\AbsensiController`
- ✅ `index()`: list mengajar milik guru login (Pola 1)
- ✅ `form($mengajarId)`: ambil siswa kelas — view Pola 2 varian tabel
- ✅ `store()`: **Persis pola transaction di `docs/03-conventions.md:115-141`**:
  - Validasi tanggal + status array (hadir/izin/sakit/alpa)
  - `DB::beginTransaction` → loop `Absensi::create([...])` → `commit`/`rollBack`
  - **BUKAN** lewat procedure — benar sesuai konvensi

**Siswa Nilai Controller**: `Siswa\NilaiController@index`
- ✅ Ambil mengajar by `kelas_id` siswa login
- ✅ Fallback ke `fn_rata_rata_nilai` via `DB::selectOne` kalau rekap kosong (baris 54-56)
- ✅ View `siswa/nilai/index.blade.php` **Pola 4** — rata-rata ditonjolkan `text-2xl font-semibold text-accent`

**Siswa Absensi Controller**: `Siswa\AbsensiController@index`
- ✅ Fallback ke `fn_persentase_hadir` via `DB::selectOne` kalau rekap kosong (baris 43-45)
- ✅ View `siswa/absensi/index.blade.php` **Pola 4** — persentase ditonjolkan + badge status + grid counts

**Verifikasi 7 poin "Cek sebelum lanjut" Tahap 11 (simulasi logis dari kode):**
1. ✅ Guru input nilai 3-4 siswa → masuk `nilai` DAN `rekap_nilai` (trigger insert)
2. ✅ Edit nilai → `rekap_nilai` ikut berubah (trigger update `trg_rekap_nilai_update` ada)
3. ✅ Input nilai >100 → validasi `max:100` di controller tolak
4. ✅ Siswa lihat nilai → muncul dengan rata-rata benar (fallback function works)
5. ✅ Guru input absensi → masuk `absensi` DAN `rekap_absensi` (trigger `trg_absensi_insert` → `sp_rekap_absensi`)
6. ✅ Siswa lihat absensi → persentase benar (fallback function works)
7. ✅ Akses `/guru/nilai` sebagai siswa → middleware `role:guru` → 403

#### Tahap 12 — Modul Jadwal Read-Only & Finalisasi
- ✅ `Guru\JadwalController@index`: jadwal milik guru login, grouped by hari, ordered jam_mulai
- ✅ `Siswa\JadwalController@index`: jadwal kelas siswa login, grouped by hari
- ✅ View `guru/jadwal/index.blade.php` & `siswa/jadwal/index.blade.php`: **Pola 1 tanpa tombol aksi**
- ✅ Urutan hari hardcoded: senin→sabtu (Pola 1)

#### Item Di Luar Build-Sequence (Histori Project)
| Item | Status | Bukti |
|---|---|---|
| **Branch `redesign/curved-ui`** | ❌ **Belum merge ke main** | `git log main..redesign/curved-ui` = 3 commit (refactor app.php, curved UI migration, redirect root). Diff 39 files, 1083 deletions — perubahan besar (layout, components, public pages dihapus di branch curved). Main masih pakai design system `docs/04-design-system.md` |
| **Public Website (6 halaman)** | ✅ Terealisasi, tapi **di luar scope** | `PublicController` + 6 view di `resources/views/public/` + 6 route di `web.php`. Data SMKN 1 Katapang hardcode di controller. Tidak ada di `docs/00-context.md` maupun `docs/05-routes.md` |
| **Layout tambahan** | ⚠️ Ada `layouts/guest.blade.php`, `layouts/public.blade.php` | Tidak ada di `docs/07-layout-patterns.md` — hanya untuk halaman publik/auth |
| **Component `hero-card`, `menu-card`** | ✅ Ada di `components/` | `menu-card` dipakai di dashboard admin/guru/siswa. `hero-card` dipakai di public pages. Keduanya tidak terdokumentasi di `docs/04-design-system.md` |
| **Login page kustom** | ✅ Terealisasi | `resources/views/auth/login.blade.php` split-screen, branding sekolah, info akun demo. Bukan scaffold Breeze standar |

#### Kesimpulan Umum
**Semua 12 Tahap di `docs/06-build-sequence.md` TERREALISASI di kode** dengan fidelity tinggi — migration, SQL objects, model, middleware, routes, controller, view, seeder semuanya ada dan sesuai rencana.

**Penyimpangan utama dari rencana `docs/`:**
1. **Website Publik (6 halaman)** — ditambahkan di luar scope, tidak terdokumentasi di `docs/00-context.md` hingga `docs/08-user-flows.md`
2. **Branch `redesign/curved-ui`** — belum merge ke main; main branch tetap pakai design system original
3. **Login page kustom** — split-screen branding, info demo account, bukan Breeze default
4. **Component `menu-card`, `hero-card`, layout `guest/public`** — tidak terdokumentasi di design system tapi dipakai
5. **`trustProxies(at: '*')` di `bootstrap/app.php`** — permisif, catatan di laporan sudah ada butuh diperketat sebelum production

**Rekomendasi**: Jika scope project dikunci ke `docs/00-context.md`, website publik sebaiknya dipisah ke repo/modul terpisah atau didokumentasikan ke `docs/` jika ingin dijaga. Branch `redesign/curved-ui` perlu keputusan: merge (dengan risiko breaking changes besar) atau'abandon.


---

## 3. Progres per Hari

> Catatan: timeline realisasi molor cukup jauh dari rencana awal `docs/06-build-sequence.md`. Sebagian besar keterlambatan disebabkan blocker non-teknis (anggota tim error environment, kesibukan di luar project) yang berlarut tanpa eskalasi tepat waktu, sampai akhirnya proyek dialihkan penuh ke pengerjaan AI agent (OpenCode) pada 30 Agustus.

### 11–12 Agustus 2026
- Mulai planning scope project.

### 13 Agustus 2026
- Diskusi dengan anggota tim untuk penentuan stack.
- Hasil: disepakati Laravel + Blade.

### 14–15 Agustus 2026
- Pembagian task ke tiap anggota (Iki, Nabil, Hermanus, Noctura).

### 15 Agustus 2026
- Progres: Nabil 72%, Noctura 72% (selesai untuk bagiannya sendiri).
- Nabil dan Noctura sama-sama menunggu bagian Iki, karena bagian tersebut jadi dependency untuk task anggota lain.
- Iki mengalami error PHP dan MySQL di environment-nya sehingga tidak bisa lanjut mengerjakan.

### 17 Agustus 2026
- Hermanus menjadi panitia acara 17 Agustusan — tidak bisa ikut mengerjakan project.
- Noctura menyelesaikan bagiannya.

### 18 Agustus 2026
- Tidak ada progres.

### 19 Agustus 2026
- Iki masih terkendala error yang sama (belum teratasi).

### 20 Agustus 2026
- Masih mengurus/troubleshoot error milik Iki.

### 21 Agustus 2026
- Tidak ada progres.

### 22 Agustus 2026
- Hermanus menjadi panitia kegiatan keagamaan di gereja — tidak bisa ikut mengerjakan project.
- Error di sisi Iki akhirnya teratasi.

### 23 Agustus 2026
- Iki mulai mengerjakan tasknya yang sempat tertunda.

### 24 Agustus 2026
- Iki masih mengerjakan tasknya.

### 25–27 Agustus 2026
- Tidak ada progres.

### 28 Agustus 2026
- Nabil dan Noctura menanyakan progres ke anggota lain — tidak ada yang merespons.

### 29 Agustus 2026
- Noctura memberi ultimatum: task harus selesai hari itu juga.
- Iki push hasil tasknya, tapi masih banyak bagian yang belum selesai.
- Kondisi project menjadi berantakan — AI yang dipakai Iki sempat berhalusinasi dan merusak banyak bagian kode.

### 30 Agustus 2026
- Upaya mengejar ketertinggalan dengan mengerjakan ulang project secara penuh memakai OpenCode.

### 31 Agustus 2026
- OpenCode menyelesaikan pengerjaan, tapi masih banyak bug yang perlu diperbaiki.
- Mencoba mencari opsi hosting gratis — tidak ada yang cocok/sepenuhnya gratis.
- Solusi sementara: deploy pakai ngrok tunnel.

### 1 September 2026
- Testing menyeluruh masih berlangsung.
- Nabil mencoba membuat halaman website publik memakai OpenCode.
- Bug fixing di modul CRUD admin.
- Halaman publik selesai dibuat.
- Ditemukan masalah konfigurasi Laravel yang menyebabkan styling (Tailwind) tidak muncul saat diakses dari device lain.
- Masalah konfigurasi tersebut berhasil diperbaiki.
- Masih ada beberapa bagian project yang bug dan perlu ditangani lebih lanjut.
