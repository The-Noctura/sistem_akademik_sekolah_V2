<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mengajar;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $kelasId = $siswa->kelas_id;

        if (!$kelasId) {
            return view('siswa.jadwal.index', ['jadwal' => collect()]);
        }

        $mengajarIds = Mengajar::where('kelas_id', $kelasId)->pluck('id');

        $jadwal = Jadwal::with(['mengajar.mapel', 'mengajar.guru'])
            ->whereIn('mengajar_id', $mengajarIds)
            ->orderByRaw("CASE hari
                WHEN 'senin' THEN 1
                WHEN 'selasa' THEN 2
                WHEN 'rabu' THEN 3
                WHEN 'kamis' THEN 4
                WHEN 'jumat' THEN 5
                WHEN 'sabtu' THEN 6
                ELSE 7 END")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('siswa.jadwal.index', compact('jadwal'));
    }
}