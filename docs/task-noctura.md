# Task Noctura — Jadwal + Koordinasi (PM)

**Modul:** CRUD Jadwal (paling ringan, murni CRUD tanpa trigger/procedure) + koordinasi tim
**Acuan:** `pembagian-tugas-laravel.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/04-routes-and-structure.md`, `laravel-docs/05-component-library.md`

---

## H1 (Senin) — Fasilitasi Sinkronisasi

- [x] Bikin agenda singkat sebelum sesi (3 poin: konfirmasi skema final, urutan kerja, siapa pegang modul apa)
- [x] Pimpin sesi sinkronisasi bareng Iki, Nabil, Hermanus
- [x] Di sesi itu, konfirmasi bareng: skema database final sesuai `laravel-docs/03-database-schema.md`, tidak ada perubahan
- [x] Di sesi itu, konfirmasi urutan kerja: Iki bikin migration `mengajar` duluan (H2), baru Nabil/Hermanus/Noctura bisa submit data asli (H3-H4)
- [x] Di sesi itu, konfirmasi lagi siapa pegang modul apa (Iki=Auth&Master Data, Nabil=Nilai, Hermanus=Absensi, Noctura=Jadwal) sesuai `pembagian-tugas-laravel.md`
- [x] Buat 1 tempat tracking progress bareng — bisa spreadsheet sederhana dengan kolom: Nama, H1, H2, H3, H4, H5, H6, H7, isi status tiap hari (selesai/belum/blocker)
- [x] Share link tracking ke grup tim
- [x] Pastikan semua orang sudah punya akses ke repo Git yang dibuat Iki

---

## H2 (Selasa) — Setup Modul Sendiri

- [x] Jalankan:

```bash
git pull
```

- [x] Buat folder `resources/views/admin/jadwal/`
- [x] Buat folder `resources/views/guru/jadwal/`
- [x] Buat folder `resources/views/siswa/jadwal/`
- [x] Buka `laravel-docs/05-component-library.md`, cari bagian "Layout Dasar" dan "Tabel Data"
- [x] Buat file `resources/views/admin/jadwal/index.blade.php` dengan struktur dasar:

```blade
@extends('layouts.app')
@section('content')
    <h1 class="text-xl font-semibold mb-6">Manajemen Jadwal</h1>
@endsection
```

- [x] Simpan file (isi tabel dan form menyusul H3 setelah migration ada)

### Cek-in Singkat ke Tim

- [x] Tanya di grup: Iki sudah mulai migration?
- [x] Tanya di grup: Nabil sudah mulai setup Blade mock?
- [ ] Tanya di grup: Hermanus sudah mulai setup Blade mock?
- [x] Catat kalau ada yang stuck di hari ini, follow up langsung personal

---

## H3 (Rabu) — Migration Jadwal + Mulai Bantu Pantau

### Migration `jadwal`

- [x] Jalankan:

```bash
git pull
```

(pastikan sudah dapat migration dari Iki: `users`, `guru`, `siswa`, `kelas`, `mapel`, `mengajar`)

- [x] Jalankan:

```bash
php artisan make:migration create_jadwal_table
```

- [x] Buka file migration yang baru dibuat
- [x] Di dalam method `up()`, tulis persis:

```php
Schema::create('jadwal', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mengajar_id')->constrained('mengajar');
    $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->string('ruangan');
    $table->timestamps();
});
```

- [x] Simpan file
- [x] Jalankan:

```bash
php artisan migrate
```

- [x] Cek tidak ada error, buka database, pastikan tabel `jadwal` sudah muncul

### Model

- [x] Jalankan:

```bash
php artisan make:model Jadwal
```

- [x] Buka `app/Models/Jadwal.php`, tambahkan:

```php
protected $fillable = ['mengajar_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];

public function mengajar()
{
    return $this->belongsTo(Mengajar::class);
}
```

- [x] Simpan file

### Controller CRUD Admin (Pakai AI)

- [x] Jalankan:

```bash
php artisan make:controller Admin/JadwalController --resource
```

- [x] Buka `laravel-docs/04-routes-and-structure.md`, salin baris route `/admin/jadwal`
- [x] Buka `routes/web.php`, tambahkan di dalam group `admin`:

```php
Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);
```

- [x] Buat folder `resources/views/admin/jadwal/` (kalau belum ada dari H2)
- [x] Lampirkan ke AI file: `laravel-docs/00-project-overview.md`, `laravel-docs/02-tech-conventions.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [x] Tempel prompt berikut ke AI:

```
Saya butuh CRUD lengkap untuk Admin\JadwalController (Laravel resource
controller) dan 3 view Blade-nya: resources/views/admin/jadwal/index.blade.php,
create.blade.php, edit.blade.php.

Model: Jadwal, kolom: mengajar_id (dropdown dari tabel mengajar — tampilkan
gabungan nama guru + mapel + kelas biar jelas, bukan cuma id), hari (enum:
senin/selasa/rabu/kamis/jumat/sabtu), jam_mulai, jam_selesai (keduanya time),
ruangan.

Controller (index/create/store/edit/update/destroy):
- index() — ambil semua jadwal dengan eager load relasi mengajar (yang
  berisi guru, mapel, kelas), kirim ke view
- create()/store() — form tambah, validasi hari wajib salah satu opsi enum,
  jam_mulai dan jam_selesai wajib format waktu valid dan jam_selesai harus
  setelah jam_mulai
- edit()/update()/destroy() — pola sama seperti CRUD lain di project ini

Views (ikuti 05-component-library.md, jangan bikin HTML baru dari nol):
- index.blade.php — extend layouts.app, tampilkan <x-table> dengan kolom:
  Hari, Jam, Ruangan, Guru, Mapel, Kelas, Aksi (edit/hapus)
- create.blade.php — extend layouts.app, <x-form-select> untuk mengajar_id
  dan hari, <x-form-input> untuk jam_mulai/jam_selesai (type time) dan
  ruangan, <x-button> submit
- edit.blade.php — sama seperti create tapi field ke-fill data existing

Ikuti token warna dari 01-design-system.md untuk semua view.
```

- [x] Tempel hasil controller ke `app/Http/Controllers/Admin/JadwalController.php`
- [x] Tempel hasil view ke 3 file di `resources/views/admin/jadwal/`
- [x] **Cek manual:** validasi jam_selesai setelah jam_mulai ada, dropdown mengajar_id menampilkan nama yang jelas (bukan angka id)
- [ ] Test: buka `/admin/jadwal`, coba tambah 1 data jadwal percobaan, pastikan muncul di list

### Cek-in ke Iki

- [ ] Tanya langsung ke Iki: broadcast tabel final + akun testing sudah dilakukan hari ini?
- [ ] Kalau belum, tanya apa ada blocker, bantu kalau bisa

### Kalau Modul Jadwal Selesai Lebih Cepat

- [x] Jangan tambah fitur baru ke jadwal yang tidak diminta (misal filter kompleks, kalender visual)
- [ ] Alihkan sisa waktu untuk cek progress Nabil dan Hermanus — tanya langsung apa ada yang bisa dibantu

---

## H4 (Kamis) — Read-Only View + Koordinasi

### Controller Guru\JadwalController & Siswa\JadwalController (Pakai AI)

- [x] Jalankan:

```bash
php artisan make:controller Guru/JadwalController
php artisan make:controller Siswa/JadwalController
```

- [ ] Buka `laravel-docs/04-routes-and-structure.md`, salin baris route `/guru/jadwal` dan `/siswa/jadwal`
- [ ] Tambahkan route di `routes/web.php` dalam group `guru` dan `siswa`
- [x] Buat folder `resources/views/guru/jadwal/` dan `resources/views/siswa/jadwal/`
- [x] Lampirkan ke AI file: `laravel-docs/00-project-overview.md`, `laravel-docs/03-database-schema.md`, `laravel-docs/01-design-system.md`, `laravel-docs/05-component-library.md`
- [x] Tempel prompt berikut ke AI:

```
Saya butuh 2 controller read-only (cuma method index, tanpa create/edit/
delete) untuk Laravel + Blade:

1. Guru\JadwalController — method index():
   - Ambil guru yang sedang login (relasi guru->user_id = auth()->id())
   - Ambil semua jadwal yang mengajar_id-nya terhubung ke guru itu
     (lewat relasi jadwal->mengajar->guru_id)
   - Kirim ke view guru.jadwal.index

2. Siswa\JadwalController — method index():
   - Ambil siswa yang sedang login (relasi siswa->user_id = auth()->id())
   - Ambil kelas_id milik siswa itu
   - Ambil semua jadwal yang mengajar_id-nya punya kelas_id yang sama
   - Kirim ke view siswa.jadwal.index

Views (ikuti 05-component-library.md, jangan bikin HTML baru dari nol):
- resources/views/guru/jadwal/index.blade.php — extend layouts.app,
  tampilkan <x-table> dengan kolom: Hari, Jam, Ruangan, Mapel, Kelas
  (tanpa kolom Guru karena sudah pasti dirinya sendiri)
- resources/views/siswa/jadwal/index.blade.php — extend layouts.app,
  tampilkan <x-table> dengan kolom: Hari, Jam, Ruangan, Mapel, Guru

Kedua view read-only saja, tidak perlu form atau tombol aksi apa pun.

Ikuti token warna dari 01-design-system.md.
```

- [x] Tempel hasil controller ke `app/Http/Controllers/Guru/JadwalController.php` dan `app/Http/Controllers/Siswa/JadwalController.php`
- [x] Tempel hasil view ke `resources/views/guru/jadwal/index.blade.php` dan `resources/views/siswa/jadwal/index.blade.php`
- [x] **Cek manual:** query guru hanya ambil jadwal miliknya sendiri, query siswa hanya ambil jadwal kelasnya sendiri (bukan semua jadwal)
- [ ] Test: login sebagai guru, buka `/guru/jadwal`, pastikan cuma jadwal miliknya yang muncul
- [ ] Test: login sebagai siswa, buka `/siswa/jadwal`, pastikan jadwal kelasnya muncul

### Cek-in Progress Harian

- [x] Tanya Nabil: sudah sampai mana di controller & transaction untuk nilai?
- [ ] Tanya Hermanus: sudah sampai mana di controller & transaction untuk absensi?
- [ ] Update spreadsheet tracking dari H1 sesuai laporan tim
- [ ] Kalau ada yang jauh di belakang jadwal, diskusikan apa yang bisa disederhanakan (misal skip validasi non-kritis dulu, fokus alur utama)

### Keputusan Produk Cepat (Kalau Ada)

- [ ] Kalau Nabil atau Hermanus tanya soal keputusan yang tidak ada di dokumentasi (misal pesan error spesifik, aturan edge case), putuskan saat itu juga — jangan biarkan mereka nunggu lama
- [ ] Broadcast keputusan yang diambil ke grup supaya semua tahu

---

## H5 (Jumat) — Koordinasi Peer Review

- [ ] Pastikan Nabil dan Hermanus sudah saling review modul satu sama lain hari ini (bukan cuma self-test masing-masing) — follow up langsung kalau belum jalan
- [ ] Tulis skenario testing end-to-end untuk H6, format singkat langkah demi langkah, contoh:
  1. Login sebagai admin, cek data master lengkap
  2. Login sebagai guru A, buka modul nilai, input nilai untuk 1 kelas
  3. Login sebagai siswa dari kelas itu, cek nilai muncul
  4. Login sebagai guru A lagi, buka modul absensi, input absensi untuk kelas yang sama
  5. Login sebagai siswa yang sama, cek rekap absensi muncul
  6. Cek jadwal muncul benar di sisi guru dan siswa
- [ ] Share skenario ini ke grup sebelum H6 supaya semua sudah baca duluan

### Cek Progress Modul Jadwal

- [ ] Kalau modul jadwal (H3-H4) belum sepenuhnya rapi, ini jadi prioritas terakhir — kalau waktu H5 habis untuk koordinasi, modul jadwal boleh dikorbankan dulu, bukan modul nilai/absensi

---

## H6 (Sabtu) — Koordinasi Integrasi

- [ ] Pimpin sesi testing end-to-end bareng tim, ikuti skenario yang ditulis di H5 langkah demi langkah
- [ ] Ajukan pertanyaan berikut ke Nabil (modul nilai):
  - [ ] "Coba edit nilai yang sudah ada, cek apakah rata-rata di halaman siswa ikut berubah"
  - [ ] "Coba input nilai untuk siswa dari kelas lain, apa sistem menolak?"
- [ ] Ajukan pertanyaan berikut ke Hermanus (modul absensi):
  - [ ] "Coba matiin koneksi/simulasikan error di tengah submit absensi sekelas, apa data yang sudah masuk ikut ke-rollback semua?"
  - [ ] "Coba login sebagai siswa, bisa akses halaman input absensi guru?"
- [ ] Catat semua bug yang ditemukan di spreadsheet tracking, kolom baru: "Bug ditemukan H6"
- [ ] Bagi siapa yang fix bug apa (biasanya pemilik modul masing-masing)
- [ ] Set target: semua bug kritis (yang menghalangi alur utama) selesai sebelum H7

---

## H7 (Minggu) — Testing Akhir & Demo Prep

- [ ] Koordinasi sesi testing akhir bareng tim, ulangi skenario H5-H6 sekali lagi memastikan bug sudah fix
- [ ] Review alur pakai dari sisi user flow — buka dokumen scope website yang sudah dibuat sebelumnya, cocokkan tiap fitur must have sudah sesuai alur yang direncanakan
- [ ] Susun urutan demo: modul mana ditunjukkan duluan (disarankan: login → dashboard → input nilai → lihat nilai siswa → input absensi → lihat rekap siswa → jadwal)
- [ ] Pastikan akun demo dari seeder Iki masih bersih dan siap dipakai
- [ ] Sisakan waktu buffer untuk hal tak terduga (device bermasalah saat demo, internet putus, dst) — siapkan alternatif seperti screenshot/screen recording sebagai cadangan
