# Fitur Nilai Raport Mandiri Siswa

Dokumentasi ini menjelaskan implementasi fitur Nilai Raport Mandiri yang dibuat khusus untuk siswa agar bisa memantau rata-rata nilai secara mandiri tanpa mengubah alur nilai resmi guru.

## 1. Arsitektur Data
Fitur ini menggunakan tabel terpisah (`nilai_mandiri_siswa`) untuk memastikan isolasi total dari sistem nilai utama.

- **Tabel:** `nilai_mandiri_siswa`
- **Kolom:** `id`, `siswa_id` (FK), `nama_mapel`, `nilai` (1 nilai akhir per mapel), `semester` (string: '1'-'5'), `timestamps`.
- **Constraint:** Unique key `(siswa_id, nama_mapel, semester)` mencegah duplikasi input untuk mata pelajaran yang sama per semester.

## 2. Perubahan pada Sistem
- **Model Baru:** `App\Models\NilaiMandiriSiswa` (dengan method statis untuk kalkulasi rata-rata per semester dan keseluruhan).
- **Controller Baru:** `App\Http\Controllers\Siswa\NilaiMandiriController` (mengatur CRUD mandiri + batch input).
- **Routes Baru:** Didaftarkan dalam middleware `role:siswa` di `routes/web.php` (`siswa.nilai-mandiri.*`).
- **Views Baru:** 
  - `resources/views/siswa/nilai-mandiri/index.blade.php` — tampilan daftar dengan kalkulasi rata-rata per semester dan keseluruhan
  - `resources/views/siswa/nilai-mandiri/create.blade.php` — form input multiple dengan tombol tambah kolom
  - `resources/views/siswa/nilai-mandiri/edit.blade.php` — edit single nilai

## 3. Fitur Utama

### 3.1 Input Dinamis (Create Form)
- **Tombol "+ Tambah Mapel":** Menambah kolom input baru tanpa perlu reload halaman
- **Tombol "Hapus":** Menghapus baris input yang tidak diinginkan
- **Input Per Semester:** Siswa pilih semester (1-5) lalu input multiple mapel sekaligus
- **Batch Save:** Semua input disimpan dalam 1 kali submit dengan transaction

### 3.2 Kalkulasi Otomatis
- **Rata-rata Per Semester:** `RataRataSemester::getRataRataSemester($siswaId, $semester)` → menghitung rata-rata nilai di semester tertentu
- **Rata-rata Keseluruhan:** `RataRataSemester::getRataRataKeseluruhan($siswaId)` → menghitung rata-rata dari semua nilai (5 semester)
- **Predikat Otomatis:** Ditampilkan berdasarkan rata-rata keseluruhan (Sangat Baik: ≥85, Baik: ≥75, Cukup: ≥65, Kurang: <65)

### 3.3 Tampilan Index
- **Grouping Per Semester:** Data ditampilkan per semester dengan header semester dan badge rata-rata
- **Tabel Per Semester:** Menampilkan mapel + nilai per baris
- **Card Rata-rata Keseluruhan:** Gradient card di bawah menampilkan rata-rata keseluruhan dengan predikat

## 4. Alur Penggunaan

| Tahap | Aksi | Output |
| :--- | :--- | :--- |
| 1. Login | Siswa masuk ke dashboard | Dashboard dengan menu "Nilai Raport Mandiri" |
| 2. Akses | Klik menu "Nilai Raport Mandiri" | Halaman index (list) dengan tombol "Tambah Nilai" |
| 3. Pilih Semester | Pilih semester (1-5) di dropdown | Form siap input mapel & nilai |
| 4. Input Mapel | Masukkan nama mapel & nilai, klik "+ Tambah Mapel" jika ada lebih dari 1 | Multiple row input tersedia |
| 5. Hapus Baris | Jika ada kesalahan, klik tombol "Hapus" pada baris itu | Baris hilang dari form (belum submit) |
| 6. Submit | Klik "Simpan Semua" | Data masuk ke `nilai_mandiri_siswa` per mapel, kalkulasi rata-rata otomatis |
| 7. Lihat Hasil | Di halaman index, lihat tabel per semester | Tiap semester menampilkan: mapel, nilai, rata-rata semester, dan di bawah ada rata-rata keseluruhan + predikat |
| 8. Edit | Klik tombol "Edit" di row manapun | Form edit single nilai (tidak batch) |
| 9. Delete | Klik tombol "Hapus" di row | Nilai terhapus, kalkulasi rata-rata update otomatis |

## 5. Keamanan & Stabilitas
- **Isolasi Penuh:** Tidak menggunakan trigger/procedure database guru, sehingga jika sistem guru di-update, fitur ini tetap stabil.
- **Validasi Cross-check:** Setiap method controller validasi `Auth::id()` dan `siswa_id` untuk mencegah siswa melihat/mengubah data milik siswa lain.
- **Transaction Batch:** Method `store()` menggunakan `DB::beginTransaction()` untuk memastikan semua mapel dalam 1 semester disimpan secara atomik.
- **Unique Constraint DB:** Tabel punya constraint `UNIQUE (siswa_id, nama_mapel, semester)` sehingga update otomatis jika input mapel yang sama semester yang sama.
- **Konsistensi Design:** Mengikuti Design System (Warna `accent`, komponen `x-card`, `x-table`, `x-button`, `x-form-*`) sesuai dokumentasi `docs/04-design-system.md`.

## 6. Method Model

### `getRataRataSemester($siswaId, $semester): ?float`
Hitung rata-rata nilai untuk siswa tertentu di semester tertentu.
```php
$rata = NilaiMandiriSiswa::getRataRataSemester(1, '1'); // Siswa 1, Semester 1
// Output: 82.00
```

### `getRataRataKeseluruhan($siswaId): ?float`
Hitung rata-rata nilai keseluruhan (semua semester).
```php
$rata = NilaiMandiriSiswa::getRataRataKeseluruhan(1);
// Output: 85.5
```

### `getStatusSemester($siswaId, $semester): bool`
Cek apakah siswa sudah punya nilai di semester tertentu.
```php
$ada = NilaiMandiriSiswa::getStatusSemester(1, '1');
// Output: true/false
```

## 7. Struktur Request/Response

### POST `/siswa/nilai-mandiri` (store)
**Request Body (form array):**
```
semester: "1"
nama_mapel[]: ["Matematika", "Bahasa Indonesia", "IPA"]
nilai[]: [85.5, 78.0, 82.5]
```

**Response:**
- Success: Redirect ke index dengan flash message "Nilai raport berhasil disimpan."
- Error: Back dengan error message

### PUT `/siswa/nilai-mandiri/{id}` (update)
**Request Body:**
```
nama_mapel: "Matematika"
semester: "1"
nilai: 90.0
```

### DELETE `/siswa/nilai-mandiri/{id}` (destroy)
Direct delete dengan confirmation dialog

## 8. Testing

Test data sudah tersimpan dengan 8 baris:
- Semester 1: Matematika (85.5), Bahasa Indonesia (78.0), IPA (82.5) → Rata-rata: 82.0
- Semester 2: Matematika (88.0), Bahasa Indonesia (80.5) → Rata-rata: 84.25
- Semester 3: Fisika (90.0), Kimia (87.5), Biologi (92.0) → Rata-rata: 89.83
- **Rata-rata Keseluruhan: 85.5** (Predikat: Sangat Baik)

Verifikasi:
✅ Multiple input (batch) berfungsi  
✅ Kalkulasi rata-rata per semester akurat  
✅ Kalkulasi rata-rata keseluruhan akurat  
✅ Update data berfungsi  
✅ Delete data berfungsi  
✅ Tidak ada kontaminasi ke Guru/Admin controller  
✅ Syntax PHP clean (no errors)  
✅ Views Blade valid

