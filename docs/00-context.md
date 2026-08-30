# 00 — Context

Ini project website sekolah modul akademik. Dibangun 100% oleh AI agent (tidak ada tim manusia paralel mengerjakan bagian lain) — kamu mengerjakan seluruh project ini sendirian, dari migration pertama sampai halaman terakhir, mengikuti urutan di `docs/06-build-sequence.md`.

## Tujuan

Mengelola nilai, absensi, dan jadwal pelajaran secara digital. Menggantikan pencatatan manual. Fokus sempit ke 3 hal: nilai, absensi, jadwal. BUKAN sistem informasi sekolah lengkap — tidak ada PPDB, keuangan, perpustakaan.

## 3 Role Pengguna

| Role | Kebutuhan utama |
|---|---|
| **Admin** | Kelola data dasar: user, kelas, mapel, siapa-ngajar-apa, jadwal |
| **Guru** | Input nilai & absensi per kelas yang diajar, lihat jadwal mengajar sendiri |
| **Siswa** | Lihat nilai & rekap absensi sendiri, lihat jadwal pelajaran |

Tidak ada role orang tua terpisah. Ini keputusan scope yang disengaja, bukan sesuatu yang perlu "dilengkapi".

## Scope Fitur (Semua Wajib Dibangun)

1. Auth & role-based login (admin/guru/siswa)
2. Input & Lihat Nilai (guru input, siswa lihat)
3. Input & Lihat Absensi (guru input, siswa lihat)
4. Lihat Jadwal Pelajaran (read-only untuk guru & siswa, CRUD untuk admin)
5. Manajemen data dasar oleh admin (user, kelas, mapel, mengajar, jadwal)

Tidak perlu dibangun (di luar scope, jangan tambahkan meski "kelihatan berguna"): rapor PDF, dashboard analitik visual, notifikasi, sistem kelulusan otomatis, halaman log perubahan, multi-tahun-ajaran penuh, role orang tua, export Excel/CSV.

## Stack

Laravel + Blade + MySQL. Tidak ada frontend framework terpisah (bukan React/Vue). Tidak ada API terpisah — semua server-rendered.

## Prinsip Kerja Kamu Sebagai Agent

- **Ikuti `docs/06-build-sequence.md` secara berurutan.** Jangan lompat tahap, jangan kerjakan tahap belakangan duluan karena "kelihatan lebih mudah" — banyak tahap punya dependency ke tahap sebelumnya (migration harus ada sebelum model, model sebelum controller, dst).
- **Jangan improvisasi struktur data.** Skema di `docs/01-schema.md` final. Kalau menurutmu ada yang "kurang optimal", tetap ikuti seperti tertulis — itu bukan draft, itu keputusan yang sudah direview.
- **Jangan tulis ulang isi SQL procedure/trigger/function.** File `docs/02-sql-objects.sql` sudah final, tinggal dieksekusi ke database, bukan dijadikan referensi untuk "menulis versi Laravel-nya sendiri".
- **Selalu pakai kerangka layout dari `docs/07-layout-patterns.md`** untuk halaman apa pun yang kamu buat. Jangan bikin struktur HTML/Blade baru dari nol.
- **Selalu pakai komponen dari `docs/04-design-system.md`.** Kalau butuh elemen UI yang belum ada di situ, gunakan kombinasi yang ada — jangan generate HTML mentah baru.
- Baca file di `docs/` dengan Read tool **sebelum** mengerjakan tahap yang membutuhkannya — jangan mengandalkan ingatan dari sesi sebelumnya kalau ada keraguan.
