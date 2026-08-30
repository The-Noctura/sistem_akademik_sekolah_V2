<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Mengajar;
use App\Models\Nilai;
use App\Models\RekapNilai;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $kelasId = $siswa->kelas_id;

        if (!$kelasId) {
            return view('siswa.nilai.index', ['dataPerMapel' => []]);
        }

        $mengajarList = Mengajar::with(['mapel'])
            ->where('kelas_id', $kelasId)
            ->get();

        $dataPerMapel = [];

        foreach ($mengajarList as $mengajar) {
            $nilaiTugas = Nilai::where('siswa_id', $siswa->id)
                ->where('mengajar_id', $mengajar->id)
                ->where('jenis', 'tugas')
                ->first();

            $nilaiUts = Nilai::where('siswa_id', $siswa->id)
                ->where('mengajar_id', $mengajar->id)
                ->where('jenis', 'uts')
                ->first();

            $nilaiUas = Nilai::where('siswa_id', $siswa->id)
                ->where('mengajar_id', $mengajar->id)
                ->where('jenis', 'uas')
                ->first();

            $rekap = RekapNilai::where('siswa_id', $siswa->id)
                ->where('mengajar_id', $mengajar->id)
                ->where('semester', $mengajar->semester)
                ->first();

            $rataRata = $rekap->rata_rata ?? null;

            if (!$rataRata) {
                $rataRata = DB::selectOne("SELECT fn_rata_rata_nilai(?, ?) as rata", [
                    $siswa->id, $mengajar->id
                ])->rata ?? 0;
            }

            $dataPerMapel[$mengajar->mapel->nama_mapel] = [
                'mengajar' => $mengajar,
                'nilai' => [
                    'tugas' => $nilaiTugas?->nilai,
                    'uts' => $nilaiUts?->nilai,
                    'uas' => $nilaiUas?->nilai,
                ],
                'rata_rata' => $rataRata,
                'nilai_akhir' => $rekap->nilai_akhir ?? null,
                'predikat' => $rekap->predikat ?? null,
            ];
        }

        return view('siswa.nilai.index', compact('dataPerMapel'));
    }
}