# Sistem Akademik Sekolah

Website manajemen akademik sekolah berbasis **Laravel 11 + Blade + MySQL** dengan tiga role pengguna: **Admin**, **Guru**, dan **Siswa**.

## 🎯 Fitur Utama

### Admin
- Manajemen User (Guru & Siswa) — CRUD lengkap
- Manajemen Kelas — CRUD + wali kelas (dropdown guru)
- Manajemen Mata Pelajaran — CRUD
- Manajemen Mengajar — Hubungkan Guru + Mapel + Kelas + Semester
- Manajemen Jadwal — Atur hari/jam/ruangan per mengajar

### Guru
- **Input Nilai** — Pilih kelas+mapel → tabel siswa → input massal (Tugas/UTS/UAS) → simpan transaksional
- **Input Absensi** — Pilih kelas+mapel+tanggal → tabel siswa + status (Hadir/Izin/Sakit/Alpa) → simpan transaksional
- **Jadwal Mengajar** — Read-only, grouped by hari

### Siswa
- **Lihat Nilai** — Per mapel, rata-rata ditonjolkan besar, detail Tugas/UTS/UAS
- **Lihat Absensi** — Per mapel, persentase kehadiran + badge status + ringkasan count
- **Lihat Jadwal** — Read-only, grouped by hari

---

## 🏗️ Arsitektur & Teknologi

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11, PHP 8.3+ |
| Frontend | Blade, Tailwind CSS, Vite |
| Database | MySQL 8.0+ |
| Auth | Laravel Breeze (Blade) |
| Business Logic | **Stored Procedures + Triggers** (MySQL) |

### Database Design Highlights
- **Tabel inti**: `users`, `guru`, `siswa`, `kelas`, `mapel`, `mengajar`
- **Modul transaksional**: `nilai`, `absensi`
- **Rekap denormalisasi**: `rekap_nilai`, `rekap_absensi` (auto-update via trigger)
- **Audit trail**: `log_perubahan`
- **Unique keys** mencegah duplikasi: nilai (siswa+mengajar+jenis), absensi (siswa+mengajar+tanggal), rekap (siswa+mengajar+semester)

### Stored Procedures & Triggers (docs/02-sql-objects.sql)
| Object | Jenis | Fungsi |
|--------|-------|--------|
| `fn_rata_rata_nilai` | Function | Hitung rata-rata nilai siswa per mengajar |
| `fn_persentase_hadir` | Function | Hitung persentase kehadiran |
| `sp_input_nilai_kelas` | Procedure | Upsert nilai (ON DUPLICATE KEY UPDATE) |
| `sp_rekap_absensi` | Procedure | Hitung ulang rekap absensi (dipanggil trigger) |
| `trg_rekap_nilai_insert` | Trigger | Update rekap_nilai saat INSERT nilai |
| `trg_rekap_nilai_update` | Trigger | Update rekap_nilai saat UPDATE nilai |
| `trg_log_nilai_update` | Trigger | Audit log saat nilai diubah |
| `trg_absensi_insert` | Trigger | Auto-call `sp_rekap_absensi` saat INSERT absensi |

---

## 🚀 Instalasi & Setup

### Prasyarat
- PHP 8.3+
- Composer 2.x
- Node.js 20+ & npm
- MySQL 8.0+

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repo-url>
cd sistem-akademik

# 2. Install dependencies PHP
composer install

# 3. Install dependencies JS & build assets
npm install && npm run build

# 4. Copy env & generate key
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sekolah_db
DB_USERNAME=phpmyadmin
DB_PASSWORD=php123

# 6. Buat database MySQL
mysql -u root -p -e "CREATE DATABASE sekolah_db;"

# 7. Jalankan migration
php artisan migrate

# 8. Jalankan SQL Objects (procedures, functions, triggers)
mysql -u phpmyadmin -pphp123 sekolah_db < docs/02-sql-objects.sql

# 9. Verifikasi SQL objects
mysql -u phpmyadmin -pphp123 -e "SHOW FUNCTION STATUS; SHOW PROCEDURE STATUS; SHOW TRIGGERS;" sekolah_db

# 10. Seed data testing
php artisan db:seed --class=AkademikSeeder

# 11. Jalankan server
php artisan serve
```

### Akses Aplikasi
Buka http://localhost:8000

---

## 🔐 Credentials Default (dari Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@sekolah.test | password |
| Guru (Matematika) | budi@sekolah.test | password |
| Guru (B. Indonesia) | siti@sekolah.test | password |
| Siswa (10 siswa) | siswa1001@sekolah.test ... siswa1010@sekolah.test | password |

---

## 📁 Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # User, Kelas, Mapel, Mengajar, Jadwal
│   │   ├── Guru/           # Nilai, Absensi, Jadwal
│   │   └── Siswa/          # Nilai, Absensi, Jadwal
│   └── Middleware/
│       └── EnsureUserHasRole.php
├── Models/                 # 11 model dengan relasi Eloquent
database/
├── migrations/             # 13 migration (master + modul + rekap)
├── seeders/
│   └── AkademikSeeder.php
resources/
├── views/
│   ├── layouts/app.blade.php
│   ├── components/         # 10 component Blade (button, card, table, dll)
│   ├── admin/              # CRUD views (Pola 1 & 2)
│   ├── guru/               # Nilai, Absensi, Jadwal (Pola 1, 2, 4)
│   └── siswa/              # Nilai, Absensi, Jadwal (Pola 4)
routes/
└── web.php                 # Semua route dengan middleware role
docs/                       # Dokumentasi arsitektur (00-08)
```

---

## 🎨 Design System

**Tokens** (`tailwind.config.js`):
- `accent` (#2563EB), `accent-hover` (#1D4ED8), `accent-soft` (#EFF6FF)
- `surface` (#F8FAFC), `border` (#E2E8F0)
- Font: **Inter** (400, 500, 600)

**Components** (`resources/views/components/`):
- `button` (primary/secondary/danger)
- `card`, `table`, `form-input`, `form-select`
- `badge` (success/error/warning/default)
- `alert` (success/error), `navbar`, `dashboard-link`

**Layout Patterns** (`docs/07-layout-patterns.md`):
1. **Pola 1** — List/Index (tabel + aksi kanan atas)
2. **Pola 2** — Form Create/Edit (card + tombol kanan bawah)
3. **Pola 3** — Dashboard (grid 3 kolom dashboard-link)
4. **Pola 4** — Detail Read-Only Siswa (card per mapel + angka besar accent)

---

## 🔒 Keamanan & Validasi

- **Middleware Role** — Setiap route group dibungkus `role:admin|guru|siswa`
- **Ownership Check** — Guru hanya akses `mengajar` miliknya; Siswa hanya data sendiri
- **Cross-check Siswa-Kelas** — Validasi manual di controller sebelum INSERT nilai/absensi
- **Rentang Nilai** — Validasi 0-100 di controller (bukan di DB)
- **Transaksi** — `DB::beginTransaction/commit/rollBack` untuk batch insert nilai & absensi

---

## 📖 Dokumentasi Lengkap

| File | Deskripsi |
|------|-----------|
| `docs/00-context.md` | Konteks project, tujuan, scope |
| `docs/01-schema.md` | Skema database FINAL (wajib diikuti) |
| `docs/02-sql-objects.sql` | Procedures, functions, triggers (jalankan langsung ke MySQL) |
| `docs/03-conventions.md` | Struktur folder, naming, transaction handling |
| `docs/04-design-system.md` | Design tokens + component library |
| `docs/05-routes.md` | Daftar route final dengan middleware |
| `docs/06-build-sequence.md` | **Urutan build 12 tahap** (wajib baca sebelum mulai) |
| `docs/07-layout-patterns.md` | 4 kerangka halaman wajib pakai |
| `docs/08-user-flows.md` | Alur pemakaian Admin/Guru/Siswa |

---

## 🧪 Testing

### Manual Testing Checklist
Lihat [`TESTING_CHECKLIST.md`](TESTING_CHECKLIST.md) — dibagi untuk 4 tester:
- **Tester A**: Admin flow + Master Data CRUD
- **Tester B**: Guru 1 (Matematika) — Nilai, Absensi, Jadwal
- **Tester C**: Guru 2 (B. Indonesia) — Data isolation
- **Tester D**: Siswa — Lihat Nilai, Absensi, Jadwal

### Verifikasi Kritis (Tahap 11)
1. Guru input nilai 4 siswa → masuk `nilai` + `rekap_nilai`
2. Edit nilai → `rekap_nilai` berubah (trigger update)
3. Nilai >100/<0 → ditolak validasi
4. Siswa lihat nilai → rata-rata benar
5. Guru input absensi → `absensi` + `rekap_absensi` (trigger insert)
6. Siswa lihat absensi → persentase + badge benar
7. Siswa akses `/guru/*` → 403

---

## 📝 Catatan Penting

1. **JANGAN ubah skema database** — `docs/01-schema.md` final
2. **JANGAN tulis ulang SQL objects** — jalankan `docs/02-sql-objects.sql` langsung ke MySQL
3. **Nama tabel WAJIB persis** seperti schema (bukan plural Laravel default)
4. **Prosedur nilai dipanggil via** `DB::statement('CALL sp_input_nilai_kelas(...)')`
5. **Absensi pakai Eloquent biasa** (`Absensi::create()`) — trigger otomatis jalan
6. **Semua halaman wajib pakai** layout patterns (`docs/07-layout-patterns.md`) dan design system (`docs/04-design-system.md`)

---

## 📄 Lisensi

Internal project — tidak untuk distribusi.

---

## 👥 Tim Pengembang

Dibangun mengikuti **docs/06-build-sequence.md** tahap 1-12 secara berurutan oleh single developer (AI-assisted).
