# Dokumentasi Alur Sistem Akademik Sekolah V2

Dokumen ini menjelaskan perilaku aplikasi berdasarkan kode aktual pada repository Laravel + Blade + MySQL. Nama file yang dicantumkan adalah referensi implementasi, bukan asumsi dari rancangan dokumentasi.

## Ringkasan Arsitektur

Aplikasi memakai dua lapisan:

1. **Website publik**: route tanpa `auth`, halaman dirender oleh `PublicController` dan data konten utama disimpan hardcode di controller.
2. **Sistem akademik**: route login dan route berdasarkan role. Setelah autentikasi, `/dashboard` memilih dashboard sesuai `users.role`.

Alur umum sistem akademik:

```text
Browser
  -> route Laravel
  -> middleware auth dan role
  -> controller
  -> Eloquent model / stored procedure
  -> tabel MySQL dan FK/trigger
  -> Blade view
```

Sumber utama: `routes/web.php`, `routes/auth.php`, `app/Http/Middleware/EnsureUserHasRole.php`, `app/Http/Controllers/DashboardController.php`, `bootstrap/app.php`.

---

## 1. Alur Role Admin

### Akses dan menu

Semua route admin berada di dalam `auth` dan `role:admin`, dengan prefix `/admin` dan nama route `admin.*` (`routes/web.php`). Menu dashboard admin mengarah ke:

- Manajemen User: `admin.users.*`
- Manajemen Kelas: `admin.kelas.*`
- Manajemen Mapel: `admin.mapel.*`
- Manajemen Mengajar: `admin.mengajar.*`
- Manajemen Jadwal: `admin.jadwal.*`

Dashboard menghitung jumlah guru, siswa, kelas, dan mapel dari database (`app/Http/Controllers/DashboardController.php`, `resources/views/admin/dashboard.blade.php`).

### Pembatasan akses

Alias middleware `role` didaftarkan di `bootstrap/app.php` dan menggunakan `app/Http/Middleware/EnsureUserHasRole.php`.

- User dengan role selain `admin` menerima HTTP 403.
- User berstatus `nonaktif` dikeluarkan dari sesi dan menerima HTTP 403.
- Selain middleware, modul nilai dan absensi memiliki pengecekan kepemilikan `mengajar` sendiri, tetapi admin tidak memakai modul input tersebut.

### 1.1 Manajemen User

Referensi: `app/Http/Controllers/Admin/UserController.php`, `app/Models/User.php`, `resources/views/admin/users/`.

1. Admin membuka `/admin/users`; controller mengambil user berstatus aktif melalui scope `User::aktif()` dan pagination.
2. Form tambah memvalidasi `nama`, email unik, password minimal 8 karakter dan konfirmasi, serta role `admin/guru/siswa`.
3. Password di-hash menggunakan `Hash::make()`, lalu insert ke tabel `users`.
4. Edit melakukan validasi serupa dan update kolom user. Password hanya diubah jika diisi.
5. Hapus tidak menghapus baris; controller mengubah `users.status` menjadi `nonaktif`.
6. Pada login/request berikutnya, akun nonaktif ditolak oleh `LoginRequest` atau middleware role.

Efek database:

- Insert/update ke `users`.
- Tidak ada insert otomatis ke `guru` atau `siswa` ketika role dipilih. Karena itu, CRUD user aktual hanya membuat akun; profil guru/siswa harus sudah dibuat oleh seeder atau proses lain.
- FK `guru.user_id` dan `siswa.user_id` memakai `ON DELETE CASCADE`, tetapi aksi destroy admin di sini adalah soft-disable melalui status sehingga FK tidak aktif.

### 1.2 Manajemen Kelas

Referensi: `app/Http/Controllers/Admin/KelasController.php`, `app/Models/Kelas.php`, `resources/views/admin/kelas/`.

1. Index mengambil kelas beserta wali kelas dan pagination.
2. Form create/edit mengambil daftar guru untuk dropdown.
3. Store/update memvalidasi nama kelas, tingkat, tahun ajaran, dan wali kelas opsional yang harus ada di `guru.id`.
4. Data disimpan ke tabel `kelas`.
5. Delete menghapus baris `kelas`.

Efek FK:

- `kelas.wali_kelas_id -> guru.id` dengan `ON DELETE SET NULL`; penghapusan guru membuat wali kelas menjadi kosong.
- `siswa.kelas_id -> kelas.id` juga `ON DELETE SET NULL`; penghapusan kelas tidak menghapus siswa, tetapi melepas kelasnya.
- `mengajar.kelas_id -> kelas.id` memakai `ON DELETE CASCADE`; penghapusan kelas dapat menghapus penugasan mengajar beserta data turunannya melalui cascade.

### 1.3 Manajemen Mapel

Referensi: `app/Http/Controllers/Admin/MapelController.php`, `app/Models/Mapel.php`, `resources/views/admin/mapel/`.

1. Index hanya menampilkan mapel aktif melalui `Mapel::aktif()`.
2. Store/update memvalidasi nama dan kode mapel, kemudian insert/update tabel `mapel`.
3. Delete bersifat soft-disable: `mapel.status` diubah menjadi `nonaktif`.

FK penting: `mengajar.mapel_id -> mapel.id` dengan `ON DELETE CASCADE`. Namun karena controller menggunakan status nonaktif, data mengajar lama tetap ada.

### 1.4 Manajemen Mengajar

Referensi: `app/Http/Controllers/Admin/MengajarController.php`, `app/Models/Mengajar.php`, `resources/views/admin/mengajar/`.

1. Index memuat `guru`, `mapel`, dan `kelas` melalui eager loading.
2. Create/edit mengisi dropdown dari tabel `guru`, `mapel`, dan `kelas`.
3. Store/update memvalidasi seluruh FK dan kombinasi unik guru + mapel + kelas + tahun ajaran + semester.
4. Insert/update dilakukan ke tabel `mengajar`.
5. Delete menghapus penugasan tersebut.

`mengajar` adalah penghubung utama. Tabel `jadwal`, `nilai`, `absensi`, `rekap_nilai`, dan `rekap_absensi` menyimpan `mengajar_id`, sehingga satu baris mengajar menentukan guru, mapel, kelas, tahun ajaran, dan semester yang digunakan modul akademik.

Constraint database `mengajar_unique_kombinasi` didefinisikan di `database/migrations/2026_09_05_155405_add_unique_to_mengajar_table.php`; validasi aplikasi juga dilakukan di controller.

Efek cascade dari `mengajar`:

- `jadwal`, `nilai`, `absensi`, `rekap_nilai`, dan `rekap_absensi` dapat ikut terhapus karena FK masing-masing `ON DELETE CASCADE`.

### 1.5 Manajemen Jadwal

Referensi: `app/Http/Controllers/Admin/JadwalController.php`, `app/Models/Jadwal.php`, `resources/views/admin/jadwal/`.

1. Create/edit mengambil daftar penugasan mengajar dan menampilkan label guru - mapel - kelas - semester.
2. Request memvalidasi `mengajar_id`, hari, format jam, dan jam selesai harus setelah jam mulai.
3. `checkScheduleConflict()` menolak bentrok jam untuk guru yang sama dan ruangan yang sama pada hari yang sama.
4. Store/update insert atau update tabel `jadwal`; destroy menghapusnya.

FK `jadwal.mengajar_id -> mengajar.id` memakai `ON DELETE CASCADE`. Tidak ada trigger database untuk konflik jadwal; pengecekan dilakukan langsung oleh controller.

### Batasan peran admin

Dashboard admin menyediakan CRUD struktur akademik, tetapi tidak menyediakan route admin untuk input nilai atau absensi. Admin tidak mengisi `nilai`/`absensi` melalui route yang tersedia.

---

## 2. Alur Role Guru

### Akses dan menu

Route guru memakai `auth`, `role:guru`, prefix `/guru`, dan nama `guru.*` (`routes/web.php`). Dashboard menampilkan statistik kelas, mapel, dan siswa berdasarkan `guru_id` user yang sedang login (`app/Http/Controllers/DashboardController.php`, `resources/views/guru/dashboard.blade.php`).

Profil guru ditemukan melalui relasi `User::guru()` ke tabel `guru`. Jika profil guru tidak ada, controller guru yang langsung memakai `$guru->id` dapat gagal; seeder atau data master harus menyediakan baris profil tersebut.

### 2.1 Input Nilai

Referensi: `app/Http/Controllers/Guru/NilaiController.php`, `app/Models/Nilai.php`, `app/Models/Mengajar.php`, `resources/views/guru/nilai/index.blade.php`, `resources/views/guru/nilai/form.blade.php`.

1. `GET /guru/nilai` mengambil hanya baris `mengajar` dengan `guru_id` milik guru login.
2. Guru memilih satu penugasan. Form memuat semua siswa dengan `siswa.kelas_id` yang sama dengan `mengajar.kelas_id`.
3. Form memilih jenis `tugas`, `uts`, atau `uas`, lalu mengirim array nilai siswa sekaligus.
4. Controller memvalidasi jenis, array nilai, angka, dan rentang `0..100`.
5. Controller memeriksa lagi bahwa `mengajar.guru_id` adalah guru login. Akses URL ke penugasan guru lain menghasilkan 403.
6. Sebelum transaction, setiap siswa dicek: siswa harus ada dan `siswa.kelas_id` harus sama dengan `mengajar.kelas_id`.
7. Controller membuka transaction dan memanggil `CALL sp_input_nilai_kelas(...)` satu kali per siswa (`DB::statement`). Jika semua berhasil commit; jika salah satu gagal rollback semua.
8. Procedure melakukan insert atau update berdasarkan unique key `nilai(siswa_id, mengajar_id, jenis)`.

Efek database:

- Insert baru ke `nilai` menjalankan `trg_rekap_nilai_insert`, yang menghitung rata-rata lewat `fn_rata_rata_nilai` dan upsert ke `rekap_nilai`.
- Update nilai, termasuk update akibat `ON DUPLICATE KEY UPDATE`, menjalankan `trg_rekap_nilai_update` dan menyinkronkan `rekap_nilai`.
- Update nilai juga menjalankan `trg_log_nilai_update`, yang insert audit ke `log_perubahan` dengan nilai lama dan baru serta `diinput_oleh`.
- FK `nilai.siswa_id`, `nilai.mengajar_id`, dan `nilai.diinput_oleh` menjaga referensi; semuanya didefinisikan dengan cascade delete.

Tidak ada route CRUD manual untuk `rekap_nilai`; tabel tersebut dibaca oleh siswa dan dipelihara trigger.

### 2.2 Input Absensi

Referensi: `app/Http/Controllers/Guru/AbsensiController.php`, `app/Models/Absensi.php`, `resources/views/guru/absensi/index.blade.php`, `resources/views/guru/absensi/form.blade.php`.

1. `GET /guru/absensi` hanya menampilkan penugasan milik guru login.
2. Guru membuka form penugasan tertentu; controller menolak penugasan milik guru lain.
3. Form menerima tanggal dan status setiap siswa: `hadir`, `izin`, `sakit`, atau `alpa`.
4. Request divalidasi sebagai tanggal, array status, dan enum status yang sah.
5. Controller melakukan cross-check siswa-kelas sebelum transaction.
6. Di dalam transaction, setiap status dibuat dengan `Absensi::create()`; jika ada kegagalan, seluruh batch rollback.
7. Unique key `absensi(siswa_id, mengajar_id, tanggal)` mencegah absensi ganda untuk siswa dan tanggal yang sama.

Efek database:

- Insert `absensi` menjalankan `trg_absensi_insert`.
- Trigger mengambil semester dari `mengajar.semester` lalu memanggil `sp_rekap_absensi`.
- Procedure menghitung total hadir/izin/sakit/alpa dan persentase, lalu upsert `rekap_absensi`.
- Tidak ada route manual untuk CRUD `rekap_absensi`.

Catatan aktual: trigger yang tersedia adalah `AFTER INSERT`; dokumentasi SQL tidak mendefinisikan trigger update/delete absensi. Karena controller memakai insert baru dan unique key, koreksi absensi pada tanggal sama dapat menimbulkan pelanggaran unique key, bukan update otomatis.

### 2.3 Lihat Jadwal Mengajar

Referensi: `app/Http/Controllers/Guru/JadwalController.php`, `app/Models/Jadwal.php`, `resources/views/guru/jadwal/index.blade.php`.

1. Controller mengambil `mengajar.id` milik guru login.
2. Controller mengambil jadwal dengan relasi mapel dan kelas, mengurutkan hari Senin-Sabtu serta jam.
3. View mengelompokkan tampilan berdasarkan hari.
4. Halaman read-only tanpa tombol edit/hapus.

Alur ini hanya membaca `jadwal`, `mengajar`, `mapel`, dan `kelas`; tidak ada perubahan database.

---

## 3. Alur Role Murid/Siswa

### Akses dan menu

Route siswa memakai `auth`, `role:siswa`, prefix `/siswa`, dan nama `siswa.*` (`routes/web.php`). Dashboard menampilkan jumlah mapel, rata-rata nilai, dan persentase hadir (`app/Http/Controllers/DashboardController.php`, `resources/views/siswa/dashboard.blade.php`).

Controller mengambil profil dari `Auth::user()->siswa`, lalu membatasi query berdasarkan `siswa.id` dan `siswa.kelas_id` milik user tersebut.

### 3.1 Lihat Nilai

Referensi: `app/Http/Controllers/Siswa/NilaiController.php`, `app/Models/Nilai.php`, `app/Models/RekapNilai.php`, `resources/views/siswa/nilai/index.blade.php`.

1. Controller mengambil kelas siswa login.
2. Controller mengambil semua `mengajar` untuk kelas tersebut dan relasi mapel.
3. Untuk setiap mapel, controller membaca nilai `tugas`, `uts`, dan `uas` hanya untuk siswa login.
4. Controller mencoba membaca `rekap_nilai` untuk siswa, penugasan, dan semester yang cocok.
5. Jika rata-rata rekap kosong, controller memakai fallback SQL `fn_rata_rata_nilai(siswa_id, mengajar_id)`.
6. View menampilkan kartu per mapel, rata-rata, rincian jenis nilai, dan bila tersedia nilai akhir/predikat.

Halaman ini read-only. Tidak ada insert/update/delete dari route siswa.

### 3.2 Lihat Absensi

Referensi: `app/Http/Controllers/Siswa/AbsensiController.php`, `app/Models/Absensi.php`, `app/Models/RekapAbsensi.php`, `resources/views/siswa/absensi/index.blade.php`.

1. Controller mengambil semua penugasan untuk kelas siswa login.
2. Untuk tiap penugasan, controller mengambil baris absensi milik `siswa.id` tersebut.
3. Controller mengambil `rekap_absensi` sesuai siswa, penugasan, dan semester.
4. Jika persentase belum tersedia, controller memanggil fallback `fn_persentase_hadir`.
5. View menampilkan persentase, daftar tanggal/status, dan jumlah hadir/izin/sakit/alpa.

Halaman read-only dan tidak membuka data siswa lain.

### 3.3 Lihat Jadwal Pelajaran

Referensi: `app/Http/Controllers/Siswa/JadwalController.php`, `app/Models/Jadwal.php`, `resources/views/siswa/jadwal/index.blade.php`.

1. Controller membaca `kelas_id` siswa login.
2. Controller mengambil `mengajar.id` untuk kelas itu.
3. Controller mengambil jadwal beserta mapel dan guru, lalu mengurutkan per hari dan jam.
4. View menampilkan jadwal per hari tanpa aksi perubahan.

Jika siswa belum punya kelas, view menampilkan kondisi kosong dan tidak melakukan query jadwal lanjutan.

---

## 4. Alur Halaman Publik dan Auth

### 4.1 Halaman publik tanpa login

Route publik didefinisikan tanpa middleware `auth` di `routes/web.php`:

| URL                        | Controller/method              | View                                    | Sumber data                                              |
| -------------------------- | ------------------------------ | --------------------------------------- | -------------------------------------------------------- |
| `/`                        | `PublicController@home`        | `resources/views/public/home.blade.php` | hardcode `programsData()` dan `newsData()`               |
| `/tentang`                 | `PublicController@about`       | `public/about.blade.php`                | konten Blade                                             |
| `/program-keahlian`        | `PublicController@programs`    | `public/programs.blade.php`             | `programsData()`                                         |
| `/program-keahlian/{code}` | `PublicController@programShow` | `public/program-show.blade.php`         | pencarian array berdasarkan kode; kode tidak dikenal 404 |
| `/fasilitas`               | `PublicController@facilities`  | `public/facilities.blade.php`           | konten Blade                                             |
| `/berita`                  | `PublicController@news`        | `public/news.blade.php`                 | `newsData()` hardcode                                    |
| `/kontak`                  | `PublicController@contact`     | `public/contact.blade.php`              | konten Blade; form kontak demo                           |

Layout publik dan menu ada di `resources/views/layouts/public.blade.php`. Halaman publik tidak memanggil model akademik dan tidak menulis ke database. Form kontak pada view belum memiliki endpoint penyimpanan/email; tidak ada alur database untuk form tersebut.

### 4.2 Login

Route GET/POST `/login` berada dalam middleware `guest` (`routes/auth.php`).

1. GET menampilkan `resources/views/auth/login.blade.php` melalui `AuthenticatedSessionController@create`.
2. POST memakai `LoginRequest` (`app/Http/Requests/Auth/LoginRequest.php`).
3. Request memvalidasi email/password, menerapkan rate limit 5 percobaan, lalu `Auth::attempt()` melalui provider Eloquent `User` (`config/auth.php`).
4. Login user nonaktif dibatalkan.
5. Session diregenerasi, kemudian user diarahkan ke `/dashboard`.
6. `DashboardController@index` membaca role dan meneruskan ke dashboard admin, guru, atau siswa.

Database yang dibaca/ditulis: tabel `users` dibaca; tabel `sessions` dapat ditulis oleh session driver. Login tidak menulis tabel akademik.

### 4.3 Register

Route GET/POST `/register` juga berada dalam middleware `guest` (`routes/auth.php`), melalui `RegisteredUserController` (`app/Http/Controllers/Auth/RegisteredUserController.php`).

Alur yang dimaksud Breeze:

1. Validasi nama, email unik, dan password.
2. Insert user baru.
3. Event `Registered` dipancarkan.
4. User langsung login dan diarahkan ke dashboard.

**Perbedaan implementasi aktual yang perlu diketahui presentasi/maintainer:** migration users menggunakan kolom `nama` dan enum `role`, sedangkan `RegisteredUserController` mengirim field `name` dan tidak mengirim `role`. Model `User` hanya mengizinkan `nama`, bukan `name`. Akibatnya register publik tidak membentuk profil akademik guru/siswa dan berpotensi menghasilkan user tanpa nama aplikasi yang benar; role database akan mengikuti default schema (`siswa`). Route register tersedia, tetapi belum menjadi onboarding akademik lengkap.

Tidak ada pembuatan otomatis baris `guru`/`siswa` dari event `Registered` yang ditemukan.

### 4.4 Password, verifikasi, profile, logout

Route auth tambahan berada di `routes/auth.php`, controller-nya di `app/Http/Controllers/Auth/`:

- Forgot/reset password membaca `password_reset_tokens` dan meng-update `users.password`.
- Verifikasi email meng-update `users.email_verified_at` dan memancarkan event bawaan `Verified`.
- Ganti password meng-update `users.password` setelah validasi password lama.
- Logout mengakhiri session lalu redirect ke `/`.
- `/profile` berada dalam middleware `auth`; update mengubah data user sesuai `ProfileUpdateRequest`, delete akun menghapus `users` setelah validasi password.

Tidak ditemukan policy atau Gate khusus. Pembagian akses dilakukan oleh middleware `auth` dan alias `role`.

---

## 5. Alur Database, Relasi, Constraint, dan Otomatisasi

### 5.1 Tabel dan FK utama

Migration sumber: `database/migrations/0001_01_01_000000_create_users_table.php`, seluruh migration `2026_08_30_*.php`, serta `database/migrations/2026_09_05_*.php`.

```text
users
  |-- guru.user_id -> users.id                 ON DELETE CASCADE
  |-- siswa.user_id -> users.id                ON DELETE CASCADE
  |-- nilai.diinput_oleh -> users.id           ON DELETE CASCADE
  |-- log_perubahan.user_id -> users.id        ON DELETE CASCADE

 guru
  |-- kelas.wali_kelas_id -> guru.id           ON DELETE SET NULL
  |-- mengajar.guru_id -> guru.id              ON DELETE CASCADE

kelas
  |-- siswa.kelas_id -> kelas.id               ON DELETE SET NULL
  |-- mengajar.kelas_id -> kelas.id            ON DELETE CASCADE

mapel
  |-- mengajar.mapel_id -> mapel.id            ON DELETE CASCADE

mengajar
  |-- jadwal.mengajar_id -> mengajar.id        ON DELETE CASCADE
  |-- nilai.mengajar_id -> mengajar.id         ON DELETE CASCADE
  |-- absensi.mengajar_id -> mengajar.id       ON DELETE CASCADE
  |-- rekap_nilai.mengajar_id -> mengajar.id   ON DELETE CASCADE
  |-- rekap_absensi.mengajar_id -> mengajar.id ON DELETE CASCADE

siswa
  |-- nilai.siswa_id -> siswa.id               ON DELETE CASCADE
  |-- absensi.siswa_id -> siswa.id             ON DELETE CASCADE
  |-- rekap_nilai.siswa_id -> siswa.id         ON DELETE CASCADE
  |-- rekap_absensi.siswa_id -> siswa.id       ON DELETE CASCADE
```

Relasi Eloquent yang merepresentasikan hubungan ini berada di `app/Models/User.php`, `Guru.php`, `Siswa.php`, `Kelas.php`, `Mapel.php`, `Mengajar.php`, `Jadwal.php`, `Nilai.php`, `Absensi.php`, `RekapNilai.php`, dan `RekapAbsensi.php`.

### 5.2 Constraint non-FK penting

- `users.email` unique (`0001_01_01_000000_create_users_table.php`).
- `nilai(siswa_id, mengajar_id, jenis)` unique (`2026_08_30_045707_create_nilai_table.php`); menjadi dasar upsert nilai.
- `absensi(siswa_id, mengajar_id, tanggal)` unique (`2026_08_30_045725_create_absensi_table.php`); mencegah absensi ganda pada tanggal sama.
- `rekap_nilai(siswa_id, mengajar_id, semester)` unique (`2026_08_30_045804_create_rekap_nilai_table.php`).
- `rekap_absensi(siswa_id, mengajar_id, semester)` unique (`2026_08_30_045822_create_rekap_absensi_table.php`).
- Kombinasi `guru_id, mapel_id, kelas_id, tahun_ajaran, semester` pada `mengajar` unique (`2026_09_05_155405_add_unique_to_mengajar_table.php`).
- Enum membatasi role/status/jenis nilai/status absensi/hari.

FK tidak memeriksa bahwa siswa berada di kelas yang sama dengan `mengajar`. Pemeriksaan itu dilakukan manual di `Guru\NilaiController` dan `Guru\AbsensiController` sebelum transaction. Rentang nilai 0-100 juga divalidasi di controller.

### 5.3 Function dan procedure SQL

Sumber: `docs/02-sql-objects.sql`; command eksekusi/verifikasi: `app/Console/Commands/DbRunSqlObjects.php`.

- `fn_rata_rata_nilai(siswa_id, mengajar_id)`: menghitung rata-rata nilai.
- `fn_persentase_hadir(siswa_id, mengajar_id)`: menghitung persentase status hadir.
- `sp_input_nilai_kelas(...)`: insert/update satu nilai memakai `ON DUPLICATE KEY UPDATE`; dipanggil controller guru dalam transaction.
- `sp_rekap_absensi(...)`: menghitung ulang rekap absensi dan upsert ke `rekap_absensi`; dipanggil trigger, bukan controller.

### 5.4 Trigger native MySQL

Sumber: `docs/02-sql-objects.sql`.

| Trigger                  | Event                     | Efek                                                                      |
| ------------------------ | ------------------------- | ------------------------------------------------------------------------- |
| `trg_rekap_nilai_insert` | `AFTER INSERT ON nilai`   | Membuat/memperbarui `rekap_nilai.rata_rata` memakai `fn_rata_rata_nilai`. |
| `trg_rekap_nilai_update` | `AFTER UPDATE ON nilai`   | Menghitung ulang `rekap_nilai` saat nilai diedit.                         |
| `trg_log_nilai_update`   | `AFTER UPDATE ON nilai`   | Menulis audit update ke `log_perubahan`, termasuk nilai lama/baru.        |
| `trg_absensi_insert`     | `AFTER INSERT ON absensi` | Mengambil semester dari `mengajar`, lalu memanggil `sp_rekap_absensi`.    |

Tabel rekap bukan input manual dari user. `rekap_nilai` dan `rekap_absensi` adalah denormalisasi yang disinkronkan oleh trigger. Fallback function di controller siswa dipakai ketika baris rekap atau nilai agregat belum tersedia.

### 5.5 Observer, event listener, job, dan queue

Pencarian terhadap `app/` tidak menemukan Observer, Event Listener, class Job, atau implementasi `ShouldQueue` untuk proses akademik. Event auth bawaan Breeze/Laravel (`Registered`, `Verified`, `PasswordReset`, `Lockout`) memang digunakan untuk autentikasi, tetapi bukan pengganti trigger nilai/absensi.

Dengan demikian:

- Otomatisasi akademik utama adalah **native MySQL trigger/procedure/function**.
- Tidak ditemukan trigger-like Eloquent Observer untuk nilai atau absensi.
- Tidak ditemukan notifikasi otomatis akibat input nilai/absensi.
- Tabel `jobs`, `job_batches`, dan `failed_jobs` berasal dari migration bawaan; tidak ada alur job akademik yang ditemukan.

---

## 6. Daftar View per Role

Sumber: `resources/views/`.

### Admin

- `admin/dashboard.blade.php`
- `admin/users/{index,create,edit}.blade.php`
- `admin/kelas/{index,create,edit}.blade.php`
- `admin/mapel/{index,create,edit}.blade.php`
- `admin/mengajar/{index,create,edit}.blade.php`
- `admin/jadwal/{index,create,edit}.blade.php`

### Guru

- `guru/dashboard.blade.php`
- `guru/nilai/{index,form}.blade.php`
- `guru/absensi/{index,form}.blade.php`
- `guru/jadwal/index.blade.php`

### Siswa

- `siswa/dashboard.blade.php`
- `siswa/nilai/index.blade.php`
- `siswa/absensi/index.blade.php`
- `siswa/jadwal/index.blade.php`

### Publik dan auth

- Publik: `resources/views/public/*.blade.php` dan `resources/views/layouts/public.blade.php`.
- Auth: `resources/views/auth/*.blade.php` dan layout guest/app.
- Komponen UI bersama: `resources/views/components/`.

---

## 7. Kesimpulan untuk Presentasi

1. **Admin menyiapkan master data**: akun, kelas, mapel, penugasan mengajar, lalu jadwal.
2. **Guru bekerja hanya pada penugasan miliknya**: menginput nilai dan absensi per kelas secara batch.
3. **Siswa hanya membaca data miliknya**: nilai, rekap kehadiran, dan jadwal kelas.
4. **`mengajar` adalah titik penghubung utama** antara guru, mapel, kelas, semester, jadwal, nilai, dan absensi.
5. **Rekap nilai dan absensi berjalan otomatis di MySQL** melalui trigger dan stored procedure.
6. **Keamanan akses** berasal dari middleware `auth` + `role`, ditambah pengecekan kepemilikan penugasan pada controller guru.
7. **Website publik tidak bergantung pada database akademik**; konten publik yang ditemukan masih berasal dari Blade/controller hardcode.
8. **Catatan implementasi**: register publik belum membuat profil `guru/siswa` dan memakai field `name` yang tidak sesuai dengan kolom/model `nama`; ini perlu diperbaiki bila registrasi publik akan digunakan untuk onboarding akademik.
