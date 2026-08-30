# 06 — Build Sequence

Ini urutan kerja LINEAR. Kerjakan berurutan dari Tahap 1 ke Tahap 12. Jangan lompat tahap. Jangan kerjakan tahap belakangan duluan meski terlihat independen — banyak tahap punya dependency implisit ke tahap sebelumnya (migration sebelum model, model sebelum controller, SQL objects sebelum controller nilai/absensi, dst).

Sebelum memulai tahap apa pun, baca file referensi yang disebut di tahap itu memakai Read tool — jangan mengandalkan ingatan dari konteks sebelumnya kalau ada keraguan sedikit pun.

Setelah menyelesaikan setiap tahap, verifikasi sesuai instruksi "Cek sebelum lanjut" di tahap itu. Kalau verifikasi gagal, JANGAN lanjut ke tahap berikutnya — perbaiki dulu.

---

## Tahap 1 — Setup Project

**PERINGATAN KRITIS SEBELUM MULAI:** langkah 1 di bawah (`composer create-project`) dan perintah instalasi sejenis bisa menghapus atau menimpa isi folder tempat command itu dijalankan. Folder `docs/` (tempat file ini dan semua file referensi lain berada) TIDAK BOLEH berada di lokasi yang akan disentuh oleh command instalasi. Sebelum menjalankan langkah 1:
- Pastikan kamu tahu persis di folder mana `docs/` berada saat ini
- Jalankan `composer create-project` di folder KOSONG terpisah (misal folder baru bernama `app/` atau sejenisnya), BUKAN menimpa folder yang berisi `docs/`
- Setelah project Laravel selesai dibuat di folder terpisah itu, folder `docs/` harus tetap ada persis di tempat semula, tidak boleh ikut terhapus, tertimpa, atau perlu dipindah-pindah lewat command yang berisiko
- Kalau ragu sama sekali soal ini, berhenti dan tanya dulu sebelum menjalankan command apa pun yang bersifat instalasi/inisialisasi project

1. Jalankan `composer create-project laravel/laravel .` di folder project (atau folder kosong yang sudah disiapkan)
2. Setup `.env` — isi kredensial database MySQL
3. Buat database baru sesuai nama di `.env`
4. Jalankan `php artisan migrate` — pastikan tidak error (ini membuat tabel bawaan Laravel)
5. Install Laravel Breeze: `composer require laravel/breeze --dev`, lalu `php artisan breeze:install blade`
6. Jalankan `npm install && npm run build`
7. Jalankan `php artisan migrate` lagi

**Cek sebelum lanjut:** `php artisan serve`, buka `/login` dan `/register` di browser, pastikan halaman render tanpa error.

---

## Tahap 2 — Migration Master Data

Baca `docs/01-schema.md` bagian "Master Data" dan "Relasi Pengajaran" sebelum mulai.

Buat migration untuk tabel berikut, URUT sesuai dependency FK (jangan diacak):

1. `users` (tambahkan kolom `nama` dan `role` ENUM ke migration bawaan Breeze)
2. `guru` (FK ke `users`)
3. `siswa` (FK ke `users`; kolom `kelas_id` dulu tanpa constraint, karena tabel `kelas` belum ada)
4. `kelas` (FK `wali_kelas_id` ke `guru`)
5. Migration tambahan: tambahkan FK `kelas_id` di tabel `siswa` mengarah ke `kelas` (dipisah jadi migration sendiri karena urutan dependency)
6. `mapel`
7. `mengajar` (FK ke `guru`, `mapel`, `kelas`)

Semua kolom persis sesuai `docs/01-schema.md`. Jangan tambah kolom yang tidak disebutkan di sana, jangan hilangkan kolom yang disebutkan.

Jalankan `php artisan migrate`.

**Cek sebelum lanjut:** buka database, pastikan 6 tabel di atas ada dengan kolom dan FK yang benar.

---

## Tahap 3 — Migration Modul Inti + Rekap

Baca `docs/01-schema.md` bagian "Modul Inti" dan "Rekap".

Buat migration untuk:
1. `jadwal` (FK ke `mengajar`)
2. `nilai` (FK ke `siswa`, `mengajar`, `users`; **UNIQUE KEY `(siswa_id, mengajar_id, jenis)` wajib ada**)
3. `absensi` (FK ke `siswa`, `mengajar`; **UNIQUE KEY `(siswa_id, mengajar_id, tanggal)` wajib ada**)
4. `rekap_nilai` (**UNIQUE KEY `(siswa_id, mengajar_id, semester)` wajib ada**)
5. `rekap_absensi` (**UNIQUE KEY `(siswa_id, mengajar_id, semester)` wajib ada**)
6. `log_perubahan` (kolom: tabel, record_id, aksi ENUM, user_id FK, waktu, data_lama JSON, data_baru JSON)

Jalankan `php artisan migrate`.

**Cek sebelum lanjut:** buka database, pastikan semua UNIQUE KEY di atas benar-benar ada (cek tab "Indexes"). Kalau ada yang hilang, SQL di Tahap 4 akan gagal total — perbaiki dulu di sini sebelum lanjut.

---

## Tahap 4 — Jalankan SQL Objects

Baca `docs/02-sql-objects.sql` secara utuh.

Jalankan file itu LANGSUNG ke database (lewat command line `mysql` atau tool database apa pun yang tersedia). JANGAN menyalin ulang isinya ke migration Laravel. JANGAN menerjemahkan logikanya ke Eloquent.

**Cek sebelum lanjut:** jalankan query verifikasi yang tertulis di akhir `docs/02-sql-objects.sql` (`SHOW FUNCTION STATUS`, `SHOW PROCEDURE STATUS`, `SHOW TRIGGERS`). Semua 2 function, 2 procedure, 4 trigger harus muncul. Kalau ada yang tidak muncul, JANGAN lanjut — cari tahu kenapa gagal.

---

## Tahap 5 — Model & Middleware

1. Buat model untuk semua tabel: `User` (biasanya sudah ada dari Breeze), `Guru`, `Siswa`, `Kelas`, `Mapel`, `Mengajar`, `Jadwal`, `Nilai`, `RekapNilai`, `Absensi`, `RekapAbsensi`
2. Tambahkan relasi Eloquent (`belongsTo`/`hasMany`) sesuai FK di `docs/01-schema.md` — untuk tiap model, relasi yang jelas dari nama kolom FK-nya (misal `Siswa` punya `belongsTo(Kelas::class)` lewat `kelas_id`)
3. Buat middleware role: baca contoh lengkap di `docs/03-conventions.md` bagian "Middleware Role", buat file `app/Http/Middleware/EnsureUserHasRole.php` persis seperti contoh
4. Daftarkan middleware dengan alias `role` (caranya tergantung versi Laravel — cek `bootstrap/app.php` untuk Laravel 11, atau `app/Http/Kernel.php` untuk versi lebih lama)

**Cek sebelum lanjut:** middleware terdaftar tanpa error saat `php artisan route:list` dijalankan.

---

## Tahap 6 — Routes

Baca `docs/05-routes.md` secara utuh.

Buat struktur route group (admin/guru/siswa) persis seperti contoh di file itu, di `routes/web.php`. Isi SEMUA route yang terdaftar di file itu sekarang — meski controller-nya belum dibuat (nanti akan error 404/500 sementara sampai Tahap 7-10 selesai, itu wajar).

**Cek sebelum lanjut:** `php artisan route:list` menampilkan semua route dari `docs/05-routes.md` tanpa error syntax.

---

## Tahap 7 — Auth & Dashboard Redirect

Baca `docs/08-user-flows.md` bagian menu dashboard tiap role sebelum mulai.

1. Pastikan login/register dari Breeze berfungsi (buat 1 user percobaan lewat `php artisan tinker` atau seeder sementara)
2. Buat controller/logic untuk `/dashboard` — setelah login, cek `auth()->user()->role`, arahkan ke view dashboard yang sesuai (`admin.dashboard`, `guru.dashboard`, atau `siswa.dashboard`)
3. Buat 3 file dashboard (`resources/views/admin/dashboard.blade.php`, `guru/dashboard.blade.php`, `siswa/dashboard.blade.php`) memakai **Pola 3 (Dashboard)** dari `docs/07-layout-patterns.md`. Isi link menu sesuai role:
   - Admin: Manajemen User, Kelas, Mapel, Mengajar, Jadwal
   - Guru: Input Nilai, Input Absensi, Jadwal Mengajar
   - Siswa: Lihat Nilai, Lihat Absensi, Lihat Jadwal

**Cek sebelum lanjut:** login sebagai user percobaan, pastikan redirect ke dashboard yang benar sesuai role.

---

## Tahap 8 — Component Library

Baca `docs/04-design-system.md` secara utuh.

Buat semua file component yang tertulis di situ, persis seperti kode yang diberikan (jangan modifikasi):
- `layouts/app.blade.php`
- `components/navbar.blade.php`
- `components/button.blade.php`
- `components/card.blade.php`
- `components/form-input.blade.php`
- `components/form-select.blade.php`
- `components/table.blade.php`
- `components/badge.blade.php`
- `components/alert.blade.php`
- `components/dashboard-link.blade.php`

Setup juga `tailwind.config.js` sesuai kode di file yang sama.

**Cek sebelum lanjut:** halaman dashboard dari Tahap 7 tampil dengan styling yang benar (bukan HTML polos tanpa CSS).

---

## Tahap 9 — Modul Admin (CRUD Master Data)

Untuk SETIAP tabel berikut — User, Kelas, Mapel, Mengajar, Jadwal — kerjakan urutan yang sama:

1. Buat controller resource: `Admin\{Nama}Controller`
2. Isi method `index`, `create`, `store`, `edit`, `update`, `destroy` dengan Eloquent biasa (tidak ada logika kompleks di modul ini, murni CRUD)
3. Untuk `User`: tambahkan `Hash::make()` untuk password, validasi email `unique`
4. Untuk `Mengajar` dan `Jadwal`: dropdown pilih relasi (guru/mapel/kelas untuk Mengajar; mengajar untuk Jadwal) HARUS menampilkan nama yang jelas, bukan angka id
5. Buat 3 view per modul: `index.blade.php` (**Pola 1**), `create.blade.php` (**Pola 2**), `edit.blade.php` (**Pola 2**)

Urutan pengerjaan 5 modul ini: User → Kelas → Mapel → Mengajar → Jadwal (urutan ini mengikuti dependency data — Mengajar butuh Guru/Mapel/Kelas sudah ada datanya untuk dropdown, Jadwal butuh Mengajar).

**Cek sebelum lanjut setiap modul:** login sebagai admin, buka halaman index, coba tambah 1 data percobaan, edit, hapus — semua harus berhasil tanpa error sebelum lanjut ke modul berikutnya.

---

## Tahap 10 — Seeder Data Testing

Buat 1 seeder (`AkademikSeeder`) yang mengisi:
- 1 user + guru dengan role `admin`
- 2 user + guru dengan role `guru`, masing-masing punya minimal 1 baris di `mengajar`
- 1 kelas berisi 8-10 siswa (user + siswa dengan role `siswa`)
- 2 mapel

Jalankan `php artisan db:seed --class=AkademikSeeder`.

**Cek sebelum lanjut:** semua data di atas benar-benar masuk ke database, dan bisa dipakai login (email/password yang jelas, dicatat untuk dipakai testing di tahap berikutnya).

---

## Tahap 11 — Modul Nilai & Absensi (Inti)

Baca `docs/03-conventions.md` bagian "Transaction Handling" dan `docs/08-user-flows.md` bagian "Alur Guru" secara utuh sebelum mulai — ini tahap paling kritis di seluruh project. Pastikan urutan klik yang diikuti controller/view benar-benar sesuai langkah di "Alur Guru": pilih kelas dulu → baru tampil tabel siswa → baru submit sekaligus (bukan submit satu-satu per siswa).

### Modul Nilai

1. Buat model `Nilai`, `RekapNilai` dengan relasi
2. Buat `Guru\NilaiController` — method `index()` (list mengajar milik guru login), `form($mengajarId)` (ambil siswa di kelas itu), `store()` (PERSIS seperti kode contoh di `docs/03-conventions.md`, termasuk cross-check siswa-kelas SEBELUM transaction)
3. Buat `Siswa\NilaiController` — method `index()` (ambil nilai + rekap milik siswa login, dengan fallback ke `fn_rata_rata_nilai` kalau rekap kosong)
4. Buat view `guru/nilai/index.blade.php` (**Pola 1**), `guru/nilai/form.blade.php` (**Pola 2, varian tabel**), `siswa/nilai/index.blade.php` (**Pola 4**)

### Modul Absensi

1. Buat model `Absensi`, `RekapAbsensi` dengan relasi
2. Buat `Guru\AbsensiController` — method `index()`, `form($mengajarId, tanggal)`, `store()` (PERSIS seperti kode contoh di `docs/03-conventions.md` — insert lewat `Absensi::create()`, BUKAN lewat procedure)
3. Buat `Siswa\AbsensiController` — method `index()` (ambil rekap milik siswa login, fallback ke `fn_persentase_hadir`)
4. Buat view `guru/absensi/index.blade.php` (**Pola 1**), `guru/absensi/form.blade.php` (**Pola 2, varian tabel**), `siswa/absensi/index.blade.php` (**Pola 4**)

**Cek sebelum lanjut (WAJIB, jangan skip satu pun):**
1. Login guru, input nilai untuk 3-4 siswa di 1 kelas, submit — cek berhasil dan masuk ke tabel `nilai` DAN `rekap_nilai`
2. Edit salah satu nilai yang baru diinput (submit ulang dengan angka beda) — cek `rekap_nilai` ikut berubah (ini menguji `trg_rekap_nilai_update`)
3. Coba input nilai di luar rentang 0-100 — harus ditolak dengan pesan error, tidak masuk ke database
4. Login siswa dari kelas yang sama, buka halaman nilai — pastikan nilai yang diinput tadi muncul dengan rata-rata yang benar
5. Login guru, input absensi untuk 1 kelas di 1 tanggal — cek masuk ke `absensi` DAN `rekap_absensi` (ini menguji `trg_absensi_insert` memanggil `sp_rekap_absensi` otomatis)
6. Login siswa yang sama, buka halaman absensi — pastikan rekap muncul dengan persentase yang benar
7. Coba akses `/guru/nilai` atau `/guru/absensi` dengan login sebagai siswa — harus ditolak (403)

Kalau ada satu saja dari 7 poin ini gagal, JANGAN lanjut ke Tahap 12 — ini modul paling penting di seluruh project.

---

## Tahap 12 — Modul Jadwal (Read-Only) & Finalisasi

1. Buat model `Jadwal` dengan relasi (kemungkinan sudah dibuat sebagian di Tahap 9 untuk CRUD admin)
2. Buat `Guru\JadwalController@index` — jadwal milik guru login saja
3. Buat `Siswa\JadwalController@index` — jadwal kelas siswa login saja
4. Buat view `guru/jadwal/index.blade.php` dan `siswa/jadwal/index.blade.php`, keduanya **Pola 1** tanpa tombol aksi (read-only)

### Testing Akhir Menyeluruh

Baca `docs/08-user-flows.md` secara utuh, lalu jalankan persis alur di situ untuk tiap role (bukan menguji tiap halaman secara terpisah dan acak):
1. Login admin → jalankan urutan lengkap "Alur Admin" (User → Kelas → Mapel → Mengajar → Jadwal)
2. Login guru → jalankan urutan lengkap "Alur Guru" (input nilai, input absensi, lihat jadwal)
3. Login siswa → jalankan urutan lengkap "Alur Siswa" (lihat nilai, lihat absensi, lihat jadwal)
4. Cek semua halaman secara visual — pastikan semua memakai kerangka dari `docs/07-layout-patterns.md` dan token dari `docs/04-design-system.md` secara konsisten (tidak ada halaman yang "terasa beda gaya" dari halaman lain)

Kalau ada langkah di alur yang gagal (misal dashboard guru kosong tanpa kelas), cek dulu bagian "Titik Kritis" di `docs/08-user-flows.md` sebelum menelusuri kode — kemungkinan besar penyebabnya data admin di langkah 1 belum lengkap, bukan bug di modul guru/siswa.

**Project dianggap selesai** kalau semua poin di atas berhasil tanpa error, dan seluruh tampilan konsisten mengikuti design system serta layout patterns yang sudah ditentukan.
