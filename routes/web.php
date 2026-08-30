<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

  Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class);
    Route::resource('mapel', \App\Http\Controllers\Admin\MapelController::class);
    Route::resource('mengajar', \App\Http\Controllers\Admin\MengajarController::class);
    Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);
  });

  Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('nilai', [\App\Http\Controllers\Guru\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/{mengajar}', [\App\Http\Controllers\Guru\NilaiController::class, 'form'])->name('nilai.form');
    Route::post('nilai/{mengajar}', [\App\Http\Controllers\Guru\NilaiController::class, 'store'])->name('nilai.store');
    Route::get('absensi', [\App\Http\Controllers\Guru\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('absensi/{mengajar}', [\App\Http\Controllers\Guru\AbsensiController::class, 'form'])->name('absensi.form');
    Route::post('absensi/{mengajar}', [\App\Http\Controllers\Guru\AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('jadwal', [\App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');
  });

  Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('nilai', [\App\Http\Controllers\Siswa\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('absensi', [\App\Http\Controllers\Siswa\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('jadwal', [\App\Http\Controllers\Siswa\JadwalController::class, 'index'])->name('jadwal.index');
  });
});
