<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Mengajar;
use App\Models\RekapNilai;
use App\Models\RekapAbsensi;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            $stats = [
                'guru_count' => Guru::count(),
                'siswa_count' => Siswa::count(),
                'kelas_count' => Kelas::count(),
                'mapel_count' => Mapel::count(),
            ];
            return view('admin.dashboard', compact('stats'));
        }

        if ($role === 'guru') {
            $guru = Auth::user()->guru;
            $mengajarIds = $guru ? $guru->mengajar->pluck('id') : collect();
            $stats = [
                'kelas_count' => $mengajarIds->count() ? Mengajar::whereIn('id', $mengajarIds)->distinct('kelas_id')->count('kelas_id') : 0,
                'mapel_count' => $mengajarIds->count() ? Mengajar::whereIn('id', $mengajarIds)->distinct('mapel_id')->count('mapel_id') : 0,
                'siswa_count' => $mengajarIds->count() ? Siswa::whereIn('kelas_id', Mengajar::whereIn('id', $mengajarIds)->pluck('kelas_id'))->count() : 0,
            ];
            return view('guru.dashboard', compact('stats'));
        }

        if ($role === 'siswa') {
            $siswa = Auth::user()->siswa;
            $mengajarIds = $siswa && $siswa->kelas_id ? Mengajar::where('kelas_id', $siswa->kelas_id)->pluck('id') : collect();
            $avgNilai = '-'; $persenHadir = '-'; $mapelCount = 0;
            if ($mengajarIds->count()) {
                $mapelCount = $mengajarIds->count();
                $rekapNilai = RekapNilai::where('siswa_id', $siswa->id)->whereIn('mengajar_id', $mengajarIds)->avg('rata_rata');
                if ($rekapNilai) $avgNilai = number_format($rekapNilai, 2);
                $rekapAbsensi = RekapAbsensi::where('siswa_id', $siswa->id)->whereIn('mengajar_id', $mengajarIds)->avg('persentase_hadir');
                if ($rekapAbsensi) $persenHadir = number_format($rekapAbsensi, 1);
            }
            $stats = ['mapel_count'=>$mapelCount,'avg_nilai'=>$avgNilai,'persen_hadir'=>$persenHadir];
            return view('siswa.dashboard', compact('stats'));
        }

        return abort(403);
    }
}
