<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mengajar;
use App\Models\RekapAbsensi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $kelasId = $siswa->kelas_id;

        if (!$kelasId) {
            return view('siswa.absensi.index', ['dataPerMapel' => []]);
        }

        $mengajarList = Mengajar::with(['mapel'])
            ->where('kelas_id', $kelasId)
            ->get();

        $dataPerMapel = [];

        foreach ($mengajarList as $mengajar) {
            $absensi = Absensi::where('siswa_id', $siswa->id)
                ->where('mengajar_id', $mengajar->id)
                ->get();

            $rekap = RekapAbsensi::where('siswa_id', $siswa->id)
                ->where('mengajar_id', $mengajar->id)
                ->where('semester', $mengajar->semester)
                ->first();

            $persentase = $rekap->persentase_hadir ?? null;

            if ($persentase === null) {
                $persentase = DB::selectOne("SELECT fn_persentase_hadir(?, ?) as persentase", [
                    $siswa->id, $mengajar->id
                ])->persentase ?? 0;
            }

            $counts = [
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'alpa' => $absensi->where('status', 'alpa')->count(),
            ];

            $dataPerMapel[$mengajar->mapel->nama_mapel] = [
                'mengajar' => $mengajar,
                'absensi' => $absensi,
                'counts' => $counts,
                'persentase_hadir' => $persentase,
            ];
        }

        return view('siswa.absensi.index', compact('dataPerMapel'));
    }
}