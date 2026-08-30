# 05 — Routes

Semua route didaftarkan di `routes/web.php`. Struktur middleware group WAJIB seperti ini:

```php
Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // route admin
    });

    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        // route guru
    });

    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        // route siswa
    });

});
```

---

## Daftar Lengkap Route

### Auth (dari Laravel Breeze, tidak perlu dibuat manual)

| Route | Method |
|---|---|
| `/login` | GET, POST |
| `/logout` | POST |
| `/dashboard` | GET — redirect isi beda tergantung role, lihat `docs/06-build-sequence.md` |

### Admin

| Route | Method | Name | Controller |
|---|---|---|---|
| `/admin/users` | resource | `admin.users.*` | `Admin\UserController` |
| `/admin/kelas` | resource | `admin.kelas.*` | `Admin\KelasController` |
| `/admin/mapel` | resource | `admin.mapel.*` | `Admin\MapelController` |
| `/admin/mengajar` | resource | `admin.mengajar.*` | `Admin\MengajarController` |
| `/admin/jadwal` | resource | `admin.jadwal.*` | `Admin\JadwalController` |

Semua pakai `Route::resource()` — cover index/create/store/edit/update/destroy sekaligus.

### Guru

| Route | Method | Name | Controller |
|---|---|---|---|
| `/guru/nilai` | GET | `guru.nilai.index` | `Guru\NilaiController@index` |
| `/guru/nilai/{mengajar}` | GET | `guru.nilai.form` | `Guru\NilaiController@form` |
| `/guru/nilai/{mengajar}` | POST | `guru.nilai.store` | `Guru\NilaiController@store` |
| `/guru/absensi` | GET | `guru.absensi.index` | `Guru\AbsensiController@index` |
| `/guru/absensi/{mengajar}` | GET | `guru.absensi.form` | `Guru\AbsensiController@form` |
| `/guru/absensi/{mengajar}` | POST | `guru.absensi.store` | `Guru\AbsensiController@store` |
| `/guru/jadwal` | GET | `guru.jadwal.index` | `Guru\JadwalController@index` |

### Siswa

| Route | Method | Name | Controller |
|---|---|---|---|
| `/siswa/nilai` | GET | `siswa.nilai.index` | `Siswa\NilaiController@index` |
| `/siswa/absensi` | GET | `siswa.absensi.index` | `Siswa\AbsensiController@index` |
| `/siswa/jadwal` | GET | `siswa.jadwal.index` | `Siswa\JadwalController@index` |

---

## Catatan Implementasi

- `{mengajar}` di route Nilai/Absensi adalah `mengajar_id` — dipakai untuk menentukan kelas mana yang sedang diproses
- Tidak ada route CRUD untuk `rekap_nilai` atau `rekap_absensi` — kedua tabel ini cuma dibaca (di `siswa.nilai.index` dan `siswa.absensi.index`), tidak pernah ditulis manual lewat route apa pun
- Tidak ada route API terpisah (`/api/...`) — semua server-rendered lewat Blade
