# Prompt Awal — Mulai Sesi OpenCode

Tempel prompt ini sebagai pesan pertama ke opencode (dengan model Nemotron 3 Ultra) setelah semua file di `AGENTS.md` dan `docs/` sudah ada di root project.

---

```
Baca AGENTS.md di root project ini terlebih dahulu, ikuti semua instruksi
di dalamnya. Setelah itu, baca docs/00-context.md, docs/01-schema.md, dan
docs/06-build-sequence.md secara berurutan.

Tugasmu: bangun seluruh website sekolah modul akademik ini sendirian,
mengikuti docs/06-build-sequence.md dari Tahap 1 sampai Tahap 12,
berurutan, tanpa melompat tahap.

Sebelum mengerjakan tiap tahap, baca file referensi yang disebutkan di
tahap itu (ada di folder docs/) memakai Read tool — jangan mengerjakan
berdasarkan ingatan atau tebakan.

Setelah menyelesaikan setiap tahap, jalankan verifikasi "Cek sebelum
lanjut" yang tertulis di tahap itu. Laporkan hasil verifikasi ke saya
sebelum lanjut ke tahap berikutnya — jangan lanjut sendiri kalau ada
verifikasi yang gagal atau meragukan.

Kalau ada instruksi yang ambigu atau kamu tidak yakin suatu halaman
masuk pola layout yang mana di docs/07-layout-patterns.md, berhenti dan
tanya saya — jangan menebak.

Mulai dari Tahap 1 sekarang.
```

---

## Catatan Pemakaian

- **Laporan per tahap**: prompt ini sengaja meminta agent melapor tiap tahap (bukan lanjut otomatis sampai Tahap 12) supaya ada titik cek manusia di antara tahap-tahap kritis, khususnya Tahap 3-4 (migration + SQL objects) dan Tahap 11 (Nilai & Absensi) yang paling rawan kalau salah.
- **Kalau ingin agent jalan tanpa berhenti** (misal project benar-benar ditinggal karena molor total dan tidak ada waktu supervisi), hapus baris "Laporkan hasil verifikasi ke saya sebelum lanjut ke tahap berikutnya" dan ganti jadi "Lanjutkan otomatis ke tahap berikutnya kalau verifikasi berhasil, catat semua langkah di sebuah file PROGRESS.md agar bisa direview belakangan." — tapi ini lebih berisiko karena tidak ada titik koreksi di tengah jalan.
- **Kalau sesi terputus di tengah** (opencode berhenti, koneksi putus, dst), prompt lanjutan yang bisa dipakai:

```
Lanjutkan pekerjaan dari sesi sebelumnya. Baca AGENTS.md, lalu cek
progress saat ini (lihat migration, controller, dan view yang sudah
ada) untuk menentukan kamu sedang di Tahap berapa dari
docs/06-build-sequence.md. Verifikasi tahap terakhir yang sepertinya
sudah dikerjakan sebelum melanjutkan ke tahap berikutnya.
```
