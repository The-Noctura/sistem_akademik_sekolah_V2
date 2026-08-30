# 08 — User Flows

File ini menyajikan alur pemakaian aplikasi dari sudut pandang pengguna (bukan urutan pembangunan seperti `docs/06-build-sequence.md`, dan bukan kerangka tampilan seperti `docs/07-layout-patterns.md`). Gunakan file ini untuk memverifikasi bahwa hasil akhir tiap tahap benar-benar mendukung alur nyata di bawah — bukan sekadar lolos "Cek sebelum lanjut" secara teknis.

---

## Alur Admin

1. Login → redirect ke `/dashboard` → tampil dashboard admin (grid menu: Manajemen User, Kelas, Mapel, Mengajar, Jadwal)
2. Admin menyiapkan data dasar, **urutan ini wajib diikuti** karena setiap langkah bergantung ke data dari langkah sebelumnya:
   - **Manajemen User** → tambah akun guru & siswa (nama, email, password, role)
   - **Manajemen Kelas** → buat kelas, pilih wali kelas (dropdown dari guru yang sudah dibuat)
   - **Manajemen Mapel** → buat daftar mata pelajaran
   - **Manajemen Mengajar** → hubungkan guru + mapel + kelas + semester jadi satu baris penugasan. **Ini titik kunci** — tanpa baris `mengajar`, guru tidak akan punya kelas apa pun untuk diinput nilai/absensinya, dan siswa tidak akan punya jadwal/nilai/absensi yang bisa muncul.
   - **Manajemen Jadwal** → tentukan hari/jam/ruangan untuk tiap baris `mengajar` yang sudah dibuat
3. Admin tidak pernah input nilai atau tandai absensi — perannya murni menyiapkan struktur data supaya guru dan siswa bisa memakai fitur mereka.

## Alur Guru

1. Login → dashboard guru (grid menu: Input Nilai, Input Absensi, Jadwal Mengajar)
2. **Input Nilai:**
   - Buka menu Input Nilai → sistem tampilkan daftar kelas+mapel yang diajar (dari `mengajar` miliknya, BUKAN semua kelas)
   - Pilih satu kombinasi kelas+mapel+jenis nilai (tugas/uts/uas)
   - Sistem tampilkan satu tabel berisi semua siswa di kelas itu
   - Guru mengisi nilai tiap siswa di tabel yang sama, lalu submit sekaligus (bukan satu-satu)
   - Sistem menyimpan lewat transaction — kalau satu baris gagal, semua dibatalkan. Rata-rata otomatis terhitung ulang di baliknya (lewat trigger), guru tidak melakukan langkah tambahan untuk ini.
3. **Input Absensi:**
   - Buka menu Input Absensi → pilih kelas+mapel+tanggal
   - Sistem tampilkan tabel siswa dengan pilihan status (hadir/izin/sakit/alpa) per orang
   - Submit sekaligus untuk satu kelas satu hari
   - Sistem menyimpan lewat transaction, rekap kehadiran otomatis terhitung ulang di baliknya
4. **Lihat Jadwal:** read-only, cuma menampilkan jadwal mengajarnya sendiri — tidak ada aksi apa pun di halaman ini.

Guru tidak bisa melihat atau mengubah kelas yang bukan miliknya, meski mencoba mengakses lewat URL langsung — ini dijamin lewat pengecekan kepemilikan `mengajar_id` di controller, bukan cuma middleware role.

## Alur Siswa

1. Login → dashboard siswa (grid menu: Lihat Nilai, Lihat Absensi, Lihat Jadwal)
2. **Lihat Nilai:** per mapel, siswa melihat rincian nilai (tugas/uts/uas) dengan rata-rata ditonjolkan besar di bagian atas setiap kartu mapel. Siswa tidak bisa mengedit apa pun di halaman ini.
3. **Lihat Absensi:** per mapel, siswa melihat total hadir/izin/sakit/alpa dengan persentase kehadiran ditonjolkan besar. Read-only.
4. **Lihat Jadwal:** read-only, jadwal pelajaran kelasnya sendiri.

Siswa hanya bisa melihat datanya sendiri — tidak ada jalan untuk melihat nilai atau absensi siswa lain, bahkan lewat manipulasi URL.

---

## Titik Kritis yang Menghubungkan Ketiga Alur

Alur guru dan siswa **tidak bisa berjalan** sama sekali sebelum admin menyelesaikan seluruh langkah 2 di "Alur Admin" — khususnya baris `mengajar`. Kalau saat testing menyeluruh (Tahap 12 di `docs/06-build-sequence.md`) ternyata guru login tapi dashboard-nya kosong tanpa kelas, atau siswa login tapi tidak ada nilai/jadwal muncul, penyebab paling mungkin adalah data admin di langkah 2 belum lengkap — bukan bug di modul guru/siswa itu sendiri. Cek data `mengajar` dulu sebelum menelusuri kode.

## Kapan File Ini Dipakai

- Saat mengerjakan Tahap 7 (Dashboard) di `docs/06-build-sequence.md` — pastikan isi tiap dashboard sesuai menu yang disebut di alur masing-masing role di atas
- Saat mengerjakan Tahap 11 (Nilai & Absensi) — pastikan urutan klik yang diikuti controller/view benar-benar sesuai langkah di "Alur Guru" (pilih kelas dulu → baru tampil tabel siswa → baru submit sekaligus, bukan submit satu-satu per siswa)
- Saat testing akhir menyeluruh (Tahap 12) — jalankan alur ini persis dari awal sampai akhir untuk tiap role, bukan cuma menguji tiap halaman secara terpisah
