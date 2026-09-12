<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Mengajar;
use App\Models\Jadwal;
use App\Models\RekapNilai;
use App\Models\RekapAbsensi;
use App\Models\TefaProduct;
use App\Models\Berita;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $role = $user->role;

        if ($role === 'admin') {
            $tefaCount = Schema::hasTable('tefa_products') ? TefaProduct::count() : 0;
            $beritaCount = Schema::hasTable('berita') ? Berita::count() : 0;

            $stats = [
                'guru_count' => Guru::count(),
                'siswa_count' => Siswa::count(),
                'kelas_count' => Kelas::count(),
                'mapel_count' => Mapel::count(),
                'tefa_count' => $tefaCount,
                'berita_count' => $beritaCount,
            ];

            $recentLogs = [];
            if (Schema::hasTable('log_perubahan')) {
                $recentLogs = DB::table('log_perubahan')
                    ->join('users', 'log_perubahan.user_id', '=', 'users.id')
                    ->select('log_perubahan.*', 'users.nama as user_nama')
                    ->latest('waktu')
                    ->take(6)
                    ->get();
            }

            $recentNews = Schema::hasTable('berita') ? Berita::latest()->take(3)->get() : collect();

            return view('admin.dashboard', compact('stats', 'recentLogs', 'recentNews'));
        }

        if ($role === 'guru') {
            $guru = $user->guru;
            $mengajarIds = $guru ? $guru->mengajar()->pluck('id') : collect();

            $stats = [
                'kelas_count' => $mengajarIds->count() ? Mengajar::whereIn('id', $mengajarIds)->distinct('kelas_id')->count('kelas_id') : 0,
                'mapel_count' => $mengajarIds->count() ? Mengajar::whereIn('id', $mengajarIds)->distinct('mapel_id')->count('mapel_id') : 0,
                'siswa_count' => $mengajarIds->count() ? Siswa::whereIn('kelas_id', Mengajar::whereIn('id', $mengajarIds)->pluck('kelas_id'))->count() : 0,
            ];

            $dayMap = [
                1 => 'senin',
                2 => 'selasa',
                3 => 'rabu',
                4 => 'kamis',
                5 => 'jumat',
                6 => 'sabtu',
                7 => 'minggu',
            ];
            $currentDayName = $dayMap[date('N')] ?? 'senin';

            $jadwalHariIni = collect();
            if ($guru && $currentDayName !== 'minggu') {
                $jadwalHariIni = Jadwal::whereHas('mengajar', fn($q) => $q->where('guru_id', $guru->id))
                    ->where('hari', $currentDayName)
                    ->with(['mengajar.mapel', 'mengajar.kelas'])
                    ->orderBy('jam_mulai')
                    ->get();
            }

            $kelasDiampu = $guru ? Mengajar::where('guru_id', $guru->id)
                ->with(['kelas', 'mapel'])
                ->get() : collect();

            return view('guru.dashboard', compact('stats', 'jadwalHariIni', 'currentDayName', 'kelasDiampu'));
        }

        if ($role === 'siswa') {
            $siswa = $user->siswa;
            $mengajarIds = $siswa && $siswa->kelas_id ? Mengajar::where('kelas_id', $siswa->kelas_id)->pluck('id') : collect();

            $avgNilai = '-';
            $persenHadir = '-';
            $mapelCount = 0;

            if ($siswa && $mengajarIds->count()) {
                $mapelCount = $mengajarIds->count();
                $rekapNilai = RekapNilai::where('siswa_id', $siswa->id)->whereIn('mengajar_id', $mengajarIds)->avg('rata_rata');
                if ($rekapNilai !== null) {
                    $avgNilai = number_format($rekapNilai, 2);
                }

                $rekapAbsensi = RekapAbsensi::where('siswa_id', $siswa->id)->whereIn('mengajar_id', $mengajarIds)->avg('persentase_hadir');
                if ($rekapAbsensi !== null) {
                    $persenHadir = number_format($rekapAbsensi, 1);
                }
            }

            $stats = [
                'mapel_count' => $mapelCount,
                'avg_nilai' => $avgNilai,
                'persen_hadir' => $persenHadir,
            ];

            return view('siswa.dashboard', compact('stats'));
        }

        abort(403);
    }
}
