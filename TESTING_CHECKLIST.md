# MANUAL TESTING CHECKLIST — Sistem Akademik

**Project:** Website Sekolah Modul Akademik (Laravel + Blade + MySQL)
**Versi:** Final (Tahap 1-12 selesai)
**Tester:** 4 orang (dibagi per role/area)
**Tanggal:** _______________

---

## PEMBAGIAN TUGAS 4 TESTER

| Tester | Fokus Utama | Credentials |
|--------|-------------|-------------|
| **Tester A** | Admin Flow + Master Data CRUD | admin@sekolah.test / password |
| **Tester B** | Guru Flow (Nilai, Absensi, Jadwal) | budi@sekolah.test / password |
| **Tester C** | Guru 2 Flow + Cross-check data | siti@sekolah.test / password |
| **Tester D** | Siswa Flow (Lihat Nilai, Absensi, Jadwal) | siswa1001@sekolah.test / password |

---

## TESTER A — ADMIN FLOW & MASTER DATA CRUD

### A1. Login & Dashboard Admin
- [ ] Buka http://localhost:8000/login
- [ ] Login sebagai `admin@sekolah.test` / `password`
- [ ] Redirect ke `/dashboard` → tampil dashboard admin (Pola 3)
- [ ] Cek 5 menu: Manajemen User, Kelas, Mapel, Mengajar, Jadwal
- [ ] Navbar: nama user + role "Admin" + tombol Keluar berfungsi

### A2. Manajemen User (CRUD)
- [ ] **Index**: Buka `/admin/users` → tabel user tampil (nama, email, role, aksi)
- [ ] **Create**: Klik "Tambah User" → form validasi (nama, email unik, password+confirm, role dropdown)
- [ ] **Store**: Isi data guru baru → submit → success toast → data muncul di tabel
- [ ] **Edit**: Klik Edit user guru → form terisi → ubah nama/role → submit → success
- [ ] **Delete**: Klik Hapus user siswa → konfirmasi → data hilang dari tabel
- [ ] **Validasi**: Coba submit email duplikat → error "email already taken"
- [ ] **Validasi**: Coba submit password < 8 char → error

### A3. Manajemen Kelas (CRUD)
- [ ] **Index**: `/admin/kelas` → tabel (nama_kelas, tingkat, wali_kelas, tahun_ajaran)
- [ ] **Create**: Dropdown Wali Kelas menampilkan **nama guru** (bukan ID)
- [ ] **Store**: Isi data → submit → success
- [ ] **Edit**: Ubah wali kelas → submit → success
- [ ] **Delete**: Hapus kelas kosong → success

### A4. Manajemen Mapel (CRUD)
- [ ] **Index**: `/admin/mapel` → tabel (nama_mapel, kode_mapel)
- [ ] **Create/Edit/Delete**: Semua berfungsi normal

### A5. Manajemen Mengajar (CRUD) — **KRITIS**
- [ ] **Index**: `/admin/mengajar` → tabel (Guru, Mapel, Kelas, Tahun Ajaran, Semester)
- [ ] **Create**: 3 dropdown (Guru, Mapel, Kelas) semua menampilkan **nama**, bukan ID
- [ ] **Store**: Buat kombinasi guru+mapel+kelas+semester baru → success
- [ ] **Edit**: Ubah semester → success
- [ ] **Delete**: Hapus → success
- [ ] **Validasi**: Tidak bisa buat duplikat guru+mapel+kelas+semester sama

### A6. Manajemen Jadwal (CRUD)
- [ ] **Index**: `/admin/jadwal` → tabel (Guru-Mapel-Kelas, Hari, Jam, Ruangan)
- [ ] **Create**: Dropdown Mengajar menampilkan "Guru - Mapel - Kelas (Semester)"
- [ ] **Store**: Isi hari, jam_mulai, jam_selesai, ruangan → success
- [ ] **Validasi**: jam_selesai harus > jam_mulai
- [ ] **Edit/Delete**: Berfungsi

### A7. Visual Consistency Admin
- [ ] Semua halaman pakai layout Pola 1 (index) / Pola 2 (form)
- [ ] Warna konsisten: accent biru (#2563EB), surface abu muda
- [ ] Tombol: Primary biru, Secondary outline, Danger merah
- [ ] Tabel: hover row, header surface, border slate-200
- [ ] Pagination tampil di index yang >15 data

---

## TESTER B — GURU 1 (BUDI SANTOSO - MATEMATIKA)

### B1. Login & Dashboard Guru
- [ ] Logout admin → login `budi@sekolah.test` / `password`
- [ ] Redirect ke dashboard guru → 3 menu: Input Nilai, Input Absensi, Jadwal Mengajar
- [ ] Nama: "Budi Santoso, S.Pd" · role "Guru"

### B2. Input Nilai — Alur Lengkap
- [ ] **Index**: `/guru/nilai` → hanya 1 baris: "Matematika - X IPA 1 (ganjil)"
- [ ] **Form**: Klik "Input Nilai" → halaman form (Pola 2 varian tabel)
- [ ] **Dropdown Jenis**: Tugas / UTS / UAS
- [ ] **Tabel Siswa**: 10 siswa X IPA 1 tampil (nama + NIS + input nilai)
- [ ] **Input**: Isi nilai 4 siswa pertama: 85, 90, 78, 92 → Submit
- [ ] **Success**: Toast "Nilai berhasil disimpan" → redirect back ke form
- [ ] **Verifikasi DB**: Cek tabel `nilai` + `rekap_nilai` terisi 4 baris
- [ ] **Edit**: Ubah nilai siswa pertama jadi 88 → Submit → `rekap_nilai.rata_rata` berubah jadi 88
- [ ] **Validasi Rentang**: Input 150 → error "must not be greater than 100"
- [ ] **Validasi Rentang**: Input -10 → error "must be at least 0"
- [ ] **Cross-check**: (Manual DB) Coba insert nilai untuk siswa kelas lain → FK constraint tolak

### B3. Input Absensi — Alur Lengkap
- [ ] **Index**: `/guru/absensi` → list mengajar yang sama
- [ ] **Form**: Klik "Input Absensi" → tanggal default hari ini + tabel 10 siswa + dropdown status
- [ ] **Input**: Pilih Hadir/Izin/Sakit/Alpa untuk 4 siswa → Submit
- [ ] **Success**: Toast → redirect back
- [ ] **Verifikasi DB**: `absensi` + `rekap_absensi` terisi otomatis (trigger)
- [ ] **Rekap**: `total_hadir`, `total_izin`, `total_sakit`, `total_alpa`, `persentase_hadir` benar

### B4. Jadwal Mengajar (Read-Only)
- [ ] **Index**: `/guru/jadwal` → grouped by hari (Senin, Rabu untuk Matematika)
- [ ] Kolom: Mapel, Kelas, Jam Mulai, Jam Selesai, Ruangan
- [ ] **Tidak ada** tombol aksi (create/edit/delete)

### B5. Keamanan Guru
- [ ] Coba akses `/guru/nilai/2` (mengajar ID 2 = Bahasa Indonesia milik Siti) → **403 Forbidden**
- [ ] Coba akses `/admin/users` → **403 Forbidden**

---

## TESTER C — GURU 2 (SITI RAHAYU - BAHASA INDONESIA)

### C1. Login & Dashboard
- [ ] Login `siti@sekolah.test` / `password`
- [ ] Dashboard: 3 menu sama

### C2. Input Nilai (Bahasa Indonesia)
- [ ] **Index**: Hanya "Bahasa Indonesia - X IPA 1 (ganjil)"
- [ ] **Form**: Input nilai 4 siswa: 80, 85, 90, 75 → Submit
- [ ] **Verifikasi**: `nilai` + `rekap_nilai` terisi untuk mapel B.Indonesia
- [ ] **Edit**: Ubah salah satu → rekap update

### C3. Input Absensi (Bahasa Indonesia)
- [ ] **Form**: Tanggal hari ini + status beda dari Matematika
- [ ] **Verifikasi**: `rekap_absensi` terpisah per mengajar_id

### C3. Jadwal Mengajar
- [ ] **Index**: Senin/Kamis untuk Bahasa Indonesia (ruang 102)

### C4. Cross-check Data Isolation
- [ ] Pastikan Siti **tidak melihat** data Matematika di mana-mana
- [ ] Pastikan nilai/absensi Siti tidak tumpang tindih dengan Budi

---

## TESTER D — SISWA (AHMAD FAUZI - X IPA 1)

### D1. Login & Dashboard Siswa
- [ ] Login `siswa1001@sekolah.test` / `password`
- [ ] Dashboard: 3 menu: Lihat Nilai, Lihat Absensi, Lihat Jadwal
- [ ] Nama: "Ahmad Fauzi" · role "Siswa"

### D2. Lihat Nilai (Pola 4)
- [ ] **Index**: `/siswa/nilai` → 2 card: Matematika & Bahasa Indonesia
- [ ] **Card Matematika**:
  - [ ] Judul: "Matematika"
  - [ ] **Rata-rata besar**: `text-2xl font-semibold text-accent` (misal 88.00)
  - [ ] Tabel: Jenis (Tugas/UTS/UAS) + Nilai
  - [ ] Tugas: 88.00, UTS: -, UAS: -
- [ ] **Card Bahasa Indonesia**:
  - [ ] Rata-rata: 0 atau "-" (belum diinput guru)
  - [ ] Semua nilai: -
- [ ] **Read-only**: Tidak ada tombol edit/input

### D3. Lihat Absensi (Pola 4)
- [ ] **Index**: `/siswa/absensi` → 2 card
- [ ] **Card Matematika**:
  - [ ] Persentase hadir besar: `text-2xl font-semibold text-accent` (misal 100%)
  - [ ] Tabel: Tanggal + Badge status (Hadir=hijau, Izin=kuning, Sakit=biru, Alpa=merah)
  - [ ] Ringkasan 4 kotak: Hadir/Izin/Sakit/Alpa dengan count
- [ ] **Card Bahasa Indonesia**: Masih kosong (0%)

### D4. Lihat Jadwal (Pola 1 Read-Only)
- [ ] **Index**: `/siswa/jadwal` → grouped by hari
- [ ] **Senin**: Matematika 07:00-08:30 Ruang 101 Guru Budi
- [ ] **Selasa**: Bahasa Indonesia 07:00-08:30 Ruang 102 Guru Siti
- [ ] **Rabu**: Matematika...
- [ ] **Kamis**: Bahasa Indonesia...
- [ ] **Tidak ada** tombol aksi

### D5. Keamanan Siswa
- [ ] Coba akses `/guru/nilai` → **403**
- [ ] Coba akses `/admin/users` → **403**
- [ ] Coba akses `/siswa/nilai` manipulasi URL (misal id lain) → hanya data sendiri

### D6. Multi-Siswa Check (opsional)
- [ ] Login `siswa1002@sekolah.test` → data nilai/absensi beda (Bella Putri)
- [ ] Login `siswa1010@sekolah.test` → data Joko Widodo

---

## TEST CASES LINTAS ROLE (Dikerjakan Bersama)

### X1. Trigger & Procedure Verification
- [ ] **Trigger Nilai Insert**: Input nilai baru → `rekap_nilai` auto-create + rata_rata hitung
- [ ] **Trigger Nilai Update**: Edit nilai → `rekap_nilai.rata_rata` auto-update (trg_rekap_nilai_update)
- [ ] **Trigger Absensi Insert**: Input absensi → `rekap_absensi` auto-create via `sp_rekap_absensi`
- [ ] **Procedure sp_input_nilai_kelas**: ON DUPLICATE KEY UPDATE bekerja (edit = update, bukan insert baru)
- [ ] **Function fn_rata_rata_nilai**: Dipakai fallback saat rekap kosong
- [ ] **Function fn_persentase_hadir**: Dipakai fallback saat rekap_absensi kosong

### X2. Log Perubahan
- [ ] Edit nilai → cek tabel `log_perubahan` ada row baru (tabel='nilai', aksi='update', data_lama/baru JSON)

### X3. Unique Key Constraints
- [ ] Coba insert nilai duplikat (siswa_id + mengajar_id + jenis sama) → ON DUPLICATE KEY UPDATE jalan
- [ ] Coba insert absensi duplikat (siswa_id + mengajar_id + tanggal sama) → error unique key

### X4. Responsive & UI
- [ ] Mobile (<768px): Dashboard grid 1 kolom, tabel scroll horizontal
- [ ] Desktop: Dashboard grid 3 kolom
- [ ] Font: Inter di seluruh halaman
- [ ] Tidak ada warna hex hardcoded di Blade (semua pakai class Tailwind accent/surface)

### X5. Edge Cases
- [ ] Guru tanpa mengajar → dashboard kosong (tidak error)
- [ ] Siswa tanpa kelas → dashboard nilai/absensi/jadwal kosong (tidak error)
- [ ] Semester genap (buat data baru) → trigger ambil semester dinamis dari mengajar

---

## HASIL TESTING

| Tester | Total Test Case | Pass | Fail | Blocker | Catatan |
|--------|-----------------|------|------|---------|---------|
| A (Admin) | ~35 |   |   |   |   |
| B (Guru 1) | ~25 |   |   |   |   |
| C (Guru 2) | ~15 |   |   |   |   |
| D (Siswa) | ~20 |   |   |   |   |
| **Total** | **~95** |   |   |   |   |

**Tanda Tangan Tester:**
- Tester A: _______________ Tanggal: _______________
- Tester B: _______________ Tanggal: _______________
- Tester C: _______________ Tanggal: _______________
- Tester D: _______________ Tanggal: _______________

**Disetujui Lead QA:** _______________ Tanggal: _______________

---

## CATATAN UNTUK TESTER

1. **Jalankan test berurutan** sesuai nomor (A1→A2→A3...)
2. **Screenshot** setiap failure (error message, UI yang aneh)
3. **Isi kolom Catatan** dengan detail: langkah reproduksi, expected vs actual
4. **Jangan skip** validasi rentang nilai & cross-check siswa-kelas — ini bug critical
5. **Gunakan browser berbeda** (Chrome/Firefox) untuk test paralel
6. **Clear browser cache** sebelum mulai test