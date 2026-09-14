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

    public static function getDistribusiAkreditasi($siswaId)
    {
        $nilai = self::where('siswa_id', $siswaId)->get();

        if ($nilai->isEmpty()) {
            return [
                'A+' => 0,
                'A' => 0,
                'B' => 0,
                'C' => 0,
                'D' => 0,
                'total' => 0,
            ];
        }

        $distribusi = [
            'A+' => 0,
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
        ];

        foreach ($nilai as $item) {
            if ($item->nilai >= 90) {
                $distribusi['A+']++;
            } elseif ($item->nilai >= 80) {
                $distribusi['A']++;
            } elseif ($item->nilai >= 70) {
                $distribusi['B']++;
            } elseif ($item->nilai >= 60) {
                $distribusi['C']++;
            } else {
                $distribusi['D']++;
            }
        }

        $distribusi['total'] = $nilai->count();

        return $distribusi;
    }

    public static function getPersentaseAkreditasi($siswaId)
    {
        $distribusi = self::getDistribusiAkreditasi($siswaId);

        if ($distribusi['total'] === 0) {
            return [
                'A+' => 0,
                'A' => 0,
                'B' => 0,
                'C' => 0,
                'D' => 0,
            ];
        }

        return [
            'A+' => round(($distribusi['A+'] / $distribusi['total']) * 100, 1),
            'A' => round(($distribusi['A'] / $distribusi['total']) * 100, 1),
            'B' => round(($distribusi['B'] / $distribusi['total']) * 100, 1),
            'C' => round(($distribusi['C'] / $distribusi['total']) * 100, 1),
            'D' => round(($distribusi['D'] / $distribusi['total']) * 100, 1),
        ];
    }
}

