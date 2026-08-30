# 03 — Conventions

## Struktur Folder

```
app/
  Http/
    Controllers/
      Admin/          -> UserController, KelasController, MapelController, MengajarController, JadwalController
      Guru/            -> NilaiController, AbsensiController, JadwalController
      Siswa/           -> NilaiController, AbsensiController, JadwalController
    Middleware/
      EnsureUserHasRole.php
  Models/              -> satu file per tabel, tanpa subfolder
database/
  migrations/
  seeders/
resources/
  views/
    layouts/
      app.blade.php
    components/        -> lihat docs/04-design-system.md untuk daftar lengkap
    admin/
    guru/
    siswa/
    auth/              -> dari Laravel Breeze
routes/
  web.php              -> SEMUA route di sini, lihat docs/05-routes.md
```

Jangan buat struktur folder lain di luar ini. Kalau butuh menambah, tambahkan mengikuti pola yang sudah ada (misal controller baru untuk role tertentu masuk ke folder role itu).

## Naming Convention

| Jenis | Aturan | Contoh |
|---|---|---|
| Model | Singular, PascalCase | `Siswa`, `Mengajar`, `RekapNilai` |
| Nama tabel | Ikuti PERSIS `docs/01-schema.md` | `siswa`, `rekap_nilai` — JANGAN diubah ke plural default Laravel |
| Controller | PascalCase + `Controller` | `NilaiController` |
| Route name | kebab-case, prefix role | `guru.nilai.index`, `admin.kelas.create` |
| Blade view file | kebab-case | `input-nilai.blade.php` |
| Blade component | kebab-case, dipakai dengan prefix `x-` | `<x-form-input>` → file `components/form-input.blade.php` |
| Variable PHP | camelCase | `$rataRataNilai`, `$daftarSiswa` |
| Method controller | camelCase, kata kerja | `index()`, `store()` |

**Nama tabel WAJIB persis seperti di `docs/01-schema.md`** — procedure dan trigger di `docs/02-sql-objects.sql` sudah reference nama tabel spesifik. Kalau nama tabel di migration Laravel beda (misal jadi plural otomatis), SQL akan gagal total.

## Aturan Blade

Semua halaman extend dari 1 layout:

```blade
@extends('layouts.app')
@section('content')
    ...
@endsection
```

Untuk elemen tanpa parameter: `@include('components.navbar')`. Untuk komponen dengan parameter: `<x-form-input name="nilai" type="number" />`. Selalu cek `docs/04-design-system.md` sebelum bikin komponen baru — kemungkinan besar sudah ada.

**Untuk struktur halaman (bukan komponen kecil), WAJIB pakai kerangka dari `docs/07-layout-patterns.md`.** Jangan bikin struktur wrapper halaman sendiri.

## Coding Style

- PSR-12 untuk PHP (default Laravel)
- Query database: selalu Eloquent atau query builder, KECUALI untuk memanggil procedure/function (`DB::statement()` / `DB::select()`)
- Validasi: pakai `$request->validate([...])` langsung di controller untuk kasus sederhana; Form Request class kalau lebih dari 5 field
- Tidak ada raw string concatenation untuk membangun query, di endpoint mana pun — termasuk endpoint "ringan" seperti jadwal

## Transaction Handling — WAJIB untuk Nilai & Absensi

Ini bagian paling kritis. Modul Nilai dan Absensi HARUS pakai pola persis ini — jangan modifikasi strukturnya.

### Pola untuk Input Nilai (panggil stored procedure per siswa)

```php
public function store(Request $request, $mengajarId)
{
    $request->validate([
        'jenis' => 'required|in:tugas,uts,uas',
        'nilai' => 'required|array',
        'nilai.*' => 'required|numeric|min:0|max:100',
    ]);

    $mengajar = Mengajar::findOrFail($mengajarId);

    // WAJIB: cross-check siswa-kelas SEBELUM masuk transaction
    foreach (array_keys($request->nilai) as $siswaId) {
        $siswa = Siswa::find($siswaId);
        if (!$siswa || $siswa->kelas_id !== $mengajar->kelas_id) {
            return back()->withErrors(['error' => 'Ada siswa yang tidak sesuai kelas.']);
        }
    }

    DB::beginTransaction();
    try {
        foreach ($request->nilai as $siswaId => $nilai) {
            DB::statement('CALL sp_input_nilai_kelas(?, ?, ?, ?, ?)', [
                $mengajarId, $request->jenis, $siswaId, $nilai, auth()->id()
            ]);
        }
        DB::commit();
        return back()->with('success', 'Nilai berhasil disimpan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Gagal simpan nilai: ' . $e->getMessage()]);
    }
}
```

### Pola untuk Input Absensi (insert biasa, BUKAN lewat procedure)

Absensi tidak punya stored procedure sendiri untuk insert — insert lewat Eloquent biasa, karena trigger `trg_absensi_insert` otomatis jalan setelah INSERT apa pun (termasuk `Model::create()`).

```php
public function store(Request $request, $mengajarId)
{
    $request->validate([
        'tanggal' => 'required|date',
        'status' => 'required|array',
        'status.*' => 'required|in:hadir,izin,sakit,alpa',
    ]);

    DB::beginTransaction();
    try {
        foreach ($request->status as $siswaId => $status) {
            Absensi::create([
                'siswa_id' => $siswaId,
                'mengajar_id' => $mengajarId,
                'tanggal' => $request->tanggal,
                'status' => $status,
            ]);
        }
        DB::commit();
        return back()->with('success', 'Absensi berhasil disimpan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Gagal simpan absensi: ' . $e->getMessage()]);
    }
}
```

**Perbedaan kunci antara dua pola di atas — jangan sampai tertukar:**
- Nilai: `DB::statement('CALL sp_input_nilai_kelas(...)')` — WAJIB lewat procedure
- Absensi: `Absensi::create([...])` — Eloquent biasa, JANGAN dibuatkan procedure baru untuk ini

## Middleware Role

```php
// app/Http/Middleware/EnsureUserHasRole.php
public function handle(Request $request, Closure $next, string $role)
{
    if (auth()->user()->role !== $role) {
        abort(403, 'Tidak punya akses ke halaman ini.');
    }
    return $next($request);
}
```

Setiap route group WAJIB dibungkus middleware ini sesuai role (lihat `docs/05-routes.md`). Setiap controller di folder `Admin/`, `Guru/`, `Siswa/` mengasumsikan middleware sudah menyaring akses — TAPI tetap tambahkan pengecekan kepemilikan data di dalam controller (misal: guru hanya bisa akses `mengajar` miliknya sendiri, bukan sekadar role `guru` yang benar).
