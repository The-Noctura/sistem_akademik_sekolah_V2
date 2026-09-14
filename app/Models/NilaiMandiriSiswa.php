<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMandiriSiswa extends Model
{
    protected $table = 'nilai_mandiri_siswa';

    protected $fillable = [
        'siswa_id',
        'nama_mapel',
        'semester',
        'nilai',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public static function getRataRataSemester($siswaId, $semester)
    {
        $rata = self::where('siswa_id', $siswaId)
            ->where('semester', $semester)
            ->avg('nilai');

        return $rata ? round($rata, 2) : null;
    }

    public static function getRataRataKeseluruhan($siswaId)
    {
        $rata = self::where('siswa_id', $siswaId)
            ->avg('nilai');

        return $rata ? round($rata, 2) : null;
    }

    public static function getStatusSemester($siswaId, $semester)
    {
        $nilaiCount = self::where('siswa_id', $siswaId)
            ->where('semester', $semester)
            ->count();

        return $nilaiCount > 0;
    }
}

