# 01 — Database Schema

**STATUS: FINAL.** Skema ini sudah direview manusia dan tidak boleh diubah oleh agent dengan alasan apa pun — bukan alasan "normalisasi lebih baik", bukan "kolom ini sebaiknya dipisah", bukan "tipe data ini kurang tepat". Kalau kamu (agent) merasa ada yang janggal di skema ini, JANGAN mengubahnya — lanjutkan sesuai yang tertulis. Ini adalah pagar paling kritis di seluruh dokumentasi ini.

---

## Master Data

### `users`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| nama | string |
| email | string, unique |
| password | string (hashed) |
| role | enum('admin','guru','siswa') |
| created_at, updated_at | timestamp |

### `guru`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| user_id | FK → users.id |
| nip | string |
| nama | string |
| no_hp | string, nullable |
| created_at, updated_at | timestamp |

### `siswa`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| user_id | FK → users.id |
| nis | string |
| nama | string |
| kelas_id | FK → kelas.id |
| jenis_kelamin | string |
| tanggal_lahir | date |
| created_at, updated_at | timestamp |

### `kelas`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| nama_kelas | string |
| tingkat | string |
| wali_kelas_id | FK → guru.id, nullable |
| tahun_ajaran | string |
| created_at, updated_at | timestamp |

### `mapel`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| nama_mapel | string |
| kode_mapel | string |
| created_at, updated_at | timestamp |

---

## Relasi Pengajaran

### `mengajar`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| guru_id | FK → guru.id |
| mapel_id | FK → mapel.id |
| kelas_id | FK → kelas.id |
| tahun_ajaran | string |
| semester | string |
| created_at, updated_at | timestamp |

**Satu baris = satu kombinasi guru+mapel+kelas+semester.** Tabel `jadwal`, `nilai`, `absensi` semua reference ke `mengajar_id`, BUKAN langsung ke `guru_id`/`mapel_id`/`kelas_id`. Jangan buat FK langsung dari tabel-tabel itu ke guru/mapel/kelas — itu akan merusak asumsi seluruh sistem.

---

## Modul Inti

### `jadwal`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| mengajar_id | FK → mengajar.id |
| hari | enum('senin','selasa','rabu','kamis','jumat','sabtu') |
| jam_mulai | time |
| jam_selesai | time |
| ruangan | string |
| created_at, updated_at | timestamp |

### `nilai`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| siswa_id | FK → siswa.id |
| mengajar_id | FK → mengajar.id |
| jenis | enum('tugas','uts','uas') |
| nilai | decimal(5,2) |
| tanggal_input | timestamp |
| diinput_oleh | FK → users.id |
| created_at, updated_at | timestamp |

**UNIQUE KEY wajib:** `(siswa_id, mengajar_id, jenis)` — ini fondasi `ON DUPLICATE KEY UPDATE` di `sp_input_nilai_kelas`. JANGAN dihilangkan atau diubah urutan kolomnya.

### `absensi`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| siswa_id | FK → siswa.id |
| mengajar_id | FK → mengajar.id |
| tanggal | date |
| status | enum('hadir','izin','sakit','alpa') |
| created_at, updated_at | timestamp |

**UNIQUE KEY wajib:** `(siswa_id, mengajar_id, tanggal)` — ini yang mencegah 1 siswa diabsen 2x di tanggal sama. JANGAN dihilangkan.

---

## Rekap (Denormalisasi — Auto-generate Lewat Trigger)

### `rekap_nilai`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| siswa_id | FK → siswa.id |
| mengajar_id | FK → mengajar.id |
| semester | string |
| rata_rata | decimal(5,2), nullable |
| nilai_akhir | decimal(5,2), nullable |
| predikat | string, nullable |
| updated_at | timestamp |

**UNIQUE KEY wajib:** `(siswa_id, mengajar_id, semester)`

### `rekap_absensi`
| Kolom | Tipe |
|---|---|
| id | bigint, PK |
| siswa_id | FK → siswa.id |
| mengajar_id | FK → mengajar.id |
| semester | string |
| total_hadir, total_izin, total_sakit, total_alpa | integer, default 0 |
| persentase_hadir | decimal(5,2), nullable |
| updated_at | timestamp |

**UNIQUE KEY wajib:** `(siswa_id, mengajar_id, semester)`

**PENTING:** kedua tabel ini terisi OTOMATIS lewat trigger (lihat `docs/02-sql-objects.sql`). JANGAN buat controller/route untuk CRUD manual ke tabel ini — kalau ada task yang terkesan butuh ini, itu tanda kamu salah paham task-nya, bukan tanda perlu ditambah.

---

## Objek Database Non-Tabel

Detail lengkap SQL ada di `docs/02-sql-objects.sql`. Jangan tulis ulang isinya di migration Laravel — jalankan file itu langsung ke MySQL (lihat instruksi eksekusi di file tersebut).

| Nama | Jenis | Fungsi |
|---|---|---|
| `sp_input_nilai_kelas` | Procedure | Input/update nilai per siswa (dipanggil dari controller lewat `DB::statement`) |
| `sp_rekap_absensi` | Procedure | Dipanggil OTOMATIS oleh trigger, JANGAN dipanggil manual dari controller |
| `fn_rata_rata_nilai` | Function | Dipakai trigger nilai + fallback tampilan |
| `fn_persentase_hadir` | Function | Fallback tampilan kalau `rekap_absensi` belum ada |
| `trg_rekap_nilai_insert` | Trigger AFTER INSERT ON nilai | Update `rekap_nilai` |
| `trg_rekap_nilai_update` | Trigger AFTER UPDATE ON nilai | Sinkronkan ulang `rekap_nilai` saat nilai diedit |
| `trg_absensi_insert` | Trigger AFTER INSERT ON absensi | Otomatis panggil `sp_rekap_absensi` |

---

## Validasi yang TIDAK Dijamin Skema — WAJIB di Controller

Tidak ada FK constraint yang mencegah ini. Kamu HARUS menambahkan validasi eksplisit di controller untuk:

1. **Rentang nilai** — `0 ≤ nilai ≤ 100`
2. **Kecocokan siswa-kelas** — sebelum insert ke `nilai`/`absensi`, cek `siswa.kelas_id` sama dengan `kelas_id` dari `mengajar` yang bersangkutan. Tolak kalau tidak cocok.
3. **Role di setiap route** — endpoint guru harus menolak akses dari user role `siswa`, dan sebaliknya. Ini lewat middleware, bukan pengecekan manual di tiap controller.

Kalau kamu membuat controller untuk nilai/absensi TANPA validasi di atas, itu bug — bukan "boleh ditambah nanti".
