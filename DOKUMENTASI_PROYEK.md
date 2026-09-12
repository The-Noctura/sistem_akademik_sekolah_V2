# Dokumentasi Struktur, Flow, dan Fitur Sistem Akademik Sekolah V2

Dokumen ini merupakan versi final yang sudah dibersihkan dari artifact konflik dan disusun ulang agar lebih rapi, konsisten, dan mudah dipahami untuk keperluan dokumentasi project serta presentasi tim.

---

## 1. Gambaran Umum Project

Project ini adalah aplikasi sistem akademik sekolah berbasis Laravel + Blade + MySQL, dengan fokus utama pada:

- login dan autentikasi multi-role
- dashboard yang berbeda per role
- manajemen data admin
- input nilai oleh guru
- input absensi oleh guru
- akses read-only siswa terhadap nilai, absensi, dan jadwal
- halaman public sekolah, berita, dan katalog TEFA

Aplikasi ini dirancang untuk mempercepat operasional sekolah dalam mengelola data akademik secara terstruktur dan terintegrasi.

---

## 2. Struktur Folder Utama

Berikut struktur folder dan file utama yang relevan dalam sistem:

- `app/Http/Controllers/`
  - `DashboardController.php`
  - `PublicController.php`
  - `Admin/UserController.php`
  - `Admin/JadwalController.php`
  - `Admin/BeritaController.php`
  - `Admin/TefaProductController.php`
  - `Guru/NilaiController.php`
  - `Guru/AbsensiController.php`
  - `Guru/JadwalController.php`
  - `Siswa/NilaiController.php`
  - `Siswa/AbsensiController.php`
  - `Siswa/JadwalController.php`
- `app/Models/`
  - `User.php`
  - `Guru.php`
  - `Siswa.php`
  - `Kelas.php`
  - `Mapel.php`
  - `Mengajar.php`
  - `Jadwal.php`
  - `Nilai.php`
  - `Absensi.php`
  - `RekapNilai.php`
  - `RekapAbsensi.php`
  - `Berita.php`
  - `TefaProduct.php`
- `database/migrations/`
  - definisi tabel master dan relasi akademik
- `database/seeders/`
  - `AkademikSeeder.php`
  - `TefaAndBeritaSeeder.php`
- `resources/views/`
  - `layouts/app.blade.php`
  - `layouts/public.blade.php`
  - `public/*`
  - `admin/*`
  - `guru/*`
  - `siswa/*`
- `routes/web.php`
- `routes/auth.php`
- `docs/02-sql-objects.sql`

---

## 3. Flow Aplikasi Secara Umum

### 3.1 Flow login dan autentikasi

Alur login dimulai dari halaman public menuju login system:

1. User masuk ke halaman `/login`
2. `AuthenticatedSessionController` memvalidasi email dan password
3. Sistem memeriksa autentikasi dan role user
4. Jika valid, user diarahkan ke `/dashboard`
5. `DashboardController` akan memilih view dashboard sesuai role user

Role yang digunakan biasanya adalah:

- `admin`
- `guru`
- `siswa`

Akses antar role dibatasi melalui middleware role agar user tidak bisa masuk ke area yang bukan haknya.

### 3.2 Flow role-based access

Akses dibatasi berdasarkan role:

- admin masuk ke prefix `/admin`
- guru masuk ke prefix `/guru`
- siswa masuk ke prefix `/siswa`
- halaman public tetap dapat diakses tanpa login

Jika user mencoba masuk ke route yang tidak sesuai role, sistem akan menolak dengan `403` atau redirect sesuai policy aplikasi.

---

## 4. Alur Role Admin

Admin memiliki tugas utama sebagai pengelola sistem dan data sekolah.

### 4.1 Fitur utama admin

- manajemen user
- manajemen guru
- manajemen siswa
- manajemen kelas
- manajemen mapel
- manajemen jadwal
- manajemen berita
- manajemen produk TEFA
- dashboard admin

### 4.2 Flow admin dalam penggunaan aplikasi

1. Admin login ke sistem
2. Masuk ke dashboard admin
3. Mengakses menu manajemen user atau data master
4. Mengelola data yang dibutuhkan seperti kelas, mapel, mengajar, dan jadwal
5. Untuk data akademik, admin dapat memastikan relasi antar tabel sudah benar sehingga guru dan siswa bisa melihat data yang sesuai

### 4.3 Flow admin terkait user management

Pada modul user management:

- admin bisa menambah user baru
- admin menentukan role pengguna
- admin menentukan status aktif atau nonaktif
- jika role guru atau siswa dipilih, sistem akan membuat data pelengkap di tabel terkait

Semua proses ini dilakukan melalui controller admin dan disimpan ke tabel `users`, `guru`, atau `siswa` sesuai role.

---

## 5. Alur Role Guru

Guru adalah role yang paling aktif dalam operasional akademik.

### 5.1 Fitur utama guru

- dashboard guru
- input nilai
- input absensi
- lihat jadwal mengajar
- lihat kelas yang diampu

### 5.2 Flow input nilai

1. Guru login ke sistem
2. Masuk ke menu penilaian
3. Memilih kelas dan mata pelajaran yang diajarkan
4. Pilih jenis nilai (`tugas`, `uts`, atau `uas`)
5. Mengisi kolom nilai per siswa
6. Validasi dilakukan untuk memastikan siswa berada di kelas yang benar
7. Data dikirim ke controller
8. Controller memanggil stored procedure `sp_input_nilai_kelas`
9. Trigger database otomatis memperbarui rekap nilai

### 5.3 Flow input absensi

1. Guru masuk ke menu absensi
2. Memilih kelas dan tanggal presensi
3. Mengisi status kehadiran siswa (`hadir`, `izin`, `sakit`, `alpa`)
4. Data dikirim ke controller
5. Controller memvalidasi kelas yang sesuai
6. Proses absensi dilakukan dalam transaction
7. Stored procedure `sp_rekap_absensi` dijalankan untuk menghitung rekap kehadiran
8. Data tersimpan ke tabel `absensi` dan `rekap_absensi`

### 5.4 Flow jadwal guru

Guru dapat melihat jadwal mengajar yang relevan dengan dirinya. Data ini biasanya diambil dari tabel `mengajar` dan `jadwal` dengan filtering berdasarkan `guru_id`.

---

## 6. Alur Role Siswa

Siswa masuk ke aplikasi untuk melihat informasi akademik mereka secara terbatas.

### 6.1 Fitur utama siswa

- dashboard siswa
- lihat nilai
- lihat absensi
- lihat jadwal pelajaran

### 6.2 Flow siswa

1. Siswa login
2. Dashboard menampilkan informasi yang relevan dengan kelas siswa
3. Siswa bisa melihat nilai per mata pelajaran dan rekap rata-rata
4. Siswa bisa melihat absensi dan persentase kehadiran
5. Siswa bisa melihat jadwal pelajaran berdasarkan kelasnya

Siswa tidak memiliki hak untuk mengubah data akademik.

---

## 7. Alur Halaman Public

Halaman publik dapat diakses tanpa login. Fungsinya adalah memberikan informasi umum mengenai sekolah serta konten publik.

### 7.1 Menu public utama

- landing page sekolah
- profil sekolah
- program keahlian
- fasilitas
- berita
- produk TEFA
- kontak

### 7.2 Flow public website

1. Pengunjung membuka halaman home
2. Sistem menampilkan berita terbaru dan produk TEFA yang tersedia
3. Jika user membuka detail berita, sistem mengambil data dari tabel `berita`
4. Jika user membuka katalog TEFA, sistem mengambil data dari tabel `tefa_products`

Data ini diatur melalui `PublicController` dan `resources/views/public/*`.

---

## 8. Relasi Tabel Utama

Relasi yang penting dalam sistem ini adalah:

- `users` berhubungan dengan `guru` dan `siswa`
- `guru` berhubungan dengan `mengajar`
- `mapel` berhubungan dengan `mengajar`
- `kelas` berhubungan dengan `mengajar` dan `siswa`
- `mengajar` berhubungan dengan `jadwal`, `nilai`, `absensi`
- `siswa` berhubungan dengan `nilai` dan `absensi`
- `rekap_nilai` dan `rekap_absensi` digunakan untuk ringkasan data akademik

Relasi ini dipakai untuk memastikan data guru, siswa, kelas, mapel, dan jadwal terhubung dengan benar.

---

## 9. Logika Database: Trigger, Function, Procedure

Bagian ini sangat penting karena banyak perhitungan dan rekap data tidak dibuat di PHP, tetapi langsung dikerjakan oleh database.

### 9.1 Daftar SQL object

| Jenis | Nama | Fungsi |
|---|---|---|
| FUNCTION | `fn_rata_rata_nilai` | Menghitung rata-rata nilai siswa per mata pelajaran |
| FUNCTION | `fn_persentase_hadir` | Menghitung persentase kehadiran siswa |
| PROCEDURE | `sp_input_nilai_kelas` | Menyimpan atau memperbarui nilai siswa dalam satu kelas |
| PROCEDURE | `sp_rekap_absensi` | Menghitung rekap absensi per siswa atau per mengajar |
| TRIGGER | `trg_rekap_nilai_insert` | Memperbarui `rekap_nilai` saat nilai baru masuk |
| TRIGGER | `trg_rekap_nilai_update` | Memperbarui `rekap_nilai` saat nilai diubah |
| TRIGGER | `trg_log_nilai_update` | Mencatat riwayat perubahan nilai |
| TRIGGER | `trg_absensi_insert` | Menjalankan rekap absensi setelah input absensi |

### 9.2 Letak SQL object

Semua objek SQL tersebut terdapat pada file:

- `docs/02-sql-objects.sql`

Laravel tidak menulis ulang semua logika tersebut di controller. Controller hanya memanggil procedure atau function yang sudah ada di database, misalnya:

- `DB::statement('CALL sp_input_nilai_kelas(...)')`
- `DB::statement('CALL sp_rekap_absensi(...)')`

### 9.3 Kenapa SQL object penting?

Karena fungsi dan trigger ini membantu:

- menjaga konsistensi data
- mengurangi duplikasi logika di PHP
- menghitung rekap otomatis
- menyinkronkan data nilai dan absensi dengan cepat

---

## 10. Validasi Kritis di Aplikasi

Ada beberapa validasi penting yang harus selalu dijalankan oleh controller agar data tidak salah:

- validasi nilai 0–100
- validasi jenis nilai harus sesuai (`tugas`, `uts`, `uas`)
- cross-check siswa dan kelas sebelum input nilai/absensi
- transaction untuk operasi batch
- validasi guru hanya bisa mengelola kelas yang diajar

Ini penting karena tabel tidak selalu menjamin kombinasi data yang benar.

---

## 11. Akun Demo untuk Testing

Berikut akun demo yang biasanya dipakai saat testing:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@sekolah.test` | `password` |
| Guru | `budi@sekolah.test` | `password` |
| Guru | `siti@sekolah.test` | `password` |
| Siswa | `siswa1001@sekolah.test` | `password` |

Akun ini dibuat untuk memudahkan pengecekan flow admin, guru, dan siswa secara langsung di aplikasi.

---

## 12. Catatan Penting Setelah Fix Konflik Dokumen

Dokumen ini sudah dibersihkan dari tanda konflik seperti:

- `<<<<<<< HEAD`
- `=======`
- `>>>>>>> ...`

Tujuan dari pembenahan ini adalah agar dokumentasi tidak membingungkan dan bisa dipakai untuk:

- presentasi project
- handover tim
- dokumentasi pengembangan lanjutan
- referensi saat review fitur dan route

---

## 13. Kesimpulan

Sistem akademik sekolah V2 ini memiliki arsitektur yang cukup lengkap untuk kebutuhan pengelolaan sekolah, dengan pemisahan yang jelas antara:

- area public
- area admin
- area guru
- area siswa

Selain itu, penggunaan SQL object seperti trigger, function, dan procedure memastikan data nilai serta absensi tetap konsisten dan otomatis ter-rekap. Dengan dokumentasi yang sudah dibersihkan dan disusun ulang, alur aplikasi menjadi lebih mudah dipahami dan lebih siap digunakan untuk presentasi maupun pengembangan lebih lanjut.
