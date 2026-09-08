# Task Hermanus — Modul Absensi

**Modul:** Input absensi (guru) + Lihat rekap absensi (siswa), termasuk stored procedure, trigger, function, transaction
**Acuan:** `pembagian-tugas-laravel.md`, `laravel-docs/02-tech-conventions.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/05-component-library.md`

---

## H1 (Senin) — Sinkronisasi

- [ ] Ikut sesi sinkronisasi bareng tim
- [ ] Buka `laravel-docs/03-database-schema.md`, baca bagian tabel `absensi` dan `rekap_absensi`
- [ ] Baca daftar objek database: `sp_rekap_absensi`, `fn_persentase_hadir`, `trg_absensi_insert`
- [ ] Buka `laravel-docs/02-tech-conventions.md`, baca bagian "Transaction Handling (Wajib untuk Nilai & Absensi)" sampai paham pola `DB::beginTransaction()` / `commit()` / `rollBack()`
- [ ] Clone repo project dari Iki setelah dia broadcast link (kalau belum, tunggu H1 selesai atau H2 pagi)

---

## H2 (Selasa) — Setup Paralel (Tanpa Nunggu Iki)

- [ ] Jalankan:
```bash
git pull
```
- [ ] Buat folder `resources/views/guru/absensi/`
- [ ] Buat folder `resources/views/siswa/absensi/`

### Halaman Form Input Absensi (Versi Mock) — Pakai AI

- [ ] Lampirkan ke AI file: `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya membuat halaman Blade untuk Laravel bernama
resources/views/guru/absensi/form.blade.php — ini form input absensi
oleh guru, tapi untuk sekarang saya butuh versi MOCK/DUMMY dulu
(belum konek ke database sungguhan).

Extend dari layouts.app (lihat 05-component-library.md bagian
"Layout Dasar"). Buat variabel dummy 2-3 siswa contoh langsung di
dalam file pakai @php...@endphp, format:
(object)['id' => 1, 'nama' => 'Siswa Contoh 1', 'nis' => '001']

Tampilkan pakai komponen <x-table> (lihat contoh pemakaiannya di
05-component-library.md bagian "7. Tabel Data"). Kolom: Nama Siswa,
NIS, Status Kehadiran — untuk kolom status, ganti input number jadi
dropdown <select> dengan name="status[{id siswa}]" dan opsi: Hadir,
Izin, Sakit, Alpa (value: hadir/izin/sakit/alpa).

Ikuti token warna dari 01-design-system.md, jangan pakai style di
luar itu.
```

- [ ] Tempel hasil dari AI ke file `resources/views/guru/absensi/form.blade.php`
- [ ] **Cek manual:** pakai `<x-table>`, dropdown status ada 4 opsi (hadir/izin/sakit/alpa), tidak ada warna di luar token
- [ ] Buat route sementara untuk cek tampilan (nanti dirapikan H4):
```php
Route::get('/test-absensi-form', function () {
    return view('guru.absensi.form');
});
```
- [ ] Jalankan:
```bash
php artisan serve
```
- [ ] Buka browser ke `/test-absensi-form`, pastikan tabel dummy muncul dengan siswa dan dropdown status

### Halaman Lihat Rekap Absensi Siswa (Versi Mock) — Pakai AI

- [ ] Tempel prompt berikut ke AI (file yang sama masih terlampir):

```
Sekarang buatkan juga resources/views/siswa/absensi/index.blade.php —
halaman siswa melihat rekap absensi sendiri, versi MOCK/DUMMY dulu.

Extend dari layouts.app. Buat variabel dummy: total per status
(hadir, izin, sakit, alpa berupa angka) dan persentase kehadiran,
pakai @php...@endphp.

Tampilkan total per status pakai komponen <x-badge> (lihat
05-component-library.md bagian "8. Badge") — variant success untuk
hadir, warning untuk izin/sakit, error untuk alpa. Tampilkan juga
angka persentase kehadiran secara mencolok di bagian atas.

Ikuti token warna dari 01-design-system.md.
```

- [ ] Tempel hasil dari AI ke file `resources/views/siswa/absensi/index.blade.php`
- [ ] **Cek manual:** pakai `<x-badge>` dengan variant yang sesuai, tidak ada warna di luar token
- [ ] Jalankan `php artisan view:clear`, pastikan tidak ada error syntax

---

## H3 (Rabu) — Migration & SQL Objects

### Migration `absensi`

- [ ] Jalankan:
```bash
git pull
```
(pastikan sudah dapat migration dari Iki: `users`, `guru`, `siswa`, `kelas`, `mapel`, `mengajar`)
- [ ] Jalankan:
```bash
php artisan make:migration create_absensi_table
```
- [ ] Buka file migration yang baru dibuat
- [ ] Di dalam method `up()`, tulis persis:
```php
Schema::create('absensi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswa');
    $table->foreignId('mengajar_id')->constrained('mengajar');
    $table->date('tanggal');
    $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
    $table->timestamps();

    $table->unique(['siswa_id', 'mengajar_id', 'tanggal']);
});
```
- [ ] Simpan file
- [ ] **Catatan:** baris `$table->unique(...)` di atas WAJIB ada — ini yang mencegah 1 siswa diabsen 2x di tanggal sama

### Migration `rekap_absensi`

- [ ] Jalankan:
```bash
php artisan make:migration create_rekap_absensi_table
```
- [ ] Buka file migration yang baru dibuat
- [ ] Di dalam method `up()`, tulis persis:
```php
Schema::create('rekap_absensi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswa');
    $table->foreignId('mengajar_id')->constrained('mengajar');
    $table->string('semester');
    $table->integer('total_hadir')->default(0);
    $table->integer('total_izin')->default(0);
    $table->integer('total_sakit')->default(0);
    $table->integer('total_alpa')->default(0);
    $table->decimal('persentase_hadir', 5, 2)->nullable();
    $table->timestamp('updated_at')->nullable();

    $table->unique(['siswa_id', 'mengajar_id', 'semester']);
});
```
- [ ] Simpan file
- [ ] Jalankan:
```bash
php artisan migrate
```
- [ ] Cek tidak ada error, buka database, pastikan tabel `absensi` dan `rekap_absensi` sudah muncul dengan UNIQUE KEY-nya

### Buat File SQL untuk Procedure, Function, Trigger

- [ ] Buat folder `sql/` di root project (kalau belum ada — kemungkinan Nabil sudah bikin duluan, kalau sudah ada langsung pakai yang sama)
- [ ] Pastikan ada file `sql/procedures.sql`, `sql/functions.sql`, `sql/triggers.sql`

### Isi `sql/functions.sql`

- [ ] Buka file rancangan database lengkap (`rancangan-sistem-akademik-sekolah_1_.md`) bagian "3. Function", salin kode `fn_persentase_hadir`
- [ ] Tambahkan ke `sql/functions.sql` (di bawah `fn_rata_rata_nilai` kalau Nabil sudah isi duluan)
- [ ] Simpan file

### Isi `sql/procedures.sql`

- [ ] Dari file rancangan database bagian "2. Stored Procedure", salin kode `sp_rekap_absensi`
- [ ] Tambahkan ke `sql/procedures.sql`
- [ ] Simpan file

### Isi `sql/triggers.sql`

- [ ] Dari file rancangan database bagian "4. Trigger", salin kode `trg_absensi_insert`
- [ ] Tambahkan ke `sql/triggers.sql`
- [ ] **Cek eksplisit:** dalam kode trigger, pastikan bagian yang menentukan semester mengambil dari `mengajar.semester` (join ke tabel `mengajar`), BUKAN nilai semester yang ditulis tetap/hardcode seperti `'ganjil'`
- [ ] Simpan file

### Jalankan SQL Manual di Database

- [ ] Buka tool database, pilih tab untuk jalankan raw SQL/query
- [ ] Copy isi `sql/functions.sql` (semua isinya, termasuk punya Nabil kalau sudah ada), jalankan
- [ ] Cek `fn_persentase_hadir` muncul di "Functions"/"Routines"
- [ ] Copy isi `sql/procedures.sql`, jalankan
- [ ] Cek `sp_rekap_absensi` muncul di "Procedures"/"Routines"
- [ ] Copy isi `sql/triggers.sql`, jalankan
- [ ] Cek `trg_absensi_insert` muncul di tab "Triggers"

### Test Manual di Database Langsung (Bukan Lewat Laravel Dulu)

- [ ] Jalankan query manual insert 1 baris absensi langsung (bukan lewat procedure, karena absensi masuk lewat INSERT biasa yang memicu trigger):
```sql
INSERT INTO absensi (siswa_id, mengajar_id, tanggal, status, created_at, updated_at)
VALUES (1, 1, '2026-08-17', 'hadir', NOW(), NOW());
```
(sesuaikan `siswa_id` dan `mengajar_id` dengan data yang benar-benar ada dari seeder Iki)
- [ ] Cek tabel `rekap_absensi`, pastikan 1 baris baru muncul otomatis dengan `total_hadir = 1` — ini tandanya trigger `trg_absensi_insert` berhasil memanggil `sp_rekap_absensi` otomatis
- [ ] Coba insert lagi baris absensi untuk siswa dan tanggal yang **sama persis**, pastikan MySQL menolak dengan error duplicate key — ini tandanya UNIQUE KEY bekerja
- [ ] Insert 1 baris absensi lagi untuk siswa sama, tanggal beda, status `izin`
- [ ] Cek `rekap_absensi`, pastikan `total_hadir` tetap 1 dan `total_izin` jadi 1 (baris rekap ke-update, bukan nambah baris baru)

---

## H4 (Kamis) — Controller & Transaction

### Controller (Pakai AI)

- [ ] Jalankan:
```bash
php artisan make:controller Guru/AbsensiController
php artisan make:controller Siswa/AbsensiController
```
- [ ] Buka `laravel-docs/04-routes-and-structure.md`, salin baris route modul Absensi
- [ ] Buka `routes/web.php`, hapus route testing sementara `/test-absensi-form` dari H2
- [ ] Tambahkan route asli sesuai yang disalin, di dalam group `guru` dan `siswa`
- [ ] Lampirkan ke AI file: `laravel-docs/00-project-overview.md`, `laravel-docs/02-tech-conventions.md`, `laravel-docs/03-database-schema.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya sedang membuat controller Guru\AbsensiController untuk Laravel + Blade.
Ikuti konvensi di 02-tech-conventions.md dan skema di 03-database-schema.md
yang saya lampirkan.

Buatkan 3 method:

1. index() — ambil semua baris tabel `mengajar` milik guru yang sedang login
   (relasi guru->user_id = auth()->id()), kirim ke view
   guru.absensi.index untuk jadi pilihan dropdown kelas + input tanggal.

2. form($mengajarId) — terima juga parameter tanggal lewat query string
   (request()->query('tanggal')), ambil satu baris mengajar berdasarkan
   $mengajarId, ambil kelas_id dari situ, lalu ambil semua siswa yang
   kelas_id-nya sama. Kirim $mengajar, $siswaList, dan $tanggal ke view
   guru.absensi.form.

3. store(Request $request, $mengajarId) — ini yang paling penting, harus:
   - Validasi: field 'tanggal' wajib dan berupa tanggal valid
   - Validasi: field 'status' adalah array, tiap isinya salah satu dari
     hadir/izin/sakit/alpa
   - WAJIB pakai DB::beginTransaction() / commit() / rollBack() dengan
     try-catch (lihat pola contoh di 02-tech-conventions.md bagian
     "Transaction Handling")
   - Di dalam transaction, loop tiap siswa yang dikirim, insert ke tabel
     absensi PAKAI Eloquent Absensi::create() biasa (BUKAN CALL procedure
     — absensi masuk lewat INSERT biasa yang otomatis memicu trigger
     trg_absensi_insert di database)
   - Kalau semua berhasil, redirect back dengan pesan sukses
   - Kalau ada exception (misal karena UNIQUE KEY constraint terlanggar
     kalau ada duplikat), rollback dan redirect back dengan pesan error
     yang jelas dan mudah dipahami, bukan pesan error mentah dari database

Tolong beri kode lengkap ketiga method itu untuk ditempel ke
app/Http/Controllers/Guru/AbsensiController.php.
```

- [ ] Tempel hasil dari AI ke `app/Http/Controllers/Guru/AbsensiController.php`
- [ ] **Baca ulang kode yang dihasilkan**, cocokkan ke 3 poin ini sebelum lanjut:
  - [ ] Ada `DB::beginTransaction()`, `DB::commit()`, `DB::rollBack()` dengan try-catch
  - [ ] Insert absensi pakai `Absensi::create()`, BUKAN manggil `CALL sp_rekap_absensi` manual (itu tugas trigger, bukan controller)
  - [ ] Pesan error untuk kasus duplikat (UNIQUE KEY) mudah dipahami, bukan pesan mentah SQL
- [ ] Kalau ada dari 3 poin di atas yang tidak sesuai atau hilang, minta AI perbaiki secara spesifik

### Model

- [ ] Jalankan:
```bash
php artisan make:model Absensi
php artisan make:model RekapAbsensi
```
- [ ] Buka `app/Models/Absensi.php`, tambahkan:
```php
protected $fillable = ['siswa_id', 'mengajar_id', 'tanggal', 'status'];

public function siswa()
{
    return $this->belongsTo(Siswa::class);
}

public function mengajar()
{
    return $this->belongsTo(Mengajar::class);
}
```
- [ ] Simpan file

### View Form Absensi (Pakai AI)

- [ ] Lampirkan ke AI file: `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya sudah punya file resources/views/guru/absensi/form.blade.php yang
berisi data dummy (lihat isi file saat ini di bawah). Tolong ganti jadi
versi yang menerima data asli dari controller: $mengajar (objek berisi
info kelas, mapel, guru), $siswaList (koleksi siswa di kelas itu), dan
$tanggal (tanggal absensi yang dipilih).

Gunakan komponen yang SUDAH ADA di 05-component-library.md — jangan bikin
struktur HTML baru dari nol. Pakai <x-table> untuk daftar siswa.

Form harus:
- method="POST" ke route guru.absensi.store dengan parameter $mengajar->id
- @csrf
- Input tanggal (type date), name="tanggal", default value dari $tanggal
- Dropdown status per siswa pakai name="status[{{ $siswa->id }}]" dengan
  opsi hadir/izin/sakit/alpa
- Tombol submit pakai <x-button variant="primary" type="submit">

Ikuti token warna dan gaya dari 01-design-system.md.

Isi file form.blade.php saat ini:
[TEMPEL ISI FILE resources/views/guru/absensi/form.blade.php DARI H2 DI SINI]
```

- [ ] Tempel hasil dari AI, timpa isi `resources/views/guru/absensi/form.blade.php`
- [ ] **Cek manual sebelum lanjut:**
  - [ ] Ada input tanggal dan `@csrf`
  - [ ] Komponen yang dipakai benar `<x-table>`, `<x-button>`
  - [ ] Warna/style ikut token `01-design-system.md`
- [ ] Test: buka `/guru/absensi` di browser (login sebagai guru dari seeder Iki), pilih kelas dan tanggal, pastikan form muncul dengan siswa asli
- [ ] Test: submit form isi status untuk 2-3 siswa, cek muncul pesan "berhasil"
- [ ] Cek di database, tabel `absensi` dan `rekap_absensi` ke-update

---

## H5 (Jumat) — Tampilan Siswa & Testing Sendiri

### Controller Siswa\AbsensiController (Pakai AI)

- [ ] Lampirkan ke AI file: `laravel-docs/00-project-overview.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya sedang membuat controller Siswa\AbsensiController untuk Laravel + Blade,
dan sekaligus view resources/views/siswa/absensi/index.blade.php.

Controller, method index():
- Ambil siswa yang sedang login (lewat relasi siswa->user_id = auth()->id())
- Ambil data dari tabel `rekap_absensi` milik siswa itu (total per status +
  persentase kehadiran), dikelompokkan per mengajar (per mapel)
- Kalau `rekap_absensi` kosong untuk suatu mapel, pakai fallback raw query:
  DB::select('SELECT fn_persentase_hadir(?, ?) as persentase', [$siswaId, $mengajarId])
- Kirim semua data ke view siswa.absensi.index

View siswa/absensi/index.blade.php:
- Sudah ada versi dummy dari sebelumnya (saya lampirkan isinya di bawah),
  tolong ganti supaya menerima data asli dari controller
- Extend layouts.app
- Dikelompokkan per mapel: nama mapel sebagai judul kecil, lalu total per
  status pakai <x-badge> (variant success untuk hadir, warning untuk
  izin/sakit, error untuk alpa), lalu persentase kehadiran ditampilkan
  mencolok
- Ikuti token warna dari 01-design-system.md

Isi file index.blade.php saat ini (versi dummy):
[TEMPEL ISI FILE resources/views/siswa/absensi/index.blade.php DARI H2 DI SINI]
```

- [ ] Tempel hasil controller ke `app/Http/Controllers/Siswa/AbsensiController.php`
- [ ] Tempel hasil view, timpa isi `resources/views/siswa/absensi/index.blade.php`
- [ ] **Cek manual:**
  - [ ] Ada fallback ke `fn_persentase_hadir` untuk kasus `rekap_absensi` kosong
  - [ ] View pakai `<x-badge>` dengan variant yang sesuai per status
  - [ ] Warna/style ikut token `01-design-system.md`
- [ ] Test: login sebagai siswa dari seeder Iki, buka `/siswa/absensi`, pastikan rekap yang diinput guru di H4 muncul di sini

### Self-Test Wajib

- [ ] **Self-test 1:** submit absensi untuk 1 kelas lewat form guru, lalu login sebagai salah satu siswa di kelas itu, cek rekap muncul benar di halaman siswa
- [ ] **Self-test 2:** submit absensi lagi untuk kelas yang sama, tanggal beda, status campur (ada hadir, ada izin), cek total di rekap siswa ikut bertambah sesuai
- [ ] **Self-test 3:** coba submit absensi 2x untuk siswa dan tanggal yang sama (lewat form, bukan langsung SQL), pastikan sistem menangani ini dengan baik (baik ditolak dengan pesan jelas, atau ter-update — bukan malah error mentah/crash)
- [ ] **Self-test 4:** coba akses form input absensi untuk `mengajar_id` yang bukan milik guru yang sedang login, pastikan sistem menolak atau tidak menampilkan data

### Peer Review

- [ ] Minta Nabil buka kode modul absensi (controller, migration, file SQL), minta dia cek apakah pola transaction sudah sesuai `laravel-docs/02-tech-conventions.md`
- [ ] Kalau Nabil menemukan hal yang janggal, perbaiki hari ini juga

---

## H6 (Sabtu) — Integrasi

- [ ] Ikut sesi testing end-to-end bareng tim sesuai skenario dari Noctura
- [ ] Siapkan alur testing modul absensi untuk didemoin: login guru → pilih kelas & tanggal → input absensi sekelas → cek tersimpan → login siswa → cek rekap muncul
- [ ] Kalau ada bug yang ditemukan saat sesi bareng, perbaiki saat itu juga kalau memungkinkan, atau catat untuk diperbaiki setelah sesi

---

## H7 (Minggu) — Testing Akhir

- [ ] Ikut sesi testing akhir bareng tim
- [ ] Siapkan 1 skenario demo yang enak ditunjukkan: 1 kelas dengan beberapa siswa, riwayat absensi beberapa hari yang sudah terisi rapi (bukan data acak testing)
- [ ] Pastikan tidak ada sisa error/bug kecil yang mengganggu tampilan
