<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mengajar;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        $mengajarIds = Mengajar::where('guru_id', $guru->id)->pluck('id');

        $jadwal = Jadwal::with(['mengajar.mapel', 'mengajar.kelas'])
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

        return view('guru.jadwal.index', compact('jadwal'));
    }
}