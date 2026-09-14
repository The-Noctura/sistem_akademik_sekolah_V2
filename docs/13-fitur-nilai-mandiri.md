# Laporan Lengkap: Fitur Nilai Raport Mandiri & Distribusi Akreditasi Siswa

Dokumentasi ini menyajikan laporan menyeluruh mengenai pengembangan, perbaikan bug, dan penambahan fitur visualisasi diagram bulat (donut chart) pada modul nilai raport mandiri siswa.

---

## 1. Ringkasan Eksekutif

Modul **Nilai Raport Mandiri** dirancang khusus untuk siswa agar dapat menginput, mengelola, dan memantau nilai rata-rata per semester serta rata-rata keseluruhan secara mandiri, tanpa mengganggu atau bergantung pada struktur nilai resmi dari guru (`mengajar`, `nilai`, dll).

Pada pembaruan terakhir, telah dilakukan:
1. **Perbaikan Bug Update (Critical Fix):** Menambahkan `DB::beginTransaction()` dan validasi duplikasi mata pelajaran per semester pada method `update()` di `NilaiMandiriController`.
2. **Penyempurnaan Tampilan Semester:** Setiap semester sekarang dipisahkan dengan card yang jelas (`Semester 1`, `Semester 2`, dst.) lengkap dengan badge rata-rata nilai semester dan keterangan statusnya.
3. **Fitur Visualisasi Donut Chart Bulat:** Mengganti diagram progress bar linier dengan grafik SVG Donut Chart bulat untuk presentasi akreditasi/distribusi grade nilai (A+, A, B, C, D) secara proporsional.

---

## 2. Arsitektur & Struktur Data

### Tabel Database (`nilai_mandiri_siswa`)
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | Primary Key |
| `siswa_id` | bigint (FK) | Relasi ke tabel `siswa` |
| `nama_mapel` | string(100) | Nama mata pelajaran |
| `semester` | enum('1','2','3','4','5') | Semester pelajaran |
| `nilai` | decimal(5,2) | Nilai raport (0-100) |
| `created_at, updated_at` | timestamp | Timestamp standar Laravel |

**Unique Constraint:** `(siswa_id, nama_mapel, semester)` — mencegah duplikasi input mata pelajaran yang sama di semester yang sama.

---

## 3. Komponen yang Dibangun & Diubah

### A. Controller: `App\Http\Controllers\Siswa\NilaiMandiriController`
- **`index()`:** Mengelompokkan nilai berdasarkan semester (`$dataBySemester`) dan mengirim data siswa.
- **`create()` & `store()`:** Menangani form input dinamis (batch insert multiple mapel) dengan transaction `DB::beginTransaction()`.
- **`edit()` & `update()`:** Menangani edit single nilai dengan perlindungan transaksi dan validasi duplikasi eksklusif (`where('id', '!=', $id)`).
- **`destroy()`:** Menghapus nilai mandiri secara aman.

### B. Model: `App\Models\NilaiMandiriSiswa`
- **`getRataRataSemester($siswaId, $semester)`:** Menghitung rata-rata nilai siswa pada semester tertentu.
- **`getRataRataKeseluruhan($siswaId)`:** Menghitung rata-rata dari seluruh nilai mandiri siswa.
- **`getStatusSemester($siswaId, $semester)`:** Cek keberadaan nilai di semester tertentu.
- **`getDistribusiAkreditasi($siswaId)`:** Menghitung jumlah mapel per grade:
  - **A+** : $\text{nilai} \ge 90$
  - **A**  : $80 \le \text{nilai} < 90$
  - **B**  : $70 \le \text{nilai} < 80$
  - **C**  : $60 \le \text{nilai} < 70$
  - **D**  : $\text{nilai} < 60$
- **`getPersentaseAkreditasi($siswaId)`:** Menghitung persentase proporsi masing-masing grade dari total keseluruhan mapel.

### C. Views: `resources/views/siswa/nilai-mandiri/`
- **`index.blade.php`:** 
  - Tampilan per semester dengan kartu pembatas yang jelas (`S1`, `S2`, dst.) dan badge rata-rata nilai.
  - Donut Chart SVG bulat interaktif di bagian bawah menampilkan proporsi distribusi grade (A+, A, B, C, D) berdampingan dengan card Rata-rata Keseluruhan dan Predikat (Sangat Baik / Baik / Cukup / Perlu Perbaikan).
- **`create.blade.php`:** Form dinamis dengan tombol tambah/hapus baris.
- **`edit.blade.php`:** Form edit single record.

---

## 4. Alur Pemakaian (User Flow) Siswa

1. **Login:** Siswa login dengan akun role `siswa`.
2. **Navigasi:** Masuk ke dashboard siswa dan klik menu **Nilai Raport Mandiri**.
3. **Input Nilai:**
   - Klik tombol **Tambah Nilai**.
   - Pilih semester (1-5).
   - Masukkan nama mata pelajaran dan nilai (0-100).
   - Klik **+ Tambah Mapel** jika ingin memasukkan beberapa mapel sekaligus.
   - Klik **Simpan Semua**.
4. **Melihat Rekap & Diagram:**
   - Kembali ke halaman index nilai mandiri.
   - Setiap semester ditampilkan terpisah dalam Card dengan Rata-rata Semester yang mencolok di pojok kanan atas.
   - Di bagian bawah, siswa melihat **Diagram Donut Bulat** persentase distribusi grade akreditasi beserta total mapel dan Rata-rata Keseluruhan.
5. **Manajemen Data (Edit/Hapus):**
   - Siswa dapat mengedit nilai sewaktu-waktu melalui tombol **Edit** (dengan proteksi duplikasi otomatis).
   - Siswa dapat menghapus nilai melalui tombol **Hapus**.

---

## 5. Hasil Pengujian & Verifikasi (Test Report)

Pengujian end-to-end telah dijalankan melalui tinker dan unit checking dengan hasil **12/12 PASSED**:

| No | Kategori Test | Status | Keterangan |
|---|---|---|---|
| 1 | Database Connection | ✅ | Tabel `nilai_mandiri_siswa` aktif |
| 2 | Model Method: `getRataRataKeseluruhan` | ✅ | Kalkulasi akurat (contoh: 81.45) |
| 3 | Model Method: `getRataRataSemester` | ✅ | Kalkulasi per semester akurat |
| 4 | Model Method: `getDistribusiAkreditasi` | ✅ | Berhasil mengelompokkan A+, A, B, C, D |
| 5 | Model Method: `getPersentaseAkreditasi` | ✅ | Kalkulasi persentase akurat (total 100%) |
| 6 | Controller: `index()` | ✅ | Data terkelompok rapi per semester |
| 7 | Controller: `store()` | ✅ | Batch insert dengan transaction sukses |
| 8 | Controller: `update()` | ✅ | Proteksi transaction & duplikasi sukses |
| 9 | Controller: `destroy()` | ✅ | Delete record sukses |
| 10 | Blade Syntax | ✅ | `index`, `create`, `edit` bebas error |
| 11 | UI Donut Chart SVG | ✅ | Render SVG bulat dengan warna proporsional |
| 12 | Design System Compliance | ✅ | Menggunakan token Tailwind & komponen standar |

---

## 6. Kesimpulan

Fitur **Nilai Raport Mandiri Siswa** kini telah sepenuhnya lengkap, stabil, aman (dilindungi transaction dan isolasi role), serta memiliki antarmuka yang sangat jelas dengan pemisahan per semester yang rapi dan visualisasi Donut Chart bulat untuk distribusi akreditasi. Dokumentasi ini sekaligus menandai penyelesaian final untuk modul ini.
