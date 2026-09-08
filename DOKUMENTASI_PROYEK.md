# Website Sistem Akademik Sekolah (Laravel 13 + MySQL)

Website manajemen akademik — fokus pada pengelolaan nilai, absensi, dan jadwal pelajaran. Dibangun menggunakan Laravel 13 dengan penerapan logika kritis di level basis data menggunakan *Stored Procedures*, *Functions*, dan *Triggers* untuk menjamin integritas data serta performa rekapitulasi.

---

### 1. Data & Skema Basis Data

Sistem ini mengelola data master akademik dengan relasi yang ketat:
- **Master Data**: `users`, `guru`, `siswa`, `kelas`, `mapel`.
- **Relasi Pengajaran**: `mengajar` (penghubung guru, mapel, kelas, dan semester).
- **Modul Inti**: `jadwal`, `nilai`, `absensi`.
- **Modul Rekap (Auto-generate)**: `rekap_nilai`, `rekap_absensi`.
- **Audit Trail**: `log_perubahan` (mencatat jejak perubahan nilai).

---

### 2. Penerapan Logika Database (SQL Objects)

Logika perhitungan dan manipulasi data batch dipindahkan ke level database untuk menjamin konsistensi.

#### 2.1 Daftar Objek & Fungsinya
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
- **Input Nilai**: Menggunakan `DB::beginTransaction()`, memanggil `sp_input_nilai_kelas` dalam loop, lalu `DB::commit()`.
- **Input Absensi**: Menggunakan `DB::beginTransaction()`, melakukan insert ke tabel `absensi`, lalu `DB::commit()`.

---

### 3. Fitur Utama Aplikasi

- **Role-Based Access Control (RBAC)**:
  - **Admin**: CRUD data Master (User, Guru, Siswa, Kelas, Mapel, Mengajar, Jadwal).
  - **Guru**: Input Nilai (Tugas, UTS, UAS), Input Absensi per kelas, Lihat Jadwal mengajar.
  - **Siswa**: Lihat Nilai & Rapor, Lihat Rekap Absensi, Lihat Jadwal pelajaran.
- **Validasi Kritis**:
  - Validasi rentang nilai (0-100) di level Controller.
  - **Cross-check Siswa-Kelas**: Memastikan guru tidak menginput nilai/absensi untuk siswa yang bukan anggota kelas tersebut.
- **Frontend Modern**: Menggunakan Blade + Tailwind CSS dengan komponen UI yang konsisten (Alert, Badge, Card, Table).

---

### 4. Akun Demo Testing
Data awal telah disediakan via `AkademikSeeder.php`:

| Peran | Email | Kata Sandi |
|---|---|---|
| Admin | `admin@sekolah.test` | `password` |
| Guru (MTK) | `budi@sekolah.test` | `password` |
| Guru (BIN) | `siti@sekolah.test` | `password` |
| Siswa | `siswa1001@sekolah.test` | `password` |

---

### 5. Struktur Folder Penting
- `app/Http/Controllers/`: Controller dibagi per folder role (`Admin/`, `Guru/`, `Siswa/`).
- `database/migrations/`: Definisi tabel dan objek SQL (SP/Function/Trigger).
- `docs/`: Dokumentasi teknis, skema, dan panduan pembangunan.
- `resources/views/`: Template Blade yang terstruktur dan modular.
