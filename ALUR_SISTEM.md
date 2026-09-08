# Dokumentasi Alur Sistem Akademik Sekolah V2

## 1. Alur Role Admin
- **Fitur/Menu:** Pengelolaan User (`UserController`), Kelas (`KelasController`), Mata Pelajaran (`MapelController`), Data Mengajar (`MengajarController`), dan Jadwal (`JadwalController`).
- **Alur Proses:** 
    - Admin mengakses menu terkait.
    - Controller memvalidasi request (termasuk `unique` constraint pada `MengajarController`) lalu melakukan operasi CRUD pada model/tabel.
    - Data yang diubah/ditambah tersimpan ke tabel terkait (users, kelas, mapel, mengajar, jadwal).
- **Efek Samping Database:**
    - Perubahan data di tabel `mengajar` atau `jadwal` dapat memengaruhi data nilai dan absensi yang merujuk padanya melalui FK dengan `onDelete('cascade')`.

## 2. Alur Role Guru
- **Fitur/Menu:** Input Nilai (`NilaiController`), Absensi (`AbsensiController`), dan Jadwal Mengajar (`JadwalController`).
- **Alur Proses:**
    - Guru memilih kelas yang diajar.
    - Untuk **Nilai**: Controller memvalidasi cross-check siswa-kelas, lalu memanggil `DB::statement('CALL sp_input_nilai_kelas(...)')`.
    - Untuk **Absensi**: Controller memvalidasi siswa-kelas, lalu menyimpan ke tabel `absensi`.
- **Efek Samping Database:**
    - Trigger `trg_rekap_nilai_insert`/`update` (untuk nilai) dan `trg_absensi_insert` (untuk absensi) secara otomatis menjalankan procedure `sp_rekap_absensi` atau fungsi kalkulasi untuk memperbarui tabel rekap ( `rekap_nilai`, `rekap_absensi`).
    - Audit Trail: Trigger `trg_log_nilai_update` mencatat perubahan nilai ke `log_perubahan`.

## 3. Alur Role Murid
- **Fitur/Menu:** Melihat Nilai (`Siswa\NilaiController`), Absensi (`Siswa\AbsensiController`), dan Jadwal (`Siswa\JadwalController`).
- **Alur Proses:**
    - Murid mengakses dashboard/halaman terkait.
    - Controller mengambil data nilai/absensi milik murid dari tabel `nilai`/`absensi` dan rekap dari `rekap_nilai`/`rekap_absensi` (menggunakan function SQL `fn_rata_rata_nilai` dan `fn_persentase_hadir` jika rekap belum ada).

## 4. Alur Halaman Public
- **Halaman:** Home, Tentang, Program Keahlian, Fasilitas, Berita, Kontak.
- **Akses:** Bebas (tanpa login).
- **Login/Register:** Menggunakan `Auth\AuthenticatedSessionController` (login) dan `Auth\RegisteredUserController` (register), tersimpan di tabel `users`.
- **Middleware:** `guest` untuk halaman register/login, `auth` + `verified` untuk dashboard.

## 5. Diagram Relasi Tabel (Penting)
- `users` (1:1) `guru`/`siswa`.
- `guru` (1:N) `mengajar`.
- `kelas` (1:N) `mengajar` & `siswa`.
- `mapel` (1:N) `mengajar`.
- `mengajar` (1:N) `nilai`, `absensi`, `jadwal`, `rekap_nilai`, `rekap_absensi`.
- `siswa` (1:N) `nilai`, `absensi`, `rekap_nilai`, `rekap_absensi`.
