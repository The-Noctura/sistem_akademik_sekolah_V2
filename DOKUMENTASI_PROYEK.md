# Website Sistem Akademik Sekolah (Laravel 13 + MySQL)

<<<<<<< HEAD
Website manajemen akademik — fokus pada pengelolaan nilai, absensi, dan jadwal pelajaran. Dibangun menggunakan Laravel 13 dengan penerapan logika kritis di level basis data menggunakan *Stored Procedures*, *Functions*, dan *Triggers* untuk menjamin integritas data serta performa rekapitulasi.
=======
Website manajemen akademik — fokus pada pengelolaan nilai, absensi, dan jadwal pelajaran. Dibangun menggunakan Laravel 13 dengan penerapan logika kritis di level basis data menggunakan _Stored Procedures_, _Functions_, dan _Triggers_ untuk menjamin integritas data serta performa rekapitulasi.
>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85

---

### 1. Data & Skema Basis Data

Sistem ini mengelola data master akademik dengan relasi yang ketat:
<<<<<<< HEAD
=======

>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
- **Master Data**: `users`, `guru`, `siswa`, `kelas`, `mapel`.
- **Relasi Pengajaran**: `mengajar` (penghubung guru, mapel, kelas, dan semester).
- **Modul Inti**: `jadwal`, `nilai`, `absensi`.
- **Modul Rekap (Auto-generate)**: `rekap_nilai`, `rekap_absensi`.
- **Audit Trail**: `log_perubahan` (mencatat jejak perubahan nilai).

---Kamu bertugas melakukan analisis menyeluruh terhadap codebase project Laravel bernama "sistem_akademik_sekolah_V2" (Laravel 13 + Blade + MySQL + Breeze Auth) dan menyusun satu file dokumentasi (Markdown) yang menjelaskan ALUR APLIKASI beserta ALUR KE DATABASE (termasuk trigger, foreign key constraint, dan relasi antar tabel yang relevan).

Dokumentasi ini akan digunakan oleh tim presentasi (4 orang) untuk menjelaskan sistem ke audiens non-teknis maupun teknis, jadi tulis dengan bahasa yang jelas dan terstruktur.

LANGKAH ANALISIS (lakukan secara berurutan, jangan menebak — baca file aslinya):
<<<<<<< HEAD
=======

>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
1. Baca seluruh routes (web.php, dan file route lain jika ada) untuk memetakan endpoint per role.
2. Baca middleware/gate/policy yang mengatur pembagian akses role (admin, guru, murid).
3. Baca controller terkait tiap role untuk memahami logic (proses apa yang terjadi, model apa yang dipanggil, request apa yang divalidasi).
4. Baca seluruh file migration untuk memetakan struktur tabel, foreign key, dan constraint.
5. Cari trigger, event listener, observer, atau job/queue yang berjalan otomatis akibat suatu aksi (misalnya: input nilai memicu update rata-rata, absensi memicu notifikasi, dsb). Jika tidak ada trigger database asli (native SQL trigger), jelaskan juga logic "trigger-like" di level aplikasi (Eloquent Observer/Event Listener) sebagai penggantinya.
6. Baca view (Blade) terkait untuk memahami halaman apa saja yang dilihat tiap role.

STRUKTUR OUTPUT YANG DIHARAPKAN (file markdown):

# Dokumentasi Alur Sistem Akademik Sekolah V2

## 1. Alur Role Admin
<<<<<<< HEAD
=======

>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
- Fitur/menu apa saja yang bisa diakses
- Alur proses tiap fitur utama (step by step, dari klik halaman → controller → model → tabel apa yang tersentuh)
- Efek samping ke database (insert/update/delete tabel mana, relasi FK apa yang terlibat, trigger/observer apa yang jalan)

## 2. Alur Role Guru
<<<<<<< HEAD
(struktur sama seperti di atas, fokus ke fitur guru: input nilai, absensi, jadwal mengajar, dll — sesuaikan dengan fitur asli di kode)

## 3. Alur Role Murid
(struktur sama seperti di atas, fokus ke fitur murid: lihat nilai, lihat absensi, lihat jadwal, dll)

## 4. Alur Halaman Public
=======

(struktur sama seperti di atas, fokus ke fitur guru: input nilai, absensi, jadwal mengajar, dll — sesuaikan dengan fitur asli di kode)

## 3. Alur Role Murid

(struktur sama seperti di atas, fokus ke fitur murid: lihat nilai, lihat absensi, lihat jadwal, dll)

## 4. Alur Halaman Public

>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
- Halaman apa saja yang bisa diakses tanpa login
- Alur login/register (jika ada) beserta tabel yang tersentuh
- Middleware apa yang menjaga halaman ini tetap publik/terbatas

## 5. Diagram Relasi Tabel (opsional tapi disarankan)
<<<<<<< HEAD
- Sertakan dalam bentuk teks/ASCII atau daftar FK antar tabel yang paling penting untuk dipahami alurnya

ATURAN PENTING:
=======

- Sertakan dalam bentuk teks/ASCII atau daftar FK antar tabel yang paling penting untuk dipahami alurnya

ATURAN PENTING:

>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
- Jangan mengarang fitur yang tidak ada di kode. Jika suatu bagian tidak ditemukan (misalnya tidak ada trigger native), katakan secara eksplisit "tidak ditemukan" alih-alih membuat asumsi.
- Sertakan nama file asli (path) di setiap bagian sebagai referensi, misalnya: (`app/Http/Controllers/GuruController.php`, `database/migrations/xxxx_create_nilai_table.php`)
- Gunakan bahasa Indonesia yang formal dan mudah dipahami tim presentasi (asumsikan mereka paham konsep dasar tapi tidak semua paham detail teknis Laravel).
- Simpan output akhir sebagai file bernama `ALUR_SISTEM.md` di root project.

### 2. Penerapan Logika Database (SQL Objects)

Logika perhitungan dan manipulasi data batch dipindahkan ke level database untuk menjamin konsistensi.

#### 2.1 Daftar Objek & Fungsinya
<<<<<<< HEAD
| Jenis | Nama | Fungsi |
|---|---|---|
| **FUNCTION** | `fn_rata_rata_nilai` | Menghitung rata-rata nilai siswa per mata pelajaran secara real-time. |
| **FUNCTION** | `fn_persentase_hadir` | Menghitung persentase kehadiran siswa. |
| **PROCEDURE** | `sp_input_nilai_kelas` | Input/update nilai secara batch dengan klausa `ON DUPLICATE KEY UPDATE`. |
| **PROCEDURE** | `sp_rekap_absensi` | Menghitung total hadir, izin, sakit, alpa dan memperbarui tabel rekap. |
| **TRIGGER** | `trg_rekap_nilai_insert` | Sinkronisasi tabel `rekap_nilai` saat nilai baru masuk. |
| **TRIGGER** | `trg_rekap_nilai_update` | Sinkronisasi tabel `rekap_nilai` saat nilai lama diubah. |
| **TRIGGER** | `trg_absensi_insert` | Otomatis menjalankan `sp_rekap_absensi` setelah input presensi. |
| **TRIGGER** | `trg_log_nilai_update` | Mencatat data lama dan baru ke `log_perubahan` saat nilai diedit. |

#### 2.2 Penggunaan Transaksi (COMMIT/ROLLBACK)
Operasi batch pada **Nilai** dan **Absensi** dilindungi oleh database transaction untuk mencegah data parsial jika terjadi kegagalan:
=======

| Jenis         | Nama                     | Fungsi                                                                   |
| ------------- | ------------------------ | ------------------------------------------------------------------------ |
| **FUNCTION**  | `fn_rata_rata_nilai`     | Menghitung rata-rata nilai siswa per mata pelajaran secara real-time.    |
| **FUNCTION**  | `fn_persentase_hadir`    | Menghitung persentase kehadiran siswa.                                   |
| **PROCEDURE** | `sp_input_nilai_kelas`   | Input/update nilai secara batch dengan klausa `ON DUPLICATE KEY UPDATE`. |
| **PROCEDURE** | `sp_rekap_absensi`       | Menghitung total hadir, izin, sakit, alpa dan memperbarui tabel rekap.   |
| **TRIGGER**   | `trg_rekap_nilai_insert` | Sinkronisasi tabel `rekap_nilai` saat nilai baru masuk.                  |
| **TRIGGER**   | `trg_rekap_nilai_update` | Sinkronisasi tabel `rekap_nilai` saat nilai lama diubah.                 |
| **TRIGGER**   | `trg_absensi_insert`     | Otomatis menjalankan `sp_rekap_absensi` setelah input presensi.          |
| **TRIGGER**   | `trg_log_nilai_update`   | Mencatat data lama dan baru ke `log_perubahan` saat nilai diedit.        |

#### 2.2 Penggunaan Transaksi (COMMIT/ROLLBACK)

Operasi batch pada **Nilai** dan **Absensi** dilindungi oleh database transaction untuk mencegah data parsial jika terjadi kegagalan:

>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
- **Input Nilai**: Menggunakan `DB::beginTransaction()`, memanggil `sp_input_nilai_kelas` dalam loop, lalu `DB::commit()`.
- **Input Absensi**: Menggunakan `DB::beginTransaction()`, melakukan insert ke tabel `absensi`, lalu `DB::commit()`.

---

### 3. Fitur Utama Aplikasi

- **Role-Based Access Control (RBAC)**:
<<<<<<< HEAD
  - **Admin**: CRUD data Master (User, Guru, Siswa, Kelas, Mapel, Mengajar, Jadwal).
  - **Guru**: Input Nilai (Tugas, UTS, UAS), Input Absensi per kelas, Lihat Jadwal mengajar.
  - **Siswa**: Lihat Nilai & Rapor, Lihat Rekap Absensi, Lihat Jadwal pelajaran.
- **Validasi Kritis**:
  - Validasi rentang nilai (0-100) di level Controller.
  - **Cross-check Siswa-Kelas**: Memastikan guru tidak menginput nilai/absensi untuk siswa yang bukan anggota kelas tersebut.
=======
    - **Admin**: CRUD data Master (User, Guru, Siswa, Kelas, Mapel, Mengajar, Jadwal).
    - **Guru**: Input Nilai (Tugas, UTS, UAS), Input Absensi per kelas, Lihat Jadwal mengajar.
    - **Siswa**: Lihat Nilai & Rapor, Lihat Rekap Absensi, Lihat Jadwal pelajaran.
- **Validasi Kritis**:
    - Validasi rentang nilai (0-100) di level Controller.
    - **Cross-check Siswa-Kelas**: Memastikan guru tidak menginput nilai/absensi untuk siswa yang bukan anggota kelas tersebut.
>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
- **Frontend Modern**: Menggunakan Blade + Tailwind CSS dengan komponen UI yang konsisten (Alert, Badge, Card, Table).

---

### 4. Akun Demo Testing
<<<<<<< HEAD
Data awal telah disediakan via `AkademikSeeder.php`:

| Peran | Email | Kata Sandi |
|---|---|---|
| Admin | `admin@sekolah.test` | `password` |
| Guru (MTK) | `budi@sekolah.test` | `password` |
| Guru (BIN) | `siti@sekolah.test` | `password` |
| Siswa | `siswa1001@sekolah.test` | `password` |
=======

Data awal telah disediakan via `AkademikSeeder.php`:

| Peran      | Email                    | Kata Sandi |
| ---------- | ------------------------ | ---------- |
| Admin      | `admin@sekolah.test`     | `password` |
| Guru (MTK) | `budi@sekolah.test`      | `password` |
| Guru (BIN) | `siti@sekolah.test`      | `password` |
| Siswa      | `siswa1001@sekolah.test` | `password` |
>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85

---

### 5. Struktur Folder Penting
<<<<<<< HEAD
=======

>>>>>>> 1abafe6895af563606e0b4857603c0a24d04ce85
- `app/Http/Controllers/`: Controller dibagi per folder role (`Admin/`, `Guru/`, `Siswa/`).
- `database/migrations/`: Definisi tabel dan objek SQL (SP/Function/Trigger).
- `docs/`: Dokumentasi teknis, skema, dan panduan pembangunan.
- `resources/views/`: Template Blade yang terstruktur dan modular.
