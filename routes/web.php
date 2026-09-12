<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/tentang', [PublicController::class, 'about'])->name('public.about');
Route::get('/program-keahlian', [PublicController::class, 'programs'])->name('public.programs');
Route::get('/program-keahlian/{code}', [PublicController::class, 'programShow'])->name('public.programs.show');
Route::get('/fasilitas', [PublicController::class, 'facilities'])->name('public.facilities');
Route::get('/berita', [PublicController::class, 'news'])->name('public.news');
Route::get('/berita/{slug}', [PublicController::class, 'newsShow'])->name('public.news.show');
Route::get('/produk-tefa', [PublicController::class, 'tefa'])->name('public.tefa');
Route::get('/kontak', [PublicController::class, 'contact'])->name('public.contact');

// Dashboard Utama
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

// Profil Pengguna
Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
  Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::resource('mapel', \App\Http\Controllers\Admin\MapelController::class);
    Route::resource('mengajar', \App\Http\Controllers\Admin\MengajarController::class);
    Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);
    Route::resource('tefa', \App\Http\Controllers\Admin\TefaProductController::class);
    Route::resource('berita', \App\Http\Controllers\Admin\BeritaController::class);
  });

  Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('nilai', [\App\Http\Controllers\Guru\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/{mengajar}', [\App\Http\Controllers\Guru\NilaiController::class, 'form'])->name('nilai.form');
    Route::post('nilai/{mengajar}', [\App\Http\Controllers\Guru\NilaiController::class, 'store'])->name('nilai.store');
    Route::delete('nilai/{mengajar}/siswa/{siswa}/jenis/{jenis}', [\App\Http\Controllers\Guru\NilaiController::class, 'destroyNilai'])->name('nilai.destroy-nilai');

    Route::get('absensi', [\App\Http\Controllers\Guru\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('absensi/{mengajar}', [\App\Http\Controllers\Guru\AbsensiController::class, 'form'])->name('absensi.form');
    Route::post('absensi/{mengajar}', [\App\Http\Controllers\Guru\AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('absensi/{mengajar}/riwayat', [\App\Http\Controllers\Guru\AbsensiController::class, 'history'])->name('absensi.history');
    Route::delete('absensi/{mengajar}/tanggal/{tanggal}', [\App\Http\Controllers\Guru\AbsensiController::class, 'destroyDate'])->name('absensi.destroy-date');

    Route::get('jadwal', [\App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');
  });

  Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('nilai', [\App\Http\Controllers\Siswa\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('absensi', [\App\Http\Controllers\Siswa\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('jadwal', [\App\Http\Controllers\Siswa\JadwalController::class, 'index'])->name('jadwal.index');
  });
});
