# Manual Testing Checklist - Sistem Akademik Sekolah V2

> **Project**: Sistem Akademik Sekolah V2 (Laravel 13 + Blade + MySQL + Breeze Auth)
> **Focus Modul**: Nilai & Absensi (dengan modul pendukung: Autentikasi, Dashboard, Otorisasi)
> **Tanggal**: 2025
> **Versi**: 1.0

---

## Panduan Penggunaan

| Kolom | Keterangan |
|-------|------------|
| **ID Test Case** | Format: MODUL-NNN (contoh: AUTH-001, NILAI-015) |
| **Prioritas** | High = Critical path / wajib lolos; Medium = Fitur utama; Low = Nice to have |
| **Precondition** | Kondisi yang harus terpenuhi sebelum eksekusi (data, login, state) |
| **Expected Result** | Hasil yang diharapkan (UI, database, redirect, flash message, HTTP status) |

**Akun Demo (dari Seeder)**:
| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@sekolah.test` | `password` |
| Guru | `budi@sekolah.test` | `password` |
| Guru | `siti@sekolah.test` | `password` |
| Siswa | `siswa1001@sekolah.test` | `password` |

---

## 1. MODUL AUTENTIKASI (AUTH)

### 1.1 Happy Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| AUTH-001 | Login sebagai Admin | Database seeded, server running | 1. Buka `/login`<br>2. Isi email: `admin@sekolah.test`<br>3. Isi password: `password`<br>4. Klik "Log in" | Redirect ke `/dashboard`, tampil dashboard Admin (stats guru/siswa/kelas/mapel) | High |
| AUTH-002 | Login sebagai Guru | Database seeded, server running | 1. Buka `/login`<br>2. Isi email: `budi@sekolah.test`<br>3. Isi password: `password`<br>4. Klik "Log in" | Redirect ke `/dashboard`, tampil dashboard Guru (kelas diampu, jadwal hari ini) | High |
| AUTH-003 | Login sebagai Siswa | Database seeded, server running | 1. Buka `/login`<br>2. Isi email: `siswa1001@sekolah.test`<br>3. Isi password: `password`<br>4. Klik "Log in" | Redirect ke `/dashboard`, tampil dashboard Siswa (nilai rata-rata, % kehadiran) | High |
| AUTH-004 | Remember Me functionality | Halaman login terbuka | 1. Isi kredensial valid<br>2. Centang "Remember me"<br>3. Login<br>4. Tutup browser<br>5. Buka ulang `/dashboard` | Masih ter-login (session persistent) | Medium |
| AUTH-005 | Logout | Sudah login sebagai user manapun | 1. Klik menu user / tombol logout<br>2. Konfirmasi logout | Redirect ke `/login`, session destroyed, tidak bisa akses `/dashboard` tanpa login | High |

### 1.2 Negative Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| AUTH-006 | Login dengan email tidak terdaftar | Halaman login terbuka | 1. Isi email: `tidakada@sekolah.test`<br>2. Isi password: `password`<br>3. Klik "Log in" | Tampil error: "These credentials do not match our records." (atau pesan validasi Laravel) | High |
| AUTH-007 | Login dengan password salah | Halaman login terbuka | 1. Isi email: `admin@sekolah.test`<br>2. Isi password: `salah`<br>3. Klik "Log in" | Tampil error: "These credentials do not match our records." | High |
| AUTH-008 | Login dengan email kosong | Halaman login terbuka | 1. Kosongkan email<br>2. Isi password: `password`<br>3. Klik "Log in" | Validasi HTML5: "Please fill out this field" / Error Laravel "The email field is required" | High |
| AUTH-009 | Login dengan password kosong | Halaman login terbuka | 1. Isi email: `admin@sekolah.test`<br>2. Kosongkan password<br>3. Klik "Log in" | Validasi HTML5 / Error Laravel "The password field is required" | High |
| AUTH-010 | Login dengan format email tidak valid | Halaman login terbuka | 1. Isi email: `bukan-email`<br>2. Isi password: `password`<br>3. Klik "Log in" | Validasi HTML5 type="email" / Error Laravel "The email must be a valid email address" | Medium |
| AUTH-011 | Akses `/dashboard` tanpa login | Belum login | 1. Buka `/dashboard` langsung via URL | Redirect ke `/login` dengan `intended` URL | High |
| AUTH-012 | Akses halaman protected (admin/guru/siswa) tanpa login | Belum login | 1. Buka `/admin/users`<br>2. Buka `/guru/nilai`<br>3. Buka `/siswa/nilai` | Semua redirect ke `/login` | High |

### 1.3 Edge Cases

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| AUTH-013 | CSRF Token validation | Halaman login terbuka | 1. Hapus `_token` dari form via DevTools<br>2. Submit login valid | Error 419 / "CSRF token mismatch" | Medium |
| AUTH-014 | Multiple login bersamaan (same user, browser berbeda) | Login di Browser A | 1. Login di Browser A<br>2. Login di Browser B dengan akun sama<br>3. Refresh Browser A | Keduanya tetap login (Laravel default: multiple sessions allowed) | Low |
| AUTH-015 | Session expired / idle timeout | Login, tunggu > `SESSION_LIFETIME` (120 menit) | 1. Login<br>2. Tunggu session expire<br>3. Akses `/dashboard` | Redirect ke `/login` | Low |
| AUTH-016 | Forgot Password flow | Halaman login terbuka | 1. Klik "Forgot your password?"<br>2. Isi email valid<br>3. Submit | Redirect ke halaman konfirmasi / email terkirim (jika mail configured) | Medium |
| AUTH-017 | Akun nonaktif (status = 'nonaktif') | User di DB diubah status ke 'nonaktif' | 1. Login dengan akun nonaktif | Logout otomatis, error "Akun Anda telah dinonaktifkan." (403) | High |

---

## 2. MODUL OTORISASI & ROLE-BASED ACCESS CONTROL (RBAC)

### 2.1 Happy Path - Akses Sesuai Role

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| RBAC-001 | Admin akses area Admin | Login sebagai Admin | 1. Akses `/admin/users`<br>2. Akses `/admin/kelas`<br>3. Akses `/admin/mapel`<br>4. Akses `/admin/mengajar`<br>4. Akses `/admin/jadwal`<br>5. Akses `/admin/berita`<br>6. Akses `/admin/tefa` | Semua halaman accessible (200 OK), tampil data & form CRUD | High |
| RBAC-002 | Guru akses area Guru | Login sebagai Guru | 1. Akses `/guru/nilai`<br>2. Akses `/guru/absensi`<br>3. Akses `/guru/jadwal` | Semua halaman accessible (200 OK), tampil data sesuai mengajar | High |
| RBAC-003 | Siswa akses area Siswa | Login sebagai Siswa | 1. Akses `/siswa/nilai`<br>2. Akses `/siswa/absensi`<br>3. Akses `/siswa/jadwal` | Semua halaman accessible (200 OK), tampil data read-only sesuai kelas | High |
| RBAC-004 | Public pages accessible tanpa login | Belum login | 1. Akses `/`<br>2. Akses `/tentang`<br>3. Akses `/program-keahlian`<br>4. Akses `/fasilitas`<br>5. Akses `/berita`<br>6. Akses `/produk-tefa`<br>7. Akses `/kontak` | Semua halaman accessible (200 OK) | High |

### 2.2 Negative Path - Akses Lintas Role (Harus Ditolak)

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| RBAC-005 | Guru mencoba akses `/admin/*` | Login sebagai Guru | 1. Akses `/admin/users`<br>2. Akses `/admin/kelas` | HTTP 403 "Tidak punya akses ke halaman ini." | High |
| RBAC-006 | Siswa mencoba akses `/admin/*` | Login sebagai Siswa | 1. Akses `/admin/users` | HTTP 403 | High |
| RBAC-007 | Siswa mencoba akses `/guru/*` | Login sebagai Siswa | 1. Akses `/guru/nilai`<br>2. Akses `/guru/absensi` | HTTP 403 | High |
| RBAC-008 | Admin mencoba akses `/guru/*` | Login sebagai Admin | 1. Akses `/guru/nilai` | HTTP 403 (Admin tidak punya role guru) | High |
| RBAC-009 | Admin mencoba akses `/siswa/*` | Login sebagai Admin | 1. Akses `/siswa/nilai` | HTTP 403 | High |
| RBAC-010 | Guru mencoba akses `/siswa/*` | Login sebagai Guru | 1. Akses `/siswa/nilai` | HTTP 403 | High |
| RBAC-011 | Guru mencoba akses kelas yang tidak diajar | Login sebagai Guru, ada data mengajar lain | 1. Akses `/guru/nilai/{mengajar_id_lain}`<br>2. Akses `/guru/absensi/{mengajar_id_lain}` | HTTP 403 "Anda tidak mengajar kelas ini." | High |

---

## 3. MODUL NILAI (GURU) - `/guru/nilai*`

### 3.1 Happy Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| NILAI-001 | Tampil daftar kelas yang diajar | Login Guru, punya data mengajar | 1. Akses `/guru/nilai` | Tampil tabel: Mapel, Kelas, Semester, TA, tombol "Input Nilai" per baris | High |
| NILAI-002 | Buka form input nilai TUGAS | Login Guru, punya mengajar | 1. Klik "Input Nilai" pada baris kelas<br>2. Default tab "Nilai Tugas" aktif | Tampil form: header info kelas/mapel, tab Tugas/UTS/UAS, tabel siswa dengan kolom input nilai | High |
| NILAI-003 | Input nilai TUGAS valid (0-100) | Form nilai TUGAS terbuka | 1. Isi nilai beberapa siswa (contoh: 85, 90, 75.5)<br>2. Klik "Simpan Semua Nilai TUGAS" | Flash success "Nilai TUGAS berhasil disimpan.", nilai tersimpan, rata-rata terupdate | High |
| NILAI-004 | Input nilai UTS valid | Form nilai TUGAS terbuka | 1. Klik tab "Nilai UTS"<br>2. Isi nilai beberapa siswa<br>3. Klik "Simpan Semua Nilai UTS" | Flash success "Nilai UTS berhasil disimpan.", switch tab ke UTS, data UTS terisi | High |
| NILAI-005 | Input nilai UAS valid | Form nilai TUGAS terbuka | 1. Klik tab "Nilai UAS"<br>2. Isi nilai beberapa siswa<br>3. Klik "Simpan Semua Nilai UAS" | Flash success "Nilai UAS berhasil disimpan." | High |
| NILAI-006 | Switch tab Tugas/UTS/UAS menjaga state | Form nilai TUGAS terbuka, sudah isi nilai | 1. Isi nilai Tugas siswa A = 80<br>2. Klik tab UTS<br>3. Klik kembali tab Tugas | Nilai Tugas siswa A masih 80 (persist via old() / DB) | High |
| NILAI-007 | Reset nilai per siswa | Sudah ada nilai tersimpan | 1. Klik tombol "Reset" pada baris siswa<br>2. Konfirmasi dialog | Flash success "Nilai TUGAS berhasil direset.", input kosong, rata-rata terupdate | High |
| NILAI-008 | Input parsial (beberapa siswa dikosongkan) | Form nilai terbuka | 1. Isi nilai hanya 2 dari 10 siswa<br>2. Simpan | Flash success, 2 siswa tersimpan, 8 siswa tidak berubah | High |
| NILAI-009 | Navigasi breadcrumb & tombol kembali | Form nilai terbuka | 1. Klik "Selesai & Kembali" atau breadcrumb "Penilaian" | Redirect ke `/guru/nilai` (index) | Medium |

### 3.2 Negative Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| NILAI-010 | Submit form tanpa isi nilai sama sekali | Form nilai terbuka | 1. Tidak isi input apapun<br>2. Klik "Simpan" | Error: "Belum ada nilai yang diisi. Masukkan angka 0–100 lalu klik Simpan." | High |
| NILAI-011 | Input nilai > 100 | Form nilai terbuka | 1. Isi input: 150<br>2. Simpan | Validasi HTML5 max=100 / Error Laravel "The nilai.0 must not be greater than 100" | High |
| NILAI-012 | Input nilai < 0 | Form nilai terbuka | 1. Isi input: -10<br>2. Simpan | Validasi HTML5 min=0 / Error Laravel "The nilai.0 must be at least 0" | High |
| NILAI-013 | Input nilai non-numeric (karakter) | Form nilai terbuka | 1. Isi input: "abc"<br>2. Simpan | Validasi HTML5 type="number" / Error Laravel numeric | High |
| NILAI-014 | Input nilai desimal > 2 digit (step=0.01) | Form nilai terbuka | 1. Isi input: 85.123<br>2. Simpan | Dibulatkan / validasi step=0.01 | Low |
| NILAI-015 | Jenis nilai tidak valid (manipulasi hidden input) | Form nilai terbuka | 1. Ubah hidden input `jenis` jadi "invalid"<br>2. Simpan | Error validasi: "The selected jenis is invalid" (in:tugas,uts,uas) | High |
| NILAI-016 | Cross-check siswa-kelas gagal (manipulasi siswa_id) | Form nilai terbuka | 1. Ubah name input jadi `nilai[999]` (siswa beda kelas)<br>2. Simpan | Error: "Ada siswa yang tidak sesuai kelas." | High |

### 3.3 Edge Cases

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| NILAI-017 | Keyboard navigation (Enter → next input) | Form nilai terbuka | 1. Fokus input siswa 1<br>2. Tekan Enter | Fokus pindah ke input siswa 2, select all | Low |
| NILAI-018 | Double submit (klik cepat 2x tombol simpan) | Form nilai terbuka | 1. Klik "Simpan" 2x cepat | Hanya 1 request diproses (tidak duplicate) | Medium |
| NILAI-019 | Reset nilai lalu input ulang | Sudah ada nilai, lalu direset | 1. Reset nilai siswa A<br>2. Input nilai baru 90<br>3. Simpan | Nilai baru 90 tersimpan, rata-rata terhitung ulang | Medium |
| NILAI-020 | Data siswa 0 (kelas kosong) | Admin buat mengajar tapi kelas tdk punya siswa | 1. Akses form nilai kelas tsb | Tampil tabel kosong, tombol simpan tetap ada, validasi "Belum ada nilai yang diisi" | Low |
| NILAI-021 | Concurrent edit (2 guru edit sama kelas) | Tidak mungkin (1 mengajar = 1 guru), tapi test race condition | 1. Simulasi 2 request simpan bersamaan | Transaction DB: salah satu success, satunya rollback / queue | Low |

---

## 4. MODUL ABSENSI (GURU) - `/guru/absensi*`

### 4.1 Happy Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| ABS-001 | Tampil daftar kelas untuk absensi | Login Guru, punya data mengajar | 1. Akses `/guru/absensi` | Tampil tabel: Mapel, Kelas, Semester/TA, tombol "Input Hari Ini" & "Riwayat" | High |
| ABS-002 | Input absensi hari ini (mode baru) | Login Guru, punya mengajar | 1. Klik "Input Hari Ini"<br>2. Default tanggal = today<br>3. Pilih status per siswa (Hadir/Izin/Sakit/Alpa)<br>4. Klik "Simpan Data Presensi" | Flash success "Presensi tanggal DD/MM/YYYY berhasil disimpan.", redirect ke Riwayat | High |
| ABS-003 | Quick-set "Semua Hadir" | Form absensi terbuka | 1. Klik tombol "Semua Hadir"<br>2. Simpan | Semua dropdown jadi "Hadir", flash success | High |
| ABS-004 | Quick-set "Semua Izin" | Form absensi terbuka | 1. Klik tombol "Semua Izin"<br>2. Simpan | Semua dropdown jadi "Izin" | Medium |
| ABS-005 | Ganti tanggal absensi (load data existing) | Sudah ada absensi tanggal kemarin | 1. Buka form absensi<br>2. Ganti tanggal ke kemarin (datepicker)<br>3. Halaman reload | Tampil mode "Edit / Koreksi", data existing terisi, badge amber | High |
| ABS-006 | Edit absensi existing (koreksi) | Mode edit (tanggal sudah ada data) | 1. Ubah status siswa A: Hadir → Sakit<br>2. Simpan | Flash success, redirect ke Riwayat, data terupdate | High |
| ABS-007 | Lihat riwayat absensi | Sudah ada data absensi | 1. Klik "Riwayat" di index<br>2. Atau dari form klik "Lihat Riwayat" | Tampil tabel riwayat: Tanggal, Hadir, Izin, Sakit, Alpa, Total, paginasi 15 per halaman | High |
| ABS-008 | Hapus absensi per tanggal | Sudah ada data absensi | 1. Di Riwayat, klik hapus pada tanggal<br>2. Konfirmasi | Flash success "Data presensi tanggal ... berhasil dihapus.", data hilang dari riwayat | High |
| ABS-009 | Validasi status wajib dipilih semua siswa | Form absensi terbuka | 1. Pilih status hanya untuk 1 siswa, sisanya biarkan default<br>2. Simpan | Error validasi: "The status.0 field is required" (semua siswa wajib) | High |

### 4.2 Negative Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| ABS-010 | Tanggal di masa depan | Form absensi terbuka | 1. Pilih tanggal besok / minggu depan<br>2. Simpan | Validasi HTML5 max=today / Error Laravel date validation | Medium |
| ABS-011 | Tanggal format tidak valid | Form absensi terbuka | 1. Manipulasi input tanggal jadi string invalid<br>2. Simpan | Error validasi date | Low |
| ABS-012 | Status tidak valid (manipulasi option value) | Form absensi terbuka | 1. Ubah value option jadi "invalid_status"<br>2. Simpan | Error: "The selected status is invalid" (in:hadir,izin,sakit,alpa) | High |
| ABS-013 | Cross-check siswa-kelas gagal (manipulasi siswa_id) | Form absensi terbuka | 1. Ubah name select jadi `status[999]`<br>2. Simpan | Error: "Ada siswa yang tidak sesuai kelas." | High |
| ABS-014 | Submit tanpa pilih status sama sekali | Form absensi baru | 1. Biarkan semua default "Hadir" (required tapi ada default)<br>2. Simpan | **Note**: Default "Hadir" memenuhi required → success. Test case ini validasi jika required tdk terpenuhi. | Medium |

### 4.3 Edge Cases

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| ABS-015 | Edit absensi → ganti tanggal → simpan | Mode edit tanggal T1 | 1. Ganti tanggal ke T2 via datepicker<br>2. Halaman reload ke form T2<br>3. Simpan | Data T1 tidak terhapus, data T2 tersimpan (create new) | Medium |
| ABS-016 | Hapus absensi → cek rekap absensi siswa | Sudah ada absensi & rekap | 1. Hapus absensi tanggal T<br>2. Cek `/siswa/absensi` atau DB `rekap_absensi` | Persentase hadir & counts terupdate (sp_rekap_absensi dipanggil manual di destroyDate) | High |
| ABS-017 | Absensi di hari Minggu (libur) | Form absensi, pilih tanggal Minggu | 1. Pilih tanggal Minggu<br>2. Simpan | Bisa disimpan (tidak ada validasi hari kerja), tapi UI datepicker boleh disable Minggu | Low |
| ABS-018 | Concurrent absensi (2 device guru sama) | - | 1. Simulasi 2 request simpan absensi sama waktu | Transaction DB handle race condition | Low |
| ABS-019 | Kelas tanpa siswa | Admin buat mengajar kelas kosong | 1. Buka form absensi | Tampil tabel kosong, tombol simpan, validasi status required tetap jalan | Low |

---

## 5. MODUL NILAI (SISWA) - `/siswa/nilai` (Read-Only)

### 5.1 Happy Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| SISNIL-001 | Tampil daftar nilai per mapel | Login Siswa, punya kelas & mengajar | 1. Akses `/siswa/nilai` | Tampil card per mapel: judul mapel, rata-rata besar, tabel Tugas/UTS/UAS | High |
| SISNIL-002 | Tampil nilai Tugas, UTS, UAS per mapel | Sudah ada data nilai di DB | 1. Cek baris Tugas, UTS, UAS per card | Nilai tampil sesuai DB, "-" jika belum ada | High |
| SISNIL-003 | Tampil rata-rata per mapel | Sudah ada nilai | 1. Cek angka besar di header card | Rata-rata = fn_rata_rata_nilai(siswa_id, mengajar_id), format 2 desimal | High |
| SISNIL-004 | Tampil nilai akhir & predikat (jika ada) | RekapNilai punya nilai_akhir & predikat | 1. Cek footer card | Tampil "Nilai Akhir: X" dan "Predikat: Y" | Medium |
| SISNIL-005 | Navigasi kembali ke dashboard | Halaman nilai terbuka | 1. Klik tombol "Kembali" | Redirect ke `/dashboard` | Low |

### 5.2 Negative / Edge Cases

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| SISNIL-006 | Siswa tanpa kelas (kelas_id = null) | Siswa di DB tdk punya kelas_id | 1. Login siswa tsb<br>2. Akses `/siswa/nilai` | Tampil "Belum ada data nilai." (empty state) | Medium |
| SISNIL-007 | Mapel tanpa nilai sama sekali | Mengajar ada tapi nilai tdk diisi guru | 1. Cek card mapel tsb | Rata-rata 0.00, semua nilai "-", tidak error | High |
| SISNIL-008 | Akses `/siswa/nilai` sebagai Guru/Admin | Login Guru/Admin | 1. Akses `/siswa/nilai` | HTTP 403 | High |

---

## 6. MODUL ABSENSI (SISWA) - `/siswa/absensi` (Read-Only)

### 6.1 Happy Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| SISAB-001 | Tampil daftar absensi per mapel | Login Siswa, punya data absensi | 1. Akses `/siswa/absensi` | Card per mapel: % kehadiran besar, tabel Tanggal + Status badge | High |
| SISAB-002 | Tampil detail absensi per tanggal | Sudah ada data absensi | 1. Cek tabel per mapel | Baris per tanggal: format DD/MM/YYYY, badge status (Hadir=green, Izin=amber, Sakit=blue, Alpa=red) | High |
| SISAB-003 | Tampil ringkasan counts (Hadir/Izin/Sakit/Alpa) | Sudah ada data absensi | 1. Cek grid 4 kolom di bawah tabel | Angka sesuai count di DB per status | High |
| SISAB-004 | Tampil persentase kehadiran | Sudah ada data absensi | 1. Cek angka besar di header card | Persentase = fn_persentase_hadir(siswa_id, mengajar_id), format 2 desimal + % | High |
| SISAB-005 | Mapel tanpa absensi | Mengajar ada tapi absensi tdk diisi | 1. Cek card mapel tsb | Tampil "Belum ada data absensi", persentase 0.00%, counts semua 0 | Medium |

### 6.2 Negative / Edge Cases

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| SISAB-006 | Siswa tanpa kelas | Siswa tdk punya kelas_id | 1. Akses `/siswa/absensi` | Tampil "Belum ada data absensi." | Medium |
| SISAB-007 | Akses `/siswa/absensi` sebagai Guru/Admin | Login Guru/Admin | 1. Akses `/siswa/absensi` | HTTP 403 | High |

---

## 7. MODUL DASHBOARD

### 7.1 Happy Path

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| DASH-001 | Dashboard Admin | Login Admin | 1. Login → redirect `/dashboard` | Tampil stats: Total Guru, Siswa, Kelas, Mapel, Produk TEFA, Berita + Log perubahan + Berita terbaru | High |
| DASH-002 | Dashboard Guru | Login Guru | 1. Login → redirect `/dashboard` | Tampil stats: Kelas Diampu, Mapel Diampu, Jumlah Siswa + Jadwal Hari Ini + Daftar Kelas Diampu | High |
| DASH-003 | Dashboard Siswa | Login Siswa | 1. Login → redirect `/dashboard` | Tampil stats: Jumlah Mapel, Rata-rata Nilai, % Kehadiran | High |
| DASH-004 | Navigasi sidebar per role | Login masing-masing role | 1. Cek sidebar menu | Admin: User, Kelas, Mapel, Mengajar, Jadwal, Berita, TEFA<br>Guru: Penilaian, Presensi, Jadwal<br>Siswa: Nilai Saya, Absensi Saya, Jadwal Saya | High |
| DASH-005 | Jadwal hari ini (Guru) | Hari kerja, guru punya jadwal hari ini | 1. Login Guru hari Senin-Jumat | Tampil jadwal hari ini di dashboard (jam, mapel, kelas) | Medium |

---

## 8. MODUL ADMIN - MANAJEMEN DATA MASTER (Smoke Test)

> **Catatan**: Hanya smoke test untuk memastikan CRUD accessible. Detail testing per entity (User, Kelas, Mapel, Mengajar, Jadwal, Berita, TEFA) di dokumen terpisah.

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| ADM-001 | CRUD User | Login Admin | 1. Akses `/admin/users`<br>2. Create, Edit, Delete user | Semua operasi CRUD berfungsi, role & status tersimpan | High |
| ADM-002 | CRUD Kelas | Login Admin | 1. Akses `/admin/kelas` | CRUD berfungsi, wali kelas assignable | High |
| ADM-003 | CRUD Mapel | Login Admin | 1. Akses `/admin/mapel` | CRUD berfungsi | Medium |
| ADM-004 | CRUD Mengajar (Guru+Mapel+Kelas) | Login Admin | 1. Akses `/admin/mengajar` | CRUD berfungsi, relasi 3 entitas valid | High |
| ADM-005 | CRUD Jadwal | Login Admin | 1. Akses `/admin/jadwal` | CRUD berfungsi, hari/jam/ruangan valid | High |
| ADM-006 | CRUD Berita | Login Admin | 1. Akses `/admin/berita` | CRUD berfungsi, slug auto-generate | Medium |
| ADM-007 | CRUD TEFA Produk | Login Admin | 1. Akses `/admin/tefa` | CRUD berfungsi, upload foto, jurusan | Medium |

---

## 9. HALAMAN PUBLIK (Public Pages)

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| PUB-001 | Homepage | - | 1. Akses `/` | Tampil hero, berita terbaru, produk TEFA | Medium |
| PUB-002 | Profil Sekolah | - | 1. Akses `/tentang` | Tampil sejarah, visi misi, identitas, pimpinan | Low |
| PUB-003 | Program Keahlian | - | 1. Akses `/program-keahlian`<br>2. Klik detail jurusan | List 9 kompetensi, detail per jurusan | Low |
| PUB-004 | Fasilitas | - | 1. Akses `/fasilitas` | Tampil fasilitas utama, PKL, ekstrakurikuler | Low |
| PUB-005 | Berita (List & Detail) | - | 1. Akses `/berita`<br>2. Klik "Baca" pada berita | List berita + pagination, detail berita full | Low |
| PUB-006 | Katalog TEFA | - | 1. Akses `/produk-tefa`<br>2. Filter jurusan<br>3. Klik "Pesan via WhatsApp" | Grid produk, filter jurusan, link WA valid | Medium |
| PUB-007 | Kontak | - | 1. Akses `/kontak` | Tampil alamat, telepon, email, map, form kontak | Low |

---

## 10. DATABASE INTEGRITY & STORED PROCEDURES

| ID | Deskripsi Skenario | Precondition | Langkah Pengujian | Expected Result | Prioritas |
|----|-------------------|--------------|-------------------|-----------------|-----------|
| DB-001 | Trigger `trg_rekap_nilai_insert` | Input nilai via Guru | 1. Input nilai baru<br>2. Cek tabel `rekap_nilai` | Row baru/terupdate dengan `rata_rata` = `fn_rata_rata_nilai` | High |
| DB-002 | Trigger `trg_rekap_nilai_update` | Update nilai via Guru | 1. Edit nilai existing<br>2. Cek `rekap_nilai` | `rata_rata` terupdate otomatis | High |
| DB-003 | Trigger `trg_log_nilai_update` | Update/hapus nilai | 1. Edit/hapus nilai<br>2. Cek tabel `log_perubahan` | Row baru: user_id, mengajar_id, siswa_id, jenis, nilai_lama, nilai_baru, waktu | Medium |
| DB-004 | Trigger `trg_absensi_insert` | Input absensi via Guru | 1. Input absensi baru<br>2. Cek `rekap_absensi` | Row baru/terupdate `persentase_hadir` = `fn_persentase_hadir` | High |
| DB-005 | Procedure `sp_input_nilai_kelas` idempoten | Panggil 2x dengan param sama | 1. CALL sp_input_nilai_kelas(...) 2x | Tidak duplicate, nilai terupdate | Medium |
| DB-006 | Procedure `sp_rekap_absensi` idempoten | Panggil 2x dengan param sama | 1. CALL sp_rekap_absensi(...) 2x | Tidak duplicate, persentase terupdate | Medium |
| DB-007 | Function `fn_rata_rata_nilai` | Data nilai: Tugas=80, UTS=90, UAS=100 | 1. SELECT fn_rata_rata_nilai(siswa_id, mengajar_id) | Return 90.00 (rata-rata 3 jenis) | Medium |
| DB-008 | Function `fn_persentase_hadir` | Data absensi: 8 Hadir, 2 Izin, 0 Sakit, 0 Alpa (total 10) | 1. SELECT fn_persentase_hadir(siswa_id, mengajar_id) | Return 80.00 | Medium |

---

## 11. CATATAN MODUL LAIN YANG TERDETEKSI TAPI BELUM DIDOKUMENTASIKAN DETAIL

Berikut modul/modul yang terdeteksi di kode (routes, controllers, views) namun **belum** dibuatkan test case detail di checklist ini. Disarankan dibuatkan checklist terpisah per modul:

| Modul | Controller | Route Prefix | Fitur Utama | Catatan |
|-------|------------|--------------|-------------|---------|
| **Jadwal (Guru)** | `Guru\JadwalController` | `/guru/jadwal` | Lihat jadwal mengajar per hari | Read-only, filter hari |
| **Jadwal (Siswa)** | `Siswa\JadwalController` | `/siswa/jadwal` | Lihat jadwal kelas per hari | Read-only |
| **Jadwal (Admin)** | `Admin\JadwalController` | `/admin/jadwal` | CRUD jadwal (hari, jam, ruangan) | Relasi ke mengajar |
| **Mengajar (Admin)** | `Admin\MengajarController` | `/admin/mengajar` | CRUD relasi Guru+Mapel+Kelas | Validasi unique Guru+Mapel+Kelas+Semester+TA |
| **User Management (Admin)** | `Admin\UserController` | `/admin/users` | CRUD user + role + status | Auto-create Guru/Siswa record saat role dipilih |
| **Berita (Admin)** | `Admin\BeritaController` | `/admin/berita` | CRUD berita + slug + gambar | Public di `/berita` |
| **TEFA Produk (Admin)** | `Admin\TefaProductController` | `/admin/tefa` | CRUD produk + foto + jurusan + WA | Public di `/produk-tefa` |
| **Profil User** | `ProfileController` | `/profile` | Edit profil, ganti password, hapus akun | Semua role |
| **Email Verification** | Auth controllers | `/email/verify/*` | Verifikasi email (Breeze default) | Perlu konfigurasi mail |
| **Password Reset** | Auth controllers | `/forgot-password`, `/reset-password` | Lupa password flow | Perlu konfigurasi mail |

---

## Ringkasan Prioritas

| Prioritas | Jumlah Test Case | Target |
|-----------|------------------|--------|
| **High** | ~55 | Wajib lolos sebelum release |
| **Medium** | ~25 | Target lolos sprint |
| **Low** | ~15 | Nice to have / technical debt |

**Total Test Case**: ~95 (fokus Nilai & Absensi + Auth + RBAC + Dashboard + Admin Smoke + Public + DB)

---

## Instruksi Eksekusi

1. **Persiapan**: `php artisan migrate:fresh --seed` → jalankan server `php artisan serve`
2. **Gunakan akun demo** sesuai tabel di atas
3. **Catat hasil** per test case: ✅ Pass / ❌ Fail / ⚠️ Partial / 🚫 Blocked
4. **Dokumentasikan bug** dengan format: `ID_TEST_CASE - Deskripsi Bug - Steps to Reproduce - Expected vs Actual - Severity`
5. **Retest** setelah fix bug

---

*Dokumen ini di-generate berdasarkan analisis kode sumber Laravel 13 (Controller, Routes, Views, Models, Middleware, Database Schema, Stored Procedures, Triggers).*