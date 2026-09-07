# MANUAL TESTING CHECKLIST — Sistem Akademik

**Project:** Website Sekolah Modul Akademik (Laravel + Blade + MySQL)
**Versi:** Final (Tahap 1-12 selesai)
**Tester:** 4 orang (dibagi per role/area)
**Tanggal:** 6 September 2026

---

## PEMBAGIAN TUGAS 4 TESTER

| Tester       | Fokus Utama                               | Credentials                       |
| ------------ | ----------------------------------------- | --------------------------------- |
| **Tester A** | Admin Flow + Master Data CRUD             | admin@sekolah.test / password     |
| **Tester B** | Guru Flow (Nilai, Absensi, Jadwal)        | budi@sekolah.test / password      |
| **Tester C** | Guru 2 Flow + Cross-check data            | siti@sekolah.test / password      |
| **Tester D** | Siswa Flow (Lihat Nilai, Absensi, Jadwal) | siswa1001@sekolah.test / password |

---

## TESTER A — ADMIN FLOW & MASTER DATA CRUD

### A1. Login & Dashboard Admin

- [x] Buka http://localhost:8000/login
- [x] Login sebagai `admin@sekolah.test` / `password`
- [x] Redirect ke `/dashboard` → tampil dashboard admin (stat cards + 5 menu cards)
- [x] Cek 5 menu: Manajemen User, Kelas, Mapel, Mengajar, Jadwal → semua link berfungsi
- [x] Navbar: nama user + role "Admin" + tombol Keluar berfungsi
- [x] **Icon menu tampil** (Tabler Icons via CDN)
- [x] **Tombol "Kembali" di kanan atas** setiap halaman index

### A2. Manajemen User (CRUD) — **Soft Delete via Status**

- [x] **Index**: Buka `/admin/users` → tabel user tampil (nama, email, role, status, aksi) => tampil dengan baik tapi status gak muncul
- [x] **Index**: Hanya user dengan `status = 'aktif'` yang tampil
- [x] **Create**: Klik "Tambah User" → form validasi (nama, email unik, password+confirm, role dropdown)
- [x] **Password Confirm Real-time**: Ketik password & konfirmasi → indikator hijau/merah "Password cocok/tidak cocok" + tombol Simpan disable saat tidak cocok => berjalan baik tapi tombol simpan tidak disable saat tidak cocok
- [x] **Store**: Isi data guru baru → submit → success toast → data muncul di tabel
- [x] **Edit**: Klik Edit user guru → form terisi → ubah nama/role → submit → success
- [x] **Delete (Soft)**: Klik Hapus user siswa → konfirmasi → user **tidak hilang dari DB**, status berubah `nonaktif` → tidak tampil di index
- [x] **Validasi**: Coba submit email duplikat → error "email already taken"
- [x] **Validasi**: Coba submit password < 8 char → error

### A3. Manajemen Kelas (CRUD)

- [x] **Index**: `/admin/kelas` → tabel (nama_kelas, tingkat, wali_kelas, tahun_ajaran) + tombol "Kembali" + "Tambah Kelas"
- [x] **Create**: Dropdown Wali Kelas menampilkan **nama guru** (bukan ID)
- [x] **Store**: Isi data → submit → success
- [x] **Edit**: Ubah wali kelas → submit → success (route parameter `{kelas}` benar)
- [x] **Delete**: Hapus kelas kosong → success (route parameter `{kelas}` benar)

### A4. Manajemen Mapel (CRUD) — **Soft Delete via Status**

- [x] **Index**: `/admin/mapel` → tabel (nama_mapel, kode_mapel, status, aksi) + tombol "Kembali" + "Tambah Mapel" => tidak ada kolom status
- [x] **Index**: Hanya mapel dengan `status = 'aktif'` yang tampil
- [x] **Create/Edit**: Berfungsi normal
- [x] **Delete (Soft)**: Hapus mapel → status berubah `nonaktif` → tidak tampil di index

### A5. Manajemen Mengajar (CRUD) — **KRITIS**

- [x] **Index**: `/admin/mengajar` → tabel (Guru, Mapel, Kelas, Tahun Ajaran, Semester) + tombol "Kembali" + "Tambah Mengajar"
- [x] **Create**: 3 dropdown (Guru, Mapel, Kelas) semua menampilkan **nama**, bukan ID
- [x] **Store**: Buat kombinasi guru+mapel+kelas+tahun_ajaran+semester baru → success
- [x] **Validasi Duplikat**: Coba buat duplikat guru+mapel+kelas+tahun_ajaran+semester sama → error "Kombinasi guru, mata pelajaran, kelas, tahun ajaran, dan semester sudah ada."
- [x] **Edit**: Ubah semester → success
- [x] **Validasi Duplikat Edit**: Coba ubah jadi kombinasi yang sudah ada → error
- [x] **Delete**: Hapus → success

### A6. Manajemen Jadwal (CRUD) — **Validasi Bentrok**

- [x] **Index**: `/admin/jadwal` → tabel (Guru-Mapel-Kelas, Hari, Jam Mulai, Jam Selesai, Ruangan) + tombol "Kembali" + "Tambah Jadwal"
- [x] **Create**: Dropdown Mengajar menampilkan "Guru - Mapel - Kelas (Semester)"
- [x] **Store**: Isi hari, jam_mulai, jam_selesai, ruangan → success => setelah selesai dibuat, format jam yang awalnya cuman "07:30 AM/PM" malah berubah menjadi "07:30:00" dan ini menimbulkan masalah seperti "The jam mulai field must match the format H:i." saat mau edit waktu.
- [x] **Validasi Jam**: jam_selesai harus > jam_mulai (rule `after:jam_mulai`)
- [x] **Validasi Bentrok Guru**: Coba buat jadwal guru yang sama di hari & jam tumpang tindih → error "Guru sudah memiliki jadwal lain yang bentrok di hari dan jam ini." => bentrok tapi masih tetap berhasil ditambahkan
- [x] **Validasi Bentrok Ruangan**: Coba buat jadwal ruangan yang sama di hari & jam tumpang tindih → error "Ruangan sudah digunakan oleh jadwal lain yang bentrok di hari dan jam ini." => bentrok tapi masih tetap berhasil ditambahkan
- [] **Edit/Delete**: Berfungsi (validasi bentrok juga jalan di edit) => tidak bisa di cek karena masalah jam tadi

### A7. Visual Consistency Admin

- [x] Semua halaman pakai layout Pola 1 (index) / Pola 2 (form)
- [x] Warna konsisten: accent biru (#2563EB), surface abu muda
- [x] Tombol: Primary biru, Secondary outline, Danger merah
- [x] Tabel: hover row, header surface, border slate-200 => hover row ada tapi terlalu remah
- [x] Pagination tampil di index yang >15 data
- [x] Font: Inter di seluruh halaman
- [x] Tidak ada warna hex hardcoded di Blade (semua pakai class Tailwind accent/surface)

---

## TESTER B — GURU 1 (BUDI SANTOSO - MATEMATIKA)

### B1. Login & Dashboard Guru

- [x] Logout admin → login `budi@sekolah.test` / `password`
- [x] Redirect ke dashboard guru → 3 menu: Input Nilai, Input Absensi, Jadwal Mengajar
- [x] Nama: "Budi Santoso, S.Pd" · role "Guru"

### B2. Input Nilai — Alur Lengkap

- [x] **Index**: `/guru/nilai` → hanya 1 baris: "Matematika - X IPA 1 (ganjil)"
- [ ] **Form**: Klik "Input Nilai" → halaman form (Pola 2 varian tabel) => kena error ErrorException resources/views/guru/nilai/form.blade.php:42 Undefined variable $request. sisanya tidak bisa dites karena error ini
- [ ] **Dropdown Jenis**: Tugas / UTS / UAS
- [ ] **Tabel Siswa**: 10 siswa X IPA 1 tampil (nama + NIS + input nilai)
- [ ] **Input**: Isi nilai 4 siswa pertama: 85, 90, 78, 92 → Submit
- [ ] **Success**: Toast "Nilai berhasil disimpan" → redirect back ke form
- [ ] **Verifikasi DB**: Cek tabel `nilai` + `rekap_nilai` terisi 4 baris
- [ ] **Edit**: Ubah nilai siswa pertama jadi 88 → Submit → `rekap_nilai.rata_rata` berubah jadi 88
- [ ] **Validasi Rentang**: Input 150 → error "must not be greater than 100"
- [ ] **Validasi Rentang**: Input -10 → error "must be at least 0"
- [ ] **Cross-check**: (Manual DB) Coba insert nilai untuk siswa kelas lain → FK constraint tolak

### B3. Input Absensi — Alur Lengkap + **Cross-check Siswa-Kelas**

- [x] **Index**: `/guru/absensi` → list mengajar yang sama
- [x] **Form**: Klik "Input Absensi" → tanggal default hari ini + tabel 10 siswa + dropdown status => tidak ada pagination akan menjadi masalah nantinya. disini pakai data 36 siswa yang sebelumnya dipakai untuk mengetes pagination di admin
- [x] **Input**: Pilih Hadir/Izin/Sakit/Alpa untuk 4 siswa → Submit
- [x] **Success**: Toast → redirect back
- [x] **Verifikasi DB**: `absensi` + `rekap_absensi` terisi otomatis (trigger) => rekap_absensi tidak terisi otomatis
- [ ] **Rekap**: `total_hadir`, `total_izin`, `total_sakit`, `total_alpa`, `persentase_hadir` benar => tidak bisa di tes karena tidak terisi otomatis
- [ ] **Cross-check Keamanan**: (Manual/curl) Kirim request dengan `siswa_id` dari kelas lain → error "Ada siswa yang tidak sesuai kelas." (bukan disimpan) => nanti kamu lakukan sendiri

### B4. Jadwal Mengajar (Read-Only)

- [x] **Index**: `/guru/jadwal` → grouped by hari (Senin, Rabu untuk Matematika)
- [x] Kolom: Mapel, Kelas, Jam Mulai, Jam Selesai, Ruangan
- [x] **Tidak ada** tombol aksi (create/edit/delete)

### B5. Keamanan Guru

- [x] Coba akses `/guru/nilai/2` (mengajar ID 2 = Bahasa Indonesia milik Siti) → **403 Forbidden**
- [x] Coba akses `/admin/users` → **403 Forbidden**
- [ ] **Akun Nonaktif**: (Setup manual) Set `users.status = 'nonaktif'` untuk user guru → login → redirect logout + 403 "Akun Anda telah dinonaktifkan." => masih bisa login

### B-Note

- tidak ada tombol kembali di ketiga menu

---

## TESTER C — GURU 2 (SITI RAHAYU - BAHASA INDONESIA)

### C1. Login & Dashboard

- [x] Login `siti@sekolah.test` / `password`
- [x] Dashboard: 3 menu sama

### C2. Input Nilai (Bahasa Indonesia)

- [x] **Index**: Hanya "Bahasa Indonesia - X IPA 1 (ganjil)"
- [ ] **Form**: Input nilai 4 siswa: 80, 85, 90, 75 → Submit => error yang sama dengan budi jadi blok c2 gak bisa dites
- [ ] **Verifikasi**: `nilai` + `rekap_nilai` terisi untuk mapel B.Indonesia
- [ ] **Edit**: Ubah salah satu → rekap update

### C3. Input Absensi (Bahasa Indonesia)

- [x] **Form**: Tanggal hari ini + status beda dari Matematika
- [ ] **Verifikasi**: `rekap_absensi` terpisah per mengajar_id => gak masuk rekap_absensi
- [ ] **Cross-check**: Request dengan `siswa_id` kelas lain → error => ini apa?

### C3. Jadwal Mengajar

- [x] **Index**: Senin/Kamis untuk Bahasa Indonesia (ruang 102)

### C4. Cross-check Data Isolation

- [x] Pastikan Siti **tidak melihat** data Matematika di mana-mana
- [x] Pastikan nilai/absensi Siti tidak tumpang tindih dengan Budi

### c-Note

- tidak ada tombol kembali di ketiga menu

---

## TESTER D — SISWA (AHMAD FAUZI - X IPA 1)

### D1. Login & Dashboard Siswa

- [x] Login `siswa1001@sekolah.test` / `password`
- [x] Dashboard: 3 menu: Lihat Nilai, Lihat Absensi, Lihat Jadwal
- [x] Nama: "Ahmad Fauzi" · role "Siswa"

### D2. Lihat Nilai (Pola 4) => tidak bisa di tes semua karena role guru belum bisa input nilai

- [x] **Index**: `/siswa/nilai` → 2 card: Matematika & Bahasa Indonesia
- [ ] **Card Matematika**:
    - [ ] Judul: "Matematika"
    - [ ] **Rata-rata besar**: `text-2xl font-semibold text-accent` (misal 88.00)
    - [ ] Tabel: Jenis (Tugas/UTS/UAS) + Nilai
    - [ ] Tugas: 88.00, UTS: -, UAS: -
- [ ] **Card Bahasa Indonesia**:
    - [ ] Rata-rata: 0 atau "-" (belum diinput guru)
    - [ ] Semua nilai: -
- [x] **Read-only**: Tidak ada tombol edit/input

### D3. Lihat Absensi (Pola 4)

- [x] **Index**: `/siswa/absensi` → 2 card
- [x] **Card Matematika**:
    - [x] Persentase hadir besar: `text-2xl font-semibold text-accent` (misal 100%)
    - [x] Tabel: Tanggal + Badge status (Hadir=hijau, Izin=kuning, Sakit=biru, Alpa=merah)
    - [x] Ringkasan 4 kotak: Hadir/Izin/Sakit/Alpa dengan count
- [x] **Card Bahasa Indonesia**: Masih kosong (0%) => udah di isi di test siti jadi ada

### D4. Lihat Jadwal (Pola 1 Read-Only) =>

- [x] **Index**: `/siswa/jadwal` → grouped by hari
- [x] **Senin**: Matematika 07:00-08:30 Ruang 101 Guru Budi
- [x] **Selasa**: Bahasa Indonesia 07:00-08:30 Ruang 102 Guru Siti
- [x] **Rabu**: Matematika...
- [x] **Kamis**: Bahasa Indonesia...
- [x] **Tidak ada** tombol aksi

### D5. Keamanan Siswa

- [x] Coba akses `/guru/nilai` → **403**
- [x] Coba akses `/admin/users` → **403**
- [x] Coba akses `/siswa/nilai` manipulasi URL (misal id lain) → hanya data sendiri
- [ ] **Akun Nonaktif**: (Setup manual) Set `users.status = 'nonaktif'` untuk user siswa → login → redirect logout + 403 "Akun Anda telah dinonaktifkan." => masih bisa login

### D6. Multi-Siswa Check (opsional)

- [x] Login `siswa1002@sekolah.test` → data nilai/absensi beda (Bella Putri)
- [x] Login `siswa1010@sekolah.test` → data Joko Widodo

### d-Note

- tidak ada tombol kembali di ketiga menu

---

## TEST CASES LINTAS ROLE (Dikerjakan Bersama)

### X1. Trigger & Procedure Verification => gak ada instruksi gimanaa

- [ ] **Trigger Nilai Insert**: Input nilai baru → `rekap_nilai` auto-create + rata_rata hitung => menu nilai gak jalan
- [ ] **Trigger Nilai Update**: Edit nilai → `rekap_nilai.rata_rata` auto-update (trg_rekap_nilai_update)
- [ ] **Trigger Absensi Insert**: Input absensi → `rekap_absensi` auto-create via `sp_rekap_absensi` => gak jalan
- [ ] **Procedure sp_input_nilai_kelas**: ON DUPLICATE KEY UPDATE bekerja (edit = update, bukan insert baru)
- [ ] **Function fn_rata_rata_nilai**: Dipakai fallback saat rekap kosong
- [ ] **Function fn_persentase_hadir**: Dipakai fallback saat rekap_absensi kosong

### X2. Log Perubahan

- [ ] Edit nilai → cek tabel `log_perubahan` ada row baru (tabel='nilai', aksi='update', data_lama/baru JSON)

### X3. Unique Key Constraints

- [ ] Coba insert nilai duplikat (siswa_id + mengajar_id + jenis sama) → ON DUPLICATE KEY UPDATE jalan
- [ ] Coba insert absensi duplikat (siswa_id + mengajar_id + tanggal sama) → error unique key
- [ ] **Mengajar Unique**: Coba insert mengajar duplikat (guru+mapel+kelas+tahun_ajaran+semester) → error validasi

### X4. Responsive & UI

- [ ] Mobile (<768px): Dashboard grid 1 kolom, tabel scroll horizontal
- [ ] Desktop: Dashboard grid 3 kolom
- [ ] Font: Inter di seluruh halaman
- [ ] Tidak ada warna hex hardcoded di Blade (semua pakai class Tailwind accent/surface)
- [ ] Icon Tabler Icons tampil di dashboard admin menu cards

### X5. Edge Cases

- [ ] Guru tanpa mengajar → dashboard kosong (tidak error)
- [ ] Siswa tanpa kelas → dashboard nilai/absensi/jadwal kosong (tidak error)
- [ ] Semester genap (buat data baru) → trigger ambil semester dinamis dari mengajar
- [ ] User dinonaktifkan admin → sesi logout otomatis saat request berikutnya

---

## HASIL TESTING

| Tester     | Total Test Case | Pass | Fail | Blocker | Catatan |
| ---------- | --------------- | ---- | ---- | ------- | ------- |
| A (Admin)  | ~45             |      |      |         |         |
| B (Guru 1) | ~30             |      |      |         |         |
| C (Guru 2) | ~20             |      |      |         |         |
| D (Siswa)  | ~25             |      |      |         |         |
| **Total**  | **~120**        |      |      |         |         |

**Tanda Tangan Tester:**

- Tester A: \***\*\_\_\_\_** Tanggal: \***\*\_\_\_\_**
- Tester B: \***\*\_\_\_\_** Tanggal: \***\*\_\_\_\_**
- Tester C: \***\*\_\_\_\_** Tanggal: \***\*\_\_\_\_**
- Tester D: \***\*\_\_\_\_** Tanggal: \***\*\_\_\_\_**

**Disetujui Lead QA:** \***\*\_\_\_\_** Tanggal: \***\*\_\_\_\_**

---

## CATATAN UNTUK TESTER

1. **Jalankan test berurutan** sesuai nomor (A1→A2→A3...)
2. **Screenshot** setiap failure (error message, UI yang aneh)
3. **Isi kolom Catatan** dengan detail: langkah reproduksi, expected vs actual
4. **Jangan skip** validasi rentang nilai & cross-check siswa-kelas — ini bug critical
5. **Gunakan browser berbeda** (Chrome/Firefox) untuk test paralel
6. **Clear browser cache** sebelum mulai test
7. **Test soft delete**: Verifikasi di DB (`SELECT * FROM users WHERE status = 'nonaktif'`) setelah klik Hapus
8. **Test validasi bentrok jadwal**: Coba buat 2 jadwal guru sama di jam yang tumpang tindih (misal Senin 07:00-08:30 dan Senin 08:00-09:30)
