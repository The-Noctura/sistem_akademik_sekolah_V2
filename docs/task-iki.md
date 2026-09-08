# Task Iki — Auth & Master Data

**Modul:** Auth (login/role) + Master Data (users, siswa, guru, kelas, mapel, mengajar) + CRUD Admin
**Acuan:** `pembagian-tugas-laravel.md`, `laravel-docs/02-tech-conventions.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/04-routes-and-structure.md`

---

## H1 (Senin) — Sinkronisasi

- [ ] Ikut sesi sinkronisasi bareng tim
- [ ] Buka `laravel-docs/03-database-schema.md`, baca bagian tabel Master Data dan Relasi Pengajaran
- [ ] Konfirmasi ke tim: tidak ada perubahan skema dari yang tertulis di file
- [ ] Buat folder project baru di komputer, kasih nama (misal `sistem-akademik`)
- [ ] Jalankan:

```bash
composer create-project laravel/laravel sistem-akademik
```

- [ ] Masuk ke folder project:

```bash
cd sistem-akademik
```

- [ ] Setup file `.env` — isi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` sesuai MySQL lokal
- [ ] Buat database baru di MySQL dengan nama yang sama seperti di `.env` (pakai phpMyAdmin/TablePlus/command line, terserah)
- [ ] Test koneksi database, jalankan:

```bash
php artisan migrate
```

- [ ] Pastikan tidak ada error (ini akan bikin tabel bawaan Laravel seperti `migrations`, `sessions`, dll)
- [ ] Push project ke repo Git bareng (GitHub/GitLab, sesuai yang disepakati tim)
- [ ] Broadcast ke tim: link repo sudah bisa di-clone

### Install Laravel Breeze (Auth Scaffolding)

- [x] Jalankan:

```bash
composer require laravel/breeze --dev
```

- [x] Jalankan:

```bash
php artisan breeze:install blade
```

- [x] Kalau ditanya pilihan di terminal, pilih opsi Blade (bukan React/Vue)
- [x] Jalankan:

```bash
npm install
```

- [x] Jalankan:

```bash
npm run build
```

- [x] Jalankan lagi:

```bash
php artisan migrate
```

- [x] Coba buka `php artisan serve`, akses `/login` dan `/register` di browser, pastikan halamannya muncul (belum perlu bisa login sukses, cuma cek halamannya render)

---

## H2 (Selasa) — Migration Inti

### Migration `users` (tambahan kolom di luar bawaan Breeze)

- [x] Buka folder `database/migrations/`, cari file yang namanya mengandung `create_users_table`
- [x] Buka file tersebut
- [x] Di dalam method `up()`, cari baris `$table->id();` dan tambahkan persis di bawahnya:

```php
$table->string('nama');
$table->enum('role', ['admin', 'guru', 'siswa']);
```

- [x] Simpan file
- [x] Jalankan:

```bash
php artisan migrate:fresh
```

- [x] Cek tidak ada error merah di terminal
- [x] Buka database, cek tabel `users` sudah punya kolom `nama` dan `role`

### Migration `guru`

- [x] Jalankan:

```bash
php artisan make:migration create_guru_table
```

- [x] Buka file migration yang baru dibuat di `database/migrations/`
- [x] Di dalam method `up()`, tulis persis:

```php
Schema::create('guru', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users');
    $table->string('nip');
    $table->string('nama');
    $table->string('no_hp')->nullable();
    $table->timestamps();
});
```

- [x] Simpan file

### Migration `siswa`

- [x] Jalankan:

```bash
php artisan make:migration create_siswa_table
```

- [x] Buka file migration yang baru dibuat
- [x] Di dalam method `up()`, tulis persis (kolom `kelas_id` diisi nanti setelah tabel `kelas` ada — untuk sekarang jangan constraint dulu ke `kelas`, cukup kolom biasa):

```php
Schema::create('siswa', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users');
    $table->string('nis');
    $table->string('nama');
    $table->unsignedBigInteger('kelas_id')->nullable();
    $table->string('jenis_kelamin');
    $table->date('tanggal_lahir');
    $table->timestamps();
});
```

- [x] Simpan file

### Migration `kelas`

- [x] Jalankan:

```bash
php artisan make:migration create_kelas_table
```

- [x] Buka file migration yang baru dibuat
- [x] Di dalam method `up()`, tulis persis:

```php
Schema::create('kelas', function (Blueprint $table) {
    $table->id();
    $table->string('nama_kelas');
    $table->string('tingkat');
    $table->foreignId('wali_kelas_id')->nullable()->constrained('guru');
    $table->string('tahun_ajaran');
    $table->timestamps();
});
```

- [x] Simpan file

### Tambah foreign key `kelas_id` ke tabel `siswa`

- [x] Jalankan:

```bash
php artisan make:migration add_kelas_foreign_to_siswa_table
```

- [x] Buka file migration yang baru dibuat
- [x] Di dalam method `up()`, tulis persis:

```php
Schema::table('siswa', function (Blueprint $table) {
    $table->foreign('kelas_id')->references('id')->on('kelas');
});
```

- [x] Simpan file
- [x] **Catatan kenapa dipisah:** ini supaya urutan migration tidak error — `kelas` butuh `guru` sudah ada duluan, sementara `siswa` dibuat sebelum `kelas`. Menambah foreign key belakangan di file terpisah menghindari masalah urutan.

### Migration `mapel`

- [x] Jalankan:

```bash
php artisan make:migration create_mapel_table
```

- [x] Buka file migration yang baru dibuat
- [x] Di dalam method `up()`, tulis persis:

```php
Schema::create('mapel', function (Blueprint $table) {
    $table->id();
    $table->string('nama_mapel');
    $table->string('kode_mapel');
    $table->timestamps();
});
```

- [x] Simpan file

### Migration `mengajar`

- [x] Jalankan:

```bash
php artisan make:migration create_mengajar_table
```

- [x] Buka file migration yang baru dibuat
- [x] Di dalam method `up()`, tulis persis:

```php
Schema::create('mengajar', function (Blueprint $table) {
    $table->id();
    $table->foreignId('guru_id')->constrained('guru');
    $table->foreignId('mapel_id')->constrained('mapel');
    $table->foreignId('kelas_id')->constrained('kelas');
    $table->string('tahun_ajaran');
    $table->string('semester');
    $table->timestamps();
});
```

- [x] Simpan file

### Jalankan Semua Migration

- [x] Jalankan:

```bash
php artisan migrate
```

- [x] Kalau ada error, baca pesan errornya — biasanya soal urutan tabel (tabel yang di-reference harus sudah ada duluan). Cek nama file migration, pastikan urutannya: `users` → `guru` → `siswa` → `kelas` → (foreign key siswa-kelas) → `mapel` → `mengajar`
- [x] Setelah tidak ada error, buka database, cek semua 6 tabel sudah muncul: `users`, `guru`, `siswa`, `kelas`, `mapel`, `mengajar`

### Middleware Role

- [x] Jalankan:

```bash
php artisan make:middleware EnsureUserHasRole
```

- [x] Buka file `app/Http/Middleware/EnsureUserHasRole.php`
- [x] Ganti isi method `handle()` jadi:

```php
public function handle(Request $request, Closure $next, string $role)
{
    if (auth()->user()->role !== $role) {
        abort(403, 'Tidak punya akses ke halaman ini.');
    }
    return $next($request);
}
```

- [x] Simpan file
- [x] Buka file `bootstrap/app.php` (Laravel 11) atau `app/Http/Kernel.php` (Laravel 10 ke bawah)
- [x] Daftarkan middleware dengan alias `role` — kalau bingung caranya di versi Laravel yang dipakai, cari "register middleware alias Laravel [versi]" atau tanya AI dengan sebut versi Laravel yang dipakai

### Route Group per Role

- [x] Buka file `routes/web.php`
- [x] Buka `laravel-docs/04-routes-and-structure.md`, salin struktur middleware group dari sana
- [x] Tempel struktur route group (admin/guru/siswa) ke `web.php`
- [x] Simpan file
- [x] Belum perlu isi route di dalamnya, cukup struktur group-nya dulu — isinya nanti H3

---

## H3 (Rabu) — Model, CRUD Admin, Seeder

### Model + Relasi Eloquent

- [x] Jalankan:

```bash
php artisan make:model User -f
```

(kalau model `User` sudah ada dari Breeze, skip — cukup edit yang sudah ada)

- [x] Jalankan:

```bash
php artisan make:model Guru
php artisan make:model Siswa
php artisan make:model Kelas
php artisan make:model Mapel
php artisan make:model Mengajar
```

- [x] Buka `app/Models/Guru.php`, tambahkan method relasi:

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

- [x] Buka `app/Models/Siswa.php`, tambahkan:

```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function kelas()
{
    return $this->belongsTo(Kelas::class);
}
```

- [x] Buka `app/Models/Kelas.php`, tambahkan:

```php
public function siswa()
{
    return $this->hasMany(Siswa::class);
}

public function waliKelas()
{
    return $this->belongsTo(Guru::class, 'wali_kelas_id');
}
```

- [x] Buka `app/Models/Mengajar.php`, tambahkan:

```php
public function guru()
{
    return $this->belongsTo(Guru::class);
}

public function mapel()
{
    return $this->belongsTo(Mapel::class);
}

public function kelas()
{
    return $this->belongsTo(Kelas::class);
}
```

- [x] Simpan semua file yang diedit

### CRUD Admin — Pakai AI (4 Modul, 1 Prompt Template)

CRUD User, Kelas, Mapel, Mengajar semua punya pola yang sama persis (list, tambah, edit, hapus) — jadi dikerjakan pakai 1 prompt template yang diulang 4x, tinggal ganti bagian nama tabel/kolom.

- [ ] Lampirkan ke AI file: `laravel-docs/00-project-overview.md`, `laravel-docs/02-tech-conventions.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`

#### CRUD Manajemen User

- [ ] Jalankan:

```bash
php artisan make:controller Admin/UserController --resource
```

- [ ] Buka `laravel-docs/04-routes-and-structure.md`, cari baris route `/admin/users`
- [ ] Buka `routes/web.php`, di dalam group `admin`, tambahkan:

```php
Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
```

- [ ] Buat folder `resources/views/admin/users/`
- [ ] MANUAL AI — tempel prompt berikut ke AI sendiri nanti:

```
Saya butuh CRUD lengkap untuk Admin\UserController (Laravel resource
controller) dan 3 view Blade-nya: resources/views/admin/users/index.blade.php,
create.blade.php, edit.blade.php.

Model: User, kolom yang relevan: nama, email, password, role
(enum: admin/guru/siswa).

Controller (method index, create, store, edit, update, destroy):
- index() — ambil semua user, kirim ke view
- create() — kosong, cuma return view form tambah
- store(Request $request) — validasi nama required, email required|email|unique,
  password required|min:8, role required|in:admin,guru,siswa. Simpan pakai
  Eloquent User::create(), password di-hash pakai Hash::make(). Redirect ke
  index dengan pesan sukses.
- edit(User $user) — return view form edit dengan data user
- update(Request $request, User $user) — validasi mirip store tapi email
  unique kecuali punya user ini sendiri, password opsional (kalau kosong,
  jangan update password). Redirect ke index dengan pesan sukses.
- destroy(User $user) — hapus user, redirect ke index dengan pesan sukses

Views (ikuti 05-component-library.md, jangan bikin HTML baru dari nol):
- index.blade.php — extend layouts.app, tampilkan <x-table> daftar user
  (kolom: Nama, Email, Role, Aksi dengan tombol edit/hapus pakai <x-button>)
- create.blade.php — extend layouts.app, form pakai <x-form-input> untuk
  nama/email/password, <x-form-select> untuk role, <x-button> submit
- edit.blade.php — sama seperti create tapi field ke-fill data existing,
  method PUT

Ikuti token warna dari 01-design-system.md untuk semua view.
```

- [ ] Tempel hasil controller ke `app/Http/Controllers/Admin/UserController.php`
- [ ] Tempel hasil view ke 3 file di `resources/views/admin/users/`
- [ ] **Cek manual:** password di-hash (`Hash::make()`), validasi email `unique` ada, view pakai komponen dari component library
- [ ] Test: buka `/admin/users` di browser (login dulu sebagai admin), coba tambah 1 user percobaan, edit, lalu hapus

#### CRUD Manajemen Kelas

- [ ] Jalankan:

```bash
php artisan make:controller Admin/KelasController --resource
```

- [ ] Tambahkan route resource `kelas` di `routes/web.php` dalam group admin
- [ ] Buat folder `resources/views/admin/kelas/`
- [ ] MANUAL AI — tempel prompt berikut ke AI sendiri nanti (file yang sama masih terlampir):

```
Sekarang buatkan CRUD yang sama polanya untuk Admin\KelasController dan
3 view di resources/views/admin/kelas/.

Model: Kelas, kolom: nama_kelas, tingkat, wali_kelas_id (dropdown pilih
dari tabel guru), tahun_ajaran.

Ikuti pola controller dan view yang sama seperti UserController
sebelumnya (index/create/store/edit/update/destroy, pakai komponen dari
05-component-library.md, ikuti token warna 01-design-system.md). Untuk
wali_kelas_id, pakai <x-form-select> dengan opsi dari daftar guru
(tampilkan nama guru, bukan id).
```

- [ ] Tempel hasil controller ke `app/Http/Controllers/Admin/KelasController.php`
- [ ] Tempel hasil view ke 3 file di `resources/views/admin/kelas/`
- [ ] Test: buka `/admin/kelas`, pastikan bisa tambah 1 data kelas percobaan

#### CRUD Manajemen Mapel

- [ ] Jalankan:

```bash
php artisan make:controller Admin/MapelController --resource
```

- [ ] Tambahkan route resource `mapel` di `routes/web.php`
- [ ] Buat folder `resources/views/admin/mapel/`
- [ ] MANUAL AI — tempel prompt berikut ke AI sendiri nanti:

```
Sekarang buatkan CRUD yang sama polanya untuk Admin\MapelController dan
3 view di resources/views/admin/mapel/.

Model: Mapel, kolom: nama_mapel, kode_mapel.

Ikuti pola yang sama seperti UserController dan KelasController
sebelumnya.
```

- [ ] Tempel hasil controller ke `app/Http/Controllers/Admin/MapelController.php`
- [ ] Tempel hasil view ke 3 file di `resources/views/admin/mapel/`
- [ ] Test: buka `/admin/mapel`, pastikan bisa tambah 1 data mapel percobaan

#### CRUD Manajemen Mengajar

- [ ] Jalankan:

```bash
php artisan make:controller Admin/MengajarController --resource
```

- [ ] Tambahkan route resource `mengajar` di `routes/web.php`
- [ ] Buat folder `resources/views/admin/mengajar/`
- [ ] MANUAL AI — tempel prompt berikut ke AI sendiri nanti:

```
Sekarang buatkan CRUD yang sama polanya untuk Admin\MengajarController dan
3 view di resources/views/admin/mengajar/.

Model: Mengajar, kolom: guru_id (dropdown dari tabel guru, tampilkan nama),
mapel_id (dropdown dari tabel mapel, tampilkan nama_mapel), kelas_id
(dropdown dari tabel kelas, tampilkan nama_kelas), tahun_ajaran, semester.

Ikuti pola yang sama seperti controller sebelumnya. Untuk index.blade.php,
tampilkan kolom gabungan yang jelas: Nama Guru, Mapel, Kelas, Tahun Ajaran,
Semester — bukan cuma id-nya.
```

- [ ] Tempel hasil controller ke `app/Http/Controllers/Admin/MengajarController.php`
- [ ] Tempel hasil view ke 3 file di `resources/views/admin/mengajar/`
- [ ] Test: buka `/admin/mengajar`, pastikan bisa tambah 1 data mengajar percobaan (pilih guru+mapel+kelas yang sudah dibuat sebelumnya)

### Seeder Data Testing

- [ ] Jalankan:

```bash
php artisan make:seeder AkademikSeeder
```

- [ ] Buka `database/seeders/AkademikSeeder.php`
- [ ] Isi method `run()` untuk bikin data berikut (boleh tulis manual satu-satu pakai `Model::create([...])`):
  - [ ] 1 user + guru admin (role `admin`)
  - [ ] 2 user + guru dengan role `guru`
  - [ ] 1 kelas
  - [ ] 5-10 user + siswa dengan role `siswa`, semua masuk ke kelas yang barusan dibuat
  - [ ] 1-2 mapel
  - [ ] Minimal 1 baris `mengajar` yang menghubungkan salah satu guru + salah satu mapel + kelas yang dibuat
- [ ] Simpan file
- [ ] Jalankan:

```bash
php artisan db:seed --class=AkademikSeeder
```

- [ ] Cek tidak ada error
- [ ] Buka database, pastikan semua data testing di atas benar-benar masuk

### Broadcast ke Tim

- [ ] Kirim pesan ke grup tim: migration + seeder sudah selesai, semua bisa `git pull` lalu jalankan:

```bash
php artisan migrate:fresh --seed --seeder=AkademikSeeder
```

- [ ] Share juga email/password akun testing (1 admin, 2 guru, beberapa siswa) yang dibuat di seeder

---

## H4 (Kamis) — Buffer & Bantu Modul Lain

- [ ] Tanya Nabil: sudah bisa akses tabel `mengajar` dari controller nilai tanpa error?
- [ ] Tanya Hermanus: sudah bisa akses tabel `mengajar` dari controller absensi tanpa error?
- [ ] Kalau ada laporan bug di CRUD admin dari siapa pun, cek dan perbaiki
- [ ] Ajak Noctura cek middleware `role` bareng — coba login sebagai guru, coba akses `/admin/users` langsung dari URL, pastikan muncul error 403 (tertolak), bukan malah bisa masuk

---

## H5 (Jumat) — Review Silang

- [ ] Koordinasi ke Noctura: mau review modul Nabil atau Hermanus
- [ ] Buka kode modul yang direview, cek controller dan migration-nya
- [ ] Kalau ada yang janggal atau tidak sesuai `laravel-docs/02-tech-conventions.md`, catat dan diskusikan langsung ke yang punya modul
- [ ] Cek halaman dashboard (`/dashboard`) — pastikan setelah login, redirect ke tampilan yang sesuai role (admin lihat menu admin, guru lihat menu guru, siswa lihat menu siswa)

---

## H6 (Sabtu) — Integrasi

- [ ] Ikut sesi testing end-to-end bareng tim, ikuti skenario yang disiapkan Noctura
- [ ] Kalau ada bug yang berhubungan dengan auth/middleware/data master (misal: user tidak bisa login, data guru/kelas tidak muncul di dropdown modul lain), perbaiki
- [ ] Setelah fix, jalankan ulang test yang gagal tadi untuk pastikan sudah benar

---

## H7 (Minggu) — Testing Akhir

- [ ] Ikut sesi testing akhir bareng tim
- [ ] Pastikan akun demo (admin, guru, siswa) masih bisa login dengan lancar
- [ ] Kalau data testing dari seeder sudah berantakan karena banyak dicoba-coba selama seminggu, jalankan ulang:

```bash
php artisan migrate:fresh --seed --seeder=AkademikSeeder
```

- [ ] Konfirmasi ke tim data demo sudah bersih dan siap dipakai
