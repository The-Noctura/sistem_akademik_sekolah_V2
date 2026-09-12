# Dokumentasi Struktur, Flow, dan Fitur Sistem Akademik Sekolah V2

Dokumen ini dibuat berdasarkan kondisi project saat ini di working tree yang sedang aktif (setelah beberapa patch dan penambahan fitur yang belum di-commit). Tujuannya adalah merangkum struktur file yang sudah dibuat, flow aplikasi per role, fitur yang ditambahkan, serta penjelasan letak trigger, function, dan procedure yang dipakai untuk menjaga integritas data.

---

## 1. Ringkasan Project yang Sudah Dibangun

Project ini adalah aplikasi sekolah berbasis Laravel + Blade + MySQL. Fokus utama sistem adalah:

- login dan role-based access
- admin mengelola master data
- guru input nilai dan absensi
- siswa melihat nilai, absensi, dan jadwal
- public website sekolah
- berita dan katalog TEFA
- dashboard yang berbeda per role

### Struktur folder inti yang sudah dibuat/ditambahkan

- `app/Http/Controllers/`
  - `DashboardController.php`
  - `PublicController.php`
  - `Admin/UserController.php`
  - `Admin/JadwalController.php`
  - `Admin/BeritaController.php` (baru)
  - `Admin/TefaProductController.php` (baru)
  - `Guru/NilaiController.php`
  - `Guru/AbsensiController.php`
  - `Guru/JadwalController.php`
  - `Siswa/NilaiController.php`
  - `Siswa/AbsensiController.php`
  - `Siswa/JadwalController.php`
- `app/Models/`
  - `Berita.php`
  - `TefaProduct.php`
- `database/migrations/`
  - `2026_09_12_000001_create_tefa_products_table.php`
  - `2026_09_12_000002_create_berita_table.php`
- `database/seeders/`
  - `DatabaseSeeder.php`
  - `TefaAndBeritaSeeder.php`
- `resources/views/`
  - `layouts/app.blade.php`
  - `layouts/public.blade.php`
  - `public/home.blade.php`
  - `public/news.blade.php`
  - `public/news-show.blade.php`
  - `public/tefa.blade.php`
  - `admin/dashboard.blade.php`
  - `admin/users/*`
  - `admin/berita/*`
  - `admin/tefa/*`
  - `guru/dashboard.blade.php`
  - `guru/nilai/*`
  - `guru/absensi/*`
- `routes/web.php`
- `docs/02-sql-objects.sql`

---

## 2. Flow Aplikasi Secara Umum

### 2.1 Flow login dan autentikasi

Flow login dimulai dari route publik:

- `routes/web.php` register route publik seperti `/`, `/berita`, `/produk-tefa`, `/program-keahlian`, dll.
- route login dan logout ada di `routes/auth.php`.
- `Auth::attempt()` diproses lewat `AuthenticatedSessionController`.
- setelah login sukses, aplikasi redirect ke `/dashboard`.
- `DashboardController@index` memeriksa `auth()->user()->role` dan menampilkan dashboard yang sesuai role.

Urutan alurnya secara sederhana:

1. user masuk ke `/login`
2. `AuthenticatedSessionController@create` menampilkan halaman login
3. form login dikirim ke `AuthenticatedSessionController@store`
4. `LoginRequest` memvalidasi email/password
5. jika valid, session dibuat dan redirect ke dashboard
6. `DashboardController@index` membuat view sesuai role

### 2.2 Flow role-based access

Akses dibatasi oleh middleware `role:admin`, `role:guru`, `role:siswa` di `routes/web.php`.

- admin hanya bisa masuk ke prefix `/admin`
- guru hanya bisa masuk ke prefix `/guru`
- siswa hanya bisa masuk ke prefix `/siswa`
- halaman public tetap terbuka tanpa login

Middleware ini otomatis memvalidasi:

- apakah user sudah login
- apakah role cocok
- apakah status user aktif atau nonaktif

Jika role tidak sesuai, sistem akan `abort(403)`. Jika status user nonaktif, sistem logout otomatis dan menolak akses.

---

## 3. Alur Role Admin

### 3.1 Fitur yang bisa diakses admin

Admin memiliki akses ke:

- manajemen user
- manajemen kelas
- manajemen mapel
- manajemen mengajar
- manajemen jadwal
- manajemen berita
- manajemen produk TEFA
- dashboard admin

Referensi utama:

- `routes/web.php`
- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Controllers/Admin/JadwalController.php`
- `app/Models/User.php`
- `resources/views/admin/*`

### 3.2 Flow admin user management

Alur user:

1. admin membuka `/admin/users`
2. `UserController@index` memanggil `User::with(['guru', 'siswa.kelas'])->latest()->paginate(15)`
3. data user ditampilkan di `resources/views/admin/users/index.blade.php`
4. admin bisa create, edit, atau menonaktifkan user
5. saat create/update, controller melakukan validasi email unik, password confirm, role, dan field tambahan
6. jika role = guru, maka data guru dibuat ke tabel `guru`
7. jika role = siswa, maka data siswa dibuat ke tabel `siswa`
8. untuk soft delete/disable, controller hanya mengubah `status` dari `aktif` ke `nonaktif` tanpa menghapus data fisik

### 3.3 Flow admin kelas, mapel, mengajar, jadwal

- `kelas`: admin bisa CRUD data kelas, memilih wali kelas dari data guru
- `mapel`: admin CRUD mata pelajaran
- `mengajar`: admin membuat relasi antara guru, mapel, kelas, semester, dan tahun ajaran
- `jadwal`: admin membuat jadwal mengajar dengan validasi jam dan bentrok

Flow umum jadwal:

1. admin membuka `admin/jadwal`
2. pilih `mengajar` dari daftar yang ada
3. masukkan hari, jam mulai, jam selesai, ruangan
4. controller memvalidasi:
   - jam selesai > jam mulai
   - guru tidak bentrok di hari yang sama
   - ruangan tidak bentrok
5. jika lolos, data masuk ke tabel `jadwal`

### 3.4 Flow admin berita dan TEFA

Penambahan modul baru ini sudah dibuat untuk halaman public sekolah:

- berita ditampilkan di halaman public dan dashboard admin
- TEFA product ditampilkan di katalog produk public

Source utama:

- `app/Http/Controllers/Admin/BeritaController.php`
- `app/Http/Controllers/Admin/TefaProductController.php`
- `app/Models/Berita.php`
- `app/Models/TefaProduct.php`
- `resources/views/admin/berita/*`
- `resources/views/admin/tefa/*`

---

## 4. Alur Role Guru

### 4.1 Fitur yang bisa diakses guru

Guru memiliki akses ke:

- dashboard guru
- input nilai
- input absensi
- melihat jadwal mengajar
- melihat kelas yang diampu

Referensi:

- `routes/web.php`
- `app/Http/Controllers/Guru/NilaiController.php`
- `app/Http/Controllers/Guru/AbsensiController.php`
- `app/Http/Controllers/Guru/JadwalController.php`
- `resources/views/guru/*`

### 4.2 Flow dashboard guru

Saat guru login:

1. `DashboardController@index` membaca `Auth::user()->guru`
2. script mengambil daftar `mengajar` milik guru
3. statistik dihitung seperti:
   - jumlah kelas diampu
   - jumlah mapel
   - jumlah siswa di kelas diajar
4. view `resources/views/guru/dashboard.blade.php` menampilkan saldo informasi dan menu utama

### 4.3 Flow input nilai guru

1. guru masuk ke `/guru/nilai`
2. `NilaiController@index` menampilkan list mata pelajaran yang diajar
3. klik salah satu `mengajar` lalu masuk ke form nilai
4. controller `NilaiController@form` menampilkan semua siswa di kelas tersebut
5. guru mengisi nilai untuk jenis `tugas`, `uts`, atau `uas`
6. sebelum insert, controller mengecek cross-check siswa-kelas
7. data disimpan via perulangan dan `DB::statement('CALL sp_input_nilai_kelas(...)')`
8. nilai terakhir akan otomatis mengupdate rekap di tabel `rekap_nilai` karena trigger SQL bekerja setelah `INSERT` atau `UPDATE` di tabel `nilai`

Fungsi yang berhubungan:

- `sp_input_nilai_kelas` di `docs/02-sql-objects.sql`
- `fn_rata_rata_nilai` di `docs/02-sql-objects.sql`
- trigger `trg_rekap_nilai_insert`
- trigger `trg_rekap_nilai_update`

### 4.4 Flow input absensi guru

1. guru masuk ke `/guru/absensi`
2. `AbsensiController@index` menampilkan list kelas yang diajar
3. form absensi menampilkan siswa di kelas tertentu untuk tanggal tertentu
4. guru memilih status: hadir, izin, sakit, alpa
5. controller memvalidasi setiap siswa masuk kelas yang sama
6. proses dilakukan dalam transaction `DB::beginTransaction()`
7. data `absensi` di `updateOrCreate()`
8. setelah insert, controller memanggil `CALL sp_rekap_absensi(...)`
9. table `rekap_absensi` otomatis diperbarui

Fungsi/procedure yang berhubungan:

- `sp_rekap_absensi`
- `trg_absensi_insert`
- `fn_persentase_hadir`

### 4.5 Flow jadwal guru

- guru hanya bisa melihat jadwal, tidak bisa membuat/ubah dari halaman guru
- data jadwal yang tampil dibatasi hanya `mengajar` milik guru tersebut
- controller `Guru/JadwalController@index` memfilter berdasarkan `guru_id`

---

## 5. Alur Role Siswa

### 5.1 Fitur yang bisa diakses siswa

Siswa dapat mengakses:

- dashboard siswa
- lihat nilai
- lihat absensi
- lihat jadwal pelajaran

Referensi:

- `routes/web.php`
- `app/Http/Controllers/Siswa/NilaiController.php`
- `app/Http/Controllers/Siswa/AbsensiController.php`
- `app/Http/Controllers/Siswa/JadwalController.php`
- `resources/views/siswa/*`

### 5.2 Flow dashboard siswa

Ketika siswa login:

1. `DashboardController@index` membaca `Auth::user()->siswa`
2. sistem menentukan kelas siswa
3. data rekap nilai dan absensi diambil dari tabel `rekap_nilai` dan `rekap_absensi`
4. dashboard menampilkan rata-rata dan persentase kehadiran

### 5.3 Flow melihat nilai siswa

1. siswa masuk ke `/siswa/nilai`
2. `Siswa/NilaiController@index` mengambil semua `mengajar` berdasarkan kelas siswa
3. data nilai ditampilkan per mapel dan jenis nilai
4. siswa hanya bisa melihat data miliknya sendiri
5. tidak ada tombol input atau edit sesuai role

### 5.4 Flow melihat absensi siswa

1. siswa membuka `/siswa/absensi`
2. `AbsensiController@index` mengambil data absensi berdasarkan `siswa_id` dan `kelas_id`
3. data disajikan dalam bentuk ringkasan per mapel dan tabel detail per tanggal
4. status hadir/izin/sakit/alpa ditampilkan dengan badge color

### 5.5 Flow melihat jadwal siswa

1. siswa membuka `/siswa/jadwal`
2. `Siswa/JadwalController@index` menampilkan jadwal berdasarkan kelas siswa
3. jadwal dikelompokkan per hari
4. siswa hanya melihat jadwal pengajarannya sendiri

---

## 6. Alur Halaman Public

Halaman public dibuka tanpa login. Daftar route public ada di `routes/web.php`:

- `/` → landing page sekolah
- `/tentang`
- `/program-keahlian`
- `/program-keahlian/{code}`
- `/fasilitas`
- `/berita`
- `/berita/{slug}`
- `/produk-tefa`
- `/kontak`

### 6.1 Flow public homepage

`PublicController@home`:

1. mengambil data program jurusan
2. mengambil berita publikasi terbaru
3. mengambil produk TEFA yang masih tersedia
4. menampilkan halaman depan di `resources/views/public/home.blade.php`

### 6.2 Flow berita public

- `PublicController@news` menampilkan daftar berita publikasi dengan filter kategori
- `PublicController@newsShow` menampilkan detail berita berdasarkan slug
- `Berita::increment('views')` dipanggil saat detail dibuka

### 6.3 Flow TEFA public

- `PublicController@tefa` mengambil daftar produk TEFA dari tabel `tefa_products`
- data disaring berdasarkan jurusan bila query `jurusan` ada
- hasil ditampilkan di `resources/views/public/tefa.blade.php`

### 6.4 Flow login public

Login masuk melalui `routes/auth.php` dan `AuthenticatedSessionController`.

- route `login` hanya untuk user guest
- setelah login berhasil, redirect ke dashboard
- route `logout` hanya aktif untuk user authenticated

---

## 7. Trigger, Function, Procedure: Letaknya di Mana?

Bagian ini penting karena banyak user sering bingung: “apa yang sebenarnya berada di SQL dan apa yang berada di Laravel?”

### 7.1 Jawaban singkat

Trigger, function, dan procedure-nya berada di database MySQL, bukan di controller Laravel.

Lokasi utama:

- `docs/02-sql-objects.sql`

Penggunaan di Laravel:

- `DB::statement('CALL sp_input_nilai_kelas(...)')`
- `DB::statement('CALL sp_rekap_absensi(...)')`
- `DB::selectOne('SELECT fn_rata_rata_nilai(...) as rata')`

Jadi, controller hanya memanggil SQL object, bukan menulis ulang logika SQL di PHP.

### 7.2 Function yang ada

Di `docs/02-sql-objects.sql`:

- `fn_rata_rata_nilai(p_siswa_id, p_mengajar_id)`
  - menghitung rata-rata nilai siswa untuk satu mapel tertentu
- `fn_persentase_hadir(p_siswa_id, p_mengajar_id)`
  - menghitung persentase kehadiran siswa

Penggunaannya di Laravel:

- `app/Http/Controllers/Guru/NilaiController.php`
- `app/Http/Controllers/Siswa/NilaiController.php`
- `app/Http/Controllers/Siswa/AbsensiController.php`

### 7.3 Procedure yang ada

Di `docs/02-sql-objects.sql`:

- `sp_input_nilai_kelas`
  - used to insert/update nilai dengan `ON DUPLICATE KEY UPDATE`
- `sp_rekap_absensi`
  - menghitung jumlah hadir, izin, sakit, alpa dan update `rekap_absensi`

Yang memanggil procedure:

- `app/Http/Controllers/Guru/NilaiController.php`
- `app/Http/Controllers/Guru/AbsensiController.php`

### 7.4 Trigger yang ada

Trigger didefinisikan di database MySQL di `docs/02-sql-objects.sql`, dan otomatis bekerja saat table terkait berubah.

Trigger utama:

1. `trg_rekap_nilai_insert`
   - setelah insert ke `nilai`, otomatis update `rekap_nilai`
2. `trg_rekap_nilai_update`
   - setelah update di `nilai`, otomatis update `rekap_nilai`
3. `trg_log_nilai_update`
   - mencatat perubahan nilai ke `log_perubahan`
4. `trg_absensi_insert`
   - setelah insert ke `absensi`, otomatis menjalankan `sp_rekap_absensi`

Keterangan penting:

- trigger ini tidak ada di folder `app/` atau `resources/`
- trigger ini hidup di MySQL database, jadi kalau ada masalah, harus dicek langsung di DB
- jika trigger tidak aktif, rekap nilai/absensi bisa “mati” meski form input berhasil

---

## 8. Relasi tabel penting

Relasi utama dalam sistem:

- `users` 1-to-1 dengan `guru`
- `users` 1-to-1 dengan `siswa`
- `guru` 1-to-many dengan `mengajar`
- `mapel` 1-to-many dengan `mengajar`
- `kelas` 1-to-many dengan `mengajar`
- `mengajar` 1-to-many dengan `jadwal`
- `mengajar` 1-to-many dengan `nilai`
- `siswa` 1-to-many dengan `nilai`
- `mengajar` 1-to-many dengan `absensi`
- `siswa` 1-to-many dengan `absensi`
- `rekap_nilai` dan `rekap_absensi` merangkum hasil dari `nilai` dan `absensi`

Pada level database, foreign key dan validasi cross-check sangat penting karena skema sendiri tidak cukup menjamin kombinasi siswa/kelas yang benar. Oleh karena itu controller wajib melakukan validasi manual sebelum insert ke `nilai` dan `absensi`.

---

## 9. Flow Data dari UI ke Database

### 9.1 Flow nilai

- guru buka halaman input nilai
- controller `Guru/NilaiController@store`
- validasi tipe dan nilai
- validasi siswa milik kelas yang sama
- `DB::beginTransaction()`
- `CALL sp_input_nilai_kelas(...)`
- `INSERT/UPDATE` ke `nilai`
- trigger update `rekap_nilai`
- `DB::commit()`

### 9.2 Flow absensi

- guru buka halaman absensi
- controller `Guru/AbsensiController@store`
- validasi kelas siswa
- `DB::beginTransaction()`
- `Absensi::updateOrCreate(...)`
- `CALL sp_rekap_absensi(...)`
- data masuk ke `absensi` dan `rekap_absensi`
- `DB::commit()`

### 9.3 Flow jadwal

- admin membuat jadwal via form
- data masuk ke `jadwal`
- guru dan siswa membaca data dari tabel `jadwal` yang terkait kelas/guru

---

## 10. Fitur Baru yang Sudah Ditambahkan Ke Project Saat Ini

Berikut rincian fitur yang baru/ditambahkan dalam patch yang saat ini masih aktif:

- public landing page sekolah
- halaman berita publik
- halaman detail berita
- katalog TEFA public
- dashboard admin dengan stat cards
- dashboard guru dan siswa yang lebih rapi
- login/logout flow yang jelas
- user management improvements
- banyak perbaikan layout dan konsistensi Blade
- penambahan `Berita` dan `TefaProduct` model + migration + seeder

---

## 11. Daftar Testing yang Sudah / Harus Dilakukan

### 11.1 Testing berdasarkan role

#### Admin
Lokasi testing:

- browser di `http://127.0.0.1:8000` atau port yang aktif
- URL utama:
  - `/login`
  - `/dashboard`
  - `/admin/users`
  - `/admin/kelas`
  - `/admin/mapel`
  - `/admin/mengajar`
  - `/admin/jadwal`
  - `/admin/berita`
  - `/admin/tefa`

Akun testing:

- admin@sekolah.test / password

Yang diuji:

- login admin
- dashboard stat card
- CRUD user
- CRUD kelas
- CRUD mapel
- CRUD mengajar
- validasi duplikat kombinasi guru-mapel-kelas
- validasi bentrok jadwal
- create/update berita dan produk TEFA

#### Guru
Lokasi testing:

- `/login`
- `/dashboard`
- `/guru/nilai`
- `/guru/absensi`
- `/guru/jadwal`

Akun testing:

- budi@sekolah.test / password
- siti@sekolah.test / password

Yang diuji:

- login sebagai guru
- akses yang dibatasi ke role yang benar
- input nilai per kelas
- input absensi per kelas
- baca jadwal mengajar
- validasi siswa bukan kelas yang diajar
- update/rekap otomatis

#### Siswa
Lokasi testing:

- `/login`
- `/dashboard`
- `/siswa/nilai`
- `/siswa/absensi`
- `/siswa/jadwal`

Akun testing:

- siswa1001@sekolah.test / password

Yang diuji:

- login siswa
- dashboard siswa
- read-only nilai
- read-only absensi
- read-only jadwal
- keamanan akses role

### 11.2 Testing teknis database

Lokasi testing:

- MySQL / phpMyAdmin / terminal MySQL
- database project aktif

Yang diuji:

- cek tabel `nilai`, `absensi`, `rekap_nilai`, `rekap_absensi`, `log_perubahan`
- cek fungsi SQL `fn_rata_rata_nilai`
- cek fungsi SQL `fn_persentase_hadir`
- cek procedure `sp_input_nilai_kelas` dan `sp_rekap_absensi`
- cek trigger `trg_rekap_nilai_insert`, `trg_rekap_nilai_update`, `trg_absensi_insert`, `trg_log_nilai_update`

### 11.3 Testing dari terminal Laravel

Command yang bisa dipakai:

- `php artisan route:list`
- `php artisan migrate`
- `php artisan db:seed`
- `php -l resources/views/...`

Fungsi:

- memastikan route active
- memastikan database schema valid
- memastikan Blade template tidak syntax error

---

## 12. Akun Demo Testing yang Valid

| Role | Email | Password |
|---|---|---|
| Admin | admin@sekolah.test | password |
| Guru 1 | budi@sekolah.test | password |
| Guru 2 | siti@sekolah.test | password |
| Siswa | siswa1001@sekolah.test | password |

---

## 13. Kesimpulan

Sistem ini sudah dibangun sebagai aplikasi akademik multi-role yang berfungsi secara terstruktur:

- admin mengelola data utama
- guru menjalankan operasional akademik
- siswa melihat data mereka sendiri
- public website menyediakan informasi sekolah dan berita
- database melakukan sinkronisasi rekap otomatis melalui trigger dan procedure SQL

Dengan pemisahan yang jelas antara logic aplikasi di Laravel dan logika database di SQL, sistem ini relatif aman, konsisten, dan siap untuk dikembangkan lebih lanjut bila ada kebutuhan revisi atau penambahan fitur di masa depan.

---

## 14. Referensi file utama

- `routes/web.php`
- `routes/auth.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/PublicController.php`
- `app/Http/Controllers/Guru/NilaiController.php`
- `app/Http/Controllers/Guru/AbsensiController.php`
- `app/Http/Controllers/Admin/UserController.php`
- `docs/02-sql-objects.sql`
- `database/seeders/AkademikSeeder.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/public.blade.php`
