# AGENTS.md

Kamu sedang membangun website sekolah modul akademik (Laravel + Blade + MySQL) sendirian, dari nol sampai selesai. Tidak ada tim manusia lain yang mengerjakan bagian lain secara paralel — kamu mengerjakan seluruh urutan tahap sampai tuntas.

## Wajib Dibaca Sebelum Mulai Apa Pun

Sebelum mengerjakan tahap pertama, baca file-file berikut memakai Read tool, secara berurutan:

1. `docs/00-context.md` — konteks project, tujuan, scope
2. `docs/01-schema.md` — skema database (FINAL, jangan diubah)
3. `docs/08-user-flows.md` — alur pemakaian aplikasi dari sudut pandang admin/guru/siswa
4. `docs/06-build-sequence.md` — urutan kerja lengkap yang harus kamu ikuti dari Tahap 1 sampai Tahap 12

Setelah itu, kerjakan `docs/06-build-sequence.md` tahap demi tahap secara berurutan. Setiap tahap di file itu akan menyebutkan file referensi lain (`docs/02-sql-objects.sql`, `docs/03-conventions.md`, `docs/04-design-system.md`, `docs/05-routes.md`, `docs/07-layout-patterns.md`, `docs/08-user-flows.md`) — baca file yang direferensikan itu tepat sebelum mengerjakan tahap yang membutuhkannya, jangan menebak isinya dari ingatan.

## Aturan Keras (Tidak Bisa Dinegosiasikan)

0. **JANGAN PERNAH menghapus, memindahkan, atau menimpa folder `docs/` maupun file apa pun di dalamnya.** Ini berlaku sepanjang seluruh sesi kerja, bukan cuma di awal. Ini termasuk secara tidak langsung — misal lewat command yang membersihkan/reset folder project (`rm -rf`, `git clean`, restore dari template, dll). Kalau sebuah command punya potensi menyentuh folder `docs/` (termasuk command instalasi seperti `composer create-project`, `laravel new`, atau apa pun yang menulis ke direktori kerja saat ini), JANGAN jalankan command itu di lokasi yang sama dengan folder `docs/` — jalankan di subfolder kosong terpisah, lalu pindahkan isinya secara manual satu per satu tanpa menyentuh `docs/`.
    - Yang BOLEH kamu lakukan terhadap folder `docs/`: **membaca** isinya dengan Read tool, sesering apa pun dibutuhkan.
    - Yang TIDAK BOLEH kamu lakukan: menghapus, mengubah isi, mengganti nama, memindahkan, menimpa, atau menjalankan command apa pun yang efeknya (langsung maupun tidak langsung/tidak disengaja) menghapus atau mengosongkan folder ini atau isinya.
    - Kalau di titik mana pun kamu mendapati folder `docs/` hilang atau isinya berkurang dari 11 file yang seharusnya ada (lihat daftar di bagian "Struktur Dokumentasi" di bawah), **berhenti total, jangan lanjutkan pekerjaan apa pun**, dan laporkan ke saya — jangan mencoba merekonstruksi ulang isinya dari ingatan, karena versi rekonstruksi dari ingatanmu tidak sama dengan versi final yang sudah direview.

1. **Jangan ubah skema database.** `docs/01-schema.md` final. Kalau menurutmu ada struktur yang kurang optimal, tetap ikuti seperti tertulis — itu keputusan yang sudah direview manusia, bukan draft.

2. **Jangan tulis ulang atau generate ulang isi `docs/02-sql-objects.sql`.** File itu dieksekusi langsung ke MySQL, bukan dijadikan referensi untuk menulis versi Eloquent/PHP-nya sendiri. Prosedur nilai dipanggil lewat `DB::statement('CALL ...')`, bukan diterjemahkan ke kode PHP.

3. **Selalu pakai transaction (`DB::beginTransaction`/`commit`/`rollBack`) untuk operasi yang menyentuh nilai atau absensi secara batch.** Kode contoh lengkap ada di `docs/03-conventions.md` — pakai persis, jangan modifikasi strukturnya.

4. **Selalu validasi cross-check siswa-kelas SEBELUM insert ke `nilai` atau `absensi`.** Tidak ada FK yang mencegah kombinasi salah — ini wajib dicek manual di controller.

5. **Selalu pakai kerangka halaman dari `docs/07-layout-patterns.md`** untuk struktur halaman apa pun. Jangan bikin wrapper/struktur HTML baru dari nol.

6. **Selalu pakai komponen dan token warna dari `docs/04-design-system.md`.** Jangan generate warna baru, jangan generate struktur tombol/form/tabel baru.

7. **Jangan tambah fitur di luar scope** yang tertulis di `docs/00-context.md` (tidak ada rapor PDF, notifikasi, dashboard analitik, role orang tua, dst) meski terasa "akan berguna".

8. **Kerjakan `docs/06-build-sequence.md` berurutan, tahap demi tahap.** Jangan lompat ke tahap yang lebih belakang karena terlihat lebih mudah/menarik. Verifikasi "Cek sebelum lanjut" di tiap tahap wajib lolos sebelum lanjut ke tahap berikutnya.

## Kalau Ragu

Kalau ada instruksi di `docs/06-build-sequence.md` yang ambigu, atau kamu menemukan halaman yang tidak jelas masuk pola layout yang mana, **berhenti dan tanyakan** — jangan menebak dan melanjutkan dengan asumsi sendiri. Kesalahan asumsi di tahap awal (terutama migration dan skema) akan menjalar ke semua tahap berikutnya dan mahal untuk diperbaiki belakangan.

## Struktur Dokumentasi

```
docs/
  00-context.md           -> tujuan, role, scope
  01-schema.md             -> skema database (final)
  02-sql-objects.sql       -> procedure/trigger/function (final, jalankan langsung)
  03-conventions.md        -> struktur folder, naming, kode transaction
  04-design-system.md      -> token warna + component library Blade
  05-routes.md              -> semua route final
  06-build-sequence.md      -> URUTAN KERJA UTAMA, mulai dari sini
  07-layout-patterns.md     -> kerangka Blade per jenis halaman
  08-user-flows.md          -> alur pemakaian dari sudut pandang admin/guru/siswa
```
