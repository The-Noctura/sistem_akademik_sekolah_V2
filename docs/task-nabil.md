# Task Nabil — Modul Nilai

**Modul:** Input nilai (guru) + Lihat nilai (siswa), termasuk stored procedure, trigger, function, transaction
**Acuan:** `pembagian-tugas-laravel.md`, `laravel-docs/02-tech-conventions.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/05-component-library.md`

---

## H1 (Senin) — Sinkronisasi

- [ ] Ikut sesi sinkronisasi bareng tim
- [ ] Buka `laravel-docs/03-database-schema.md`, baca bagian tabel `nilai` dan `rekap_nilai`
- [ ] Baca daftar objek database: `sp_input_nilai_kelas`, `fn_rata_rata_nilai`, `trg_rekap_nilai_insert`, `trg_rekap_nilai_update`, `trg_log_nilai_update`
- [ ] Buka `laravel-docs/02-tech-conventions.md`, baca bagian "Transaction Handling (Wajib untuk Nilai & Absensi)" sampai paham pola `DB::beginTransaction()` / `commit()` / `rollBack()`
- [ ] Clone repo project dari Iki setelah dia broadcast link (kalau belum, tunggu H1 selesai atau H2 pagi)

---

## H2 (Selasa) — Setup Paralel (Tanpa Nunggu Iki)

- [ ] Jalankan:
```bash
git pull
```
- [ ] Buat folder `resources/views/guru/nilai/`
- [ ] Buat folder `resources/views/siswa/nilai/`

### Halaman Form Input Nilai (Versi Mock) — Pakai AI

- [ ] Lampirkan ke AI file: `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya membuat halaman Blade untuk Laravel bernama
resources/views/guru/nilai/form.blade.php — ini form input nilai
oleh guru, tapi untuk sekarang saya butuh versi MOCK/DUMMY dulu
(belum konek ke database sungguhan) supaya saya bisa lihat tampilannya
sambil menunggu migration selesai dikerjakan orang lain di tim.

Extend dari layouts.app (lihat 05-component-library.md bagian
"Layout Dasar"). Buat variabel dummy 2-3 siswa contoh langsung di
dalam file pakai @php...@endphp, format:
(object)['id' => 1, 'nama' => 'Siswa Contoh 1', 'nis' => '001']

Tampilkan pakai komponen <x-table> (lihat contoh pemakaiannya di
05-component-library.md bagian "7. Tabel Data" — pemakaian untuk
tabel input nilai). Kolom: Nama Siswa, NIS, Nilai (input number).

Ikuti token warna dari 01-design-system.md, jangan pakai style di
luar itu.
```

- [ ] Tempel hasil dari AI ke file `resources/views/guru/nilai/form.blade.php`
- [ ] **Cek manual:** pakai `<x-table>` (bukan tabel HTML mentah), tidak ada warna di luar token `01-design-system.md`
- [ ] Buat route sementara untuk cek tampilan (nanti dirapikan H4), buka `routes/web.php`, tambahkan di luar group dulu untuk testing cepat:
```php
Route::get('/test-nilai-form', function () {
    return view('guru.nilai.form');
});
```
- [ ] Jalankan:
```bash
php artisan serve
```
- [ ] Buka browser ke `/test-nilai-form`, pastikan tabel dummy muncul dengan siswa dan input nilai

### Halaman Lihat Nilai Siswa (Versi Mock) — Pakai AI

- [ ] Tempel prompt berikut ke AI (file yang sama masih terlampir):

```
Sekarang buatkan juga resources/views/siswa/nilai/index.blade.php —
halaman siswa melihat nilai sendiri, versi MOCK/DUMMY dulu.

Extend dari layouts.app. Buat variabel dummy 1-2 baris nilai
(jenis: tugas/uts/uas, nilai: angka) pakai @php...@endphp.

Tampilkan pakai <x-table> juga, kolom: Jenis, Nilai.

Ikuti token warna dari 01-design-system.md.
```

- [ ] Tempel hasil dari AI ke file `resources/views/siswa/nilai/index.blade.php`
- [ ] **Cek manual:** pakai `<x-table>`, tidak ada warna di luar token
- [ ] Jalankan `php artisan view:clear`, pastikan tidak ada error syntax

---

## H3 (Rabu) — Migration & SQL Objects

### Migration `nilai`

- [ ] Jalankan:
```bash
git pull
```
(pastikan sudah dapat migration dari Iki: `users`, `guru`, `siswa`, `kelas`, `mapel`, `mengajar`)
- [ ] Jalankan:
```bash
php artisan make:migration create_nilai_table
```
- [ ] Buka file migration yang baru dibuat
- [ ] Di dalam method `up()`, tulis persis:
```php
Schema::create('nilai', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswa');
    $table->foreignId('mengajar_id')->constrained('mengajar');
    $table->enum('jenis', ['tugas', 'uts', 'uas']);
    $table->decimal('nilai', 5, 2);
    $table->timestamp('tanggal_input');
    $table->foreignId('diinput_oleh')->constrained('users');
    $table->timestamps();

    $table->unique(['siswa_id', 'mengajar_id', 'jenis']);
});
```
- [ ] Simpan file
- [ ] **Catatan:** baris `$table->unique(...)` di atas WAJIB ada, karena dipakai `ON DUPLICATE KEY UPDATE` di procedure nanti

### Migration `rekap_nilai`

- [ ] Jalankan:
```bash
php artisan make:migration create_rekap_nilai_table
```
- [ ] Buka file migration yang baru dibuat
- [ ] Di dalam method `up()`, tulis persis:
```php
Schema::create('rekap_nilai', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswa');
    $table->foreignId('mengajar_id')->constrained('mengajar');
    $table->string('semester');
    $table->decimal('rata_rata', 5, 2)->nullable();
    $table->decimal('nilai_akhir', 5, 2)->nullable();
    $table->string('predikat')->nullable();
    $table->timestamp('updated_at')->nullable();

    $table->unique(['siswa_id', 'mengajar_id', 'semester']);
});
```
- [ ] Simpan file
- [ ] Jalankan:
```bash
php artisan migrate
```
- [ ] Cek tidak ada error, buka database, pastikan tabel `nilai` dan `rekap_nilai` sudah muncul dengan UNIQUE KEY-nya (cek di tab "Indexes" kalau pakai phpMyAdmin/TablePlus)

### Buat File SQL untuk Procedure, Function, Trigger

- [ ] Buat folder `sql/` di root project (kalau belum ada)
- [ ] Buat file `sql/procedures.sql`
- [ ] Buat file `sql/functions.sql`
- [ ] Buat file `sql/triggers.sql`

### Isi `sql/functions.sql`

- [ ] Buka `laravel-docs/03-database-schema.md`, cari nama `fn_rata_rata_nilai`
- [ ] Buka file rancangan database lengkap (`rancangan-sistem-akademik-sekolah_1_.md`) bagian "3. Function", salin kode `fn_rata_rata_nilai` (bukan `fn_persentase_hadir`, itu punya Hermanus)
- [ ] Tempel ke `sql/functions.sql`
- [ ] Simpan file

### Isi `sql/procedures.sql`

- [ ] Dari file rancangan database, salin kode `sp_input_nilai_kelas` (bagian "2. Stored Procedure")
- [ ] Tempel ke `sql/procedures.sql`
- [ ] Simpan file

### Isi `sql/triggers.sql`

- [ ] Dari file rancangan database bagian "4. Trigger", salin kode `trg_rekap_nilai_insert`
- [ ] Salin juga `trg_rekap_nilai_update`
- [ ] Salin juga `trg_log_nilai_update`
- [ ] Tempel ketiganya ke `sql/triggers.sql`
- [ ] Simpan file

### Jalankan SQL Manual di Database

- [ ] Buka tool database (phpMyAdmin/TablePlus/DBeaver), pilih tab untuk jalankan raw SQL/query
- [ ] Copy isi `sql/functions.sql`, jalankan
- [ ] Cek tidak ada error, cek di bagian "Functions" atau "Routines" database, `fn_rata_rata_nilai` sudah muncul
- [ ] Copy isi `sql/procedures.sql`, jalankan
- [ ] Cek `sp_input_nilai_kelas` sudah muncul di "Procedures"/"Routines"
- [ ] Copy isi `sql/triggers.sql`, jalankan
- [ ] Cek ketiga trigger sudah muncul di tab "Triggers"

### Test Manual di Database Langsung (Bukan Lewat Laravel Dulu)

- [ ] Jalankan query manual untuk insert 1 baris nilai lewat procedure:
```sql
CALL sp_input_nilai_kelas(1, 'uts', 1, 85.5, 1);
```
(sesuaikan angka `1, 'uts', 1, 85.5, 1` dengan `mengajar_id`, jenis, `siswa_id`, nilai, `user_id` yang benar-benar ada di data seeder Iki)
- [ ] Cek tabel `nilai`, pastikan 1 baris baru muncul
- [ ] Cek tabel `rekap_nilai`, pastikan 1 baris baru muncul juga dengan `rata_rata` terisi otomatis (ini tandanya trigger `trg_rekap_nilai_insert` jalan)
- [ ] Jalankan lagi procedure yang sama tapi nilai beda, misal:
```sql
CALL sp_input_nilai_kelas(1, 'uts', 1, 90, 1);
```
- [ ] Cek tabel `nilai`, pastikan baris yang tadi ke-**update** (bukan nambah baris baru) — ini tandanya `ON DUPLICATE KEY UPDATE` jalan
- [ ] Cek tabel `rekap_nilai`, pastikan `rata_rata` ikut berubah — ini tandanya trigger `trg_rekap_nilai_update` jalan

---

## H4 (Kamis) — Controller & Transaction

### Controller (Pakai AI)

- [ ] Jalankan:
```bash
php artisan make:controller Guru/NilaiController
php artisan make:controller Siswa/NilaiController
```
- [ ] Buka `laravel-docs/04-routes-and-structure.md`, salin baris route modul Nilai
- [ ] Buka `routes/web.php`, hapus route testing sementara `/test-nilai-form` dari H2
- [ ] Tambahkan route asli sesuai yang disalin, di dalam group `guru` dan `siswa`
- [ ] Lampirkan ke AI file: `laravel-docs/00-project-overview.md`, `laravel-docs/02-tech-conventions.md`, `laravel-docs/03-database-schema.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya sedang membuat controller Guru\NilaiController untuk Laravel + Blade.
Ikuti konvensi di 02-tech-conventions.md dan skema di 03-database-schema.md
yang saya lampirkan.

Buatkan 3 method:

1. index() — ambil semua baris tabel `mengajar` milik guru yang sedang login
   (relasi guru->user_id = auth()->id()), kirim ke view
   guru.nilai.index untuk jadi pilihan dropdown kelas+mapel+jenis nilai.

2. form($mengajarId) — ambil satu baris `mengajar` berdasarkan $mengajarId,
   ambil kelas_id dari situ, lalu ambil semua siswa yang kelas_id-nya sama.
   Kirim $mengajar dan $siswaList ke view guru.nilai.form.

3. store(Request $request, $mengajarId) — ini yang paling penting, harus:
   - Validasi: field 'nilai' adalah array, tiap isinya numeric, min:0, max:100
   - Validasi: field 'jenis' adalah salah satu dari tugas/uts/uas
   - SEBELUM masuk transaction: ambil mengajar berdasarkan $mengajarId,
     cek tiap siswa_id yang dikirim dari form benar-benar terdaftar di
     kelas_id yang sama dengan mengajar tsb (query ke tabel siswa).
     Kalau ada yang tidak cocok, return balik dengan pesan error,
     JANGAN lanjut ke transaction.
   - WAJIB pakai DB::beginTransaction() / commit() / rollBack() dengan
     try-catch (lihat pola contoh di 02-tech-conventions.md bagian
     "Transaction Handling").
   - Di dalam transaction, loop tiap siswa yang dikirim, panggil raw SQL
     CALL sp_input_nilai_kelas(?, ?, ?, ?, ?) dengan urutan parameter:
     mengajar_id, jenis, siswa_id, nilai, auth()->id() — JANGAN pakai
     Eloquent create() untuk ini, karena harus lewat stored procedure.
   - Kalau semua berhasil, redirect back dengan pesan sukses.
   - Kalau ada exception, rollback dan redirect back dengan pesan error
     yang jelas (bukan pesan error mentah dari database).

Tolong beri kode lengkap ketiga method itu untuk ditempel ke
app/Http/Controllers/Guru/NilaiController.php.
```

- [ ] Tempel hasil dari AI ke `app/Http/Controllers/Guru/NilaiController.php`
- [ ] **Baca ulang kode yang dihasilkan**, cocokkan ke 4 poin ini sebelum lanjut:
  - [ ] Ada `DB::beginTransaction()`, `DB::commit()`, `DB::rollBack()` dengan try-catch — bukan cuma loop tanpa transaction
  - [ ] Ada pengecekan siswa-kelas SEBELUM transaction dimulai (bukan di dalam loop transaction)
  - [ ] Manggil `CALL sp_input_nilai_kelas(...)` lewat `DB::statement()`, bukan `Nilai::create()`
  - [ ] Validasi nilai 0-100 benar-benar ada
- [ ] Kalau ada dari 4 poin di atas yang tidak sesuai atau hilang, minta AI perbaiki secara spesifik (sebut poin mana yang kurang), jangan langsung terima kalau kode terasa "aneh" atau melewatkan sesuatu

### Model

- [ ] Jalankan:
```bash
php artisan make:model Nilai
php artisan make:model RekapNilai
```
- [ ] Buka `app/Models/Nilai.php`, tambahkan:
```php
protected $fillable = ['siswa_id', 'mengajar_id', 'jenis', 'nilai', 'tanggal_input', 'diinput_oleh'];

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

### View Form Nilai (Pakai AI)

- [ ] Lampirkan ke AI file: `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya sudah punya file resources/views/guru/nilai/form.blade.php yang berisi
data dummy (lihat isi file saat ini di bawah). Tolong ganti jadi versi yang
menerima data asli dari controller: $mengajar (objek berisi info kelas, mapel,
guru) dan $siswaList (koleksi siswa di kelas itu).

Gunakan komponen yang SUDAH ADA di 05-component-library.md — jangan bikin
struktur HTML baru dari nol. Pakai <x-table> untuk daftar siswa, dan
<x-form-select> kalau perlu dropdown jenis nilai (tugas/uts/uas).

Form harus:
- method="POST" ke route guru.nilai.store dengan parameter $mengajar->id
- @csrf
- Input nilai per siswa pakai name="nilai[{{ $siswa->id }}]" type number
  min="0" max="100"
- Dropdown/select untuk pilih jenis nilai (tugas/uts/uas), name="jenis"
- Tombol submit pakai <x-button variant="primary" type="submit">

Ikuti token warna dan gaya dari 01-design-system.md — jangan pakai warna
di luar yang sudah ditentukan di situ.

Isi file form.blade.php saat ini:
[TEMPEL ISI FILE resources/views/guru/nilai/form.blade.php DARI H2 DI SINI]
```

- [ ] Tempel hasil dari AI, timpa isi `resources/views/guru/nilai/form.blade.php`
- [ ] **Cek manual sebelum lanjut:**
  - [ ] Tidak ada warna/style yang keluar dari token di `01-design-system.md`
  - [ ] Komponen yang dipakai benar `<x-table>`, `<x-form-select>`, `<x-button>` — bukan tabel HTML mentah buatan AI sendiri
  - [ ] Ada `@csrf` di dalam form
- [ ] Test: buka `/guru/nilai` di browser (login sebagai guru dari seeder Iki), pilih salah satu kelas, pastikan form muncul dengan siswa asli dari database (bukan dummy lagi)
- [ ] Test: submit form isi nilai untuk 2-3 siswa, cek muncul pesan "berhasil"
- [ ] Cek di database, tabel `nilai` dan `rekap_nilai` ke-update

---

## H5 (Jumat) — Tampilan Siswa & Testing Sendiri

### Controller Siswa\NilaiController (Pakai AI)

- [ ] Lampirkan ke AI file: `laravel-docs/00-project-overview.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [ ] Tempel prompt berikut ke AI:

```
Saya sedang membuat controller Siswa\NilaiController untuk Laravel + Blade,
dan sekaligus view resources/views/siswa/nilai/index.blade.php.

Controller, method index():
- Ambil siswa yang sedang login (lewat relasi siswa->user_id = auth()->id())
- Ambil semua nilai milik siswa itu dari tabel `nilai`, dikelompokkan
  per mengajar (per mapel)
- Ambil juga data dari tabel `rekap_nilai` untuk tampilkan rata-rata
  per mapel
- Kalau `rekap_nilai` kosong untuk suatu mapel (belum ada baris),
  pakai fallback raw query:
  DB::select('SELECT fn_rata_rata_nilai(?, ?) as rata', [$siswaId, $mengajarId])
- Kirim semua data ke view siswa.nilai.index

View siswa/nilai/index.blade.php:
- Sudah ada versi dummy dari sebelumnya (saya lampirkan isinya di bawah),
  tolong ganti supaya menerima data asli dari controller
- Extend layouts.app
- Tampilkan pakai <x-table> dari 05-component-library.md, dikelompokkan
  per mapel: nama mapel sebagai judul kecil, lalu tabel nilai per jenis
  (tugas/uts/uas), lalu rata-rata di bawah tabel
- Ikuti token warna dari 01-design-system.md

Isi file index.blade.php saat ini (versi dummy):
[TEMPEL ISI FILE resources/views/siswa/nilai/index.blade.php DARI H2 DI SINI]
```

- [ ] Tempel hasil controller ke `app/Http/Controllers/Siswa/NilaiController.php`
- [ ] Tempel hasil view, timpa isi `resources/views/siswa/nilai/index.blade.php`
- [ ] **Cek manual:**
  - [ ] Ada fallback ke `fn_rata_rata_nilai` untuk kasus `rekap_nilai` kosong
  - [ ] View pakai `<x-table>`, bukan HTML mentah
  - [ ] Warna/style ikut token `01-design-system.md`
- [ ] Test: login sebagai siswa dari seeder Iki, buka `/siswa/nilai`, pastikan nilai yang diinput guru di H4 muncul di sini

### Self-Test Wajib

- [ ] **Self-test 1:** submit nilai untuk 1 kelas lewat form guru, lalu login sebagai salah satu siswa di kelas itu, cek nilai muncul benar di halaman siswa
- [ ] **Self-test 2:** edit nilai yang sudah diinput (submit ulang form dengan angka beda untuk siswa yang sama, jenis yang sama), lalu cek di halaman siswa apakah nilai dan rata-rata ikut berubah — kalau tidak berubah, berarti trigger `trg_rekap_nilai_update` belum benar, cek lagi ke H3
- [ ] **Self-test 3:** coba akses form input nilai untuk `mengajar_id` yang bukan milik guru yang sedang login (misal ganti angka di URL manual), pastikan sistem menolak atau tidak menampilkan data — kalau bisa diakses bebas, tambahkan pengecekan di controller
- [ ] **Self-test 4:** coba submit nilai di luar rentang 0-100 (misal 150), pastikan muncul pesan error dan tidak masuk ke database

### Peer Review

- [ ] Minta Hermanus buka kode modul nilai (controller, migration, file SQL), minta dia cek apakah pola transaction dan trigger sudah sesuai `laravel-docs/02-tech-conventions.md`
- [ ] Kalau Hermanus menemukan hal yang janggal, perbaiki hari ini juga (jangan ditunda ke H6)

---

## H6 (Sabtu) — Integrasi

- [ ] Ikut sesi testing end-to-end bareng tim sesuai skenario dari Noctura
- [ ] Siapkan alur testing modul nilai untuk didemoin: login guru → pilih kelas → input nilai sekelas → cek tersimpan → login siswa → cek nilai muncul
- [ ] Kalau ada bug yang ditemukan saat sesi bareng, perbaiki saat itu juga kalau memungkinkan, atau catat untuk diperbaiki setelah sesi

---

## H7 (Minggu) — Testing Akhir

- [ ] Ikut sesi testing akhir bareng tim
- [ ] Siapkan 1 skenario demo yang enak ditunjukkan: 1 kelas dengan beberapa siswa, nilai yang sudah terisi rapi (bukan angka acak testing), supaya kelihatan bagus saat presentasi
- [ ] Pastikan tidak ada sisa error/bug kecil yang mengganggu tampilan (misal label yang salah ketik, tombol yang belum ganti warna sesuai design system)
