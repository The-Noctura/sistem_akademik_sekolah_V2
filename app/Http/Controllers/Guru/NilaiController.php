<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Mengajar;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        $mengajarList = Mengajar::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.nilai.index', compact('mengajarList'));
    }

    public function form($mengajarId)
    {
        $mengajar = Mengajar::with(['mapel', 'kelas.siswa'])->findOrFail($mengajarId);

        $guru = Auth::user()->guru;
        if ($mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        $siswaList = Siswa::where('kelas_id', $mengajar->kelas_id)->get();

        $nilaiTugas = Nilai::where('mengajar_id', $mengajarId)
            ->where('jenis', 'tugas')
            ->pluck('nilai', 'siswa_id')
            ->toArray();

        $nilaiUts = Nilai::where('mengajar_id', $mengajarId)
            ->where('jenis', 'uts')
            ->pluck('nilai', 'siswa_id')
            ->toArray();

        $nilaiUas = Nilai::where('mengajar_id', $mengajarId)
            ->where('jenis', 'uas')
            ->pluck('nilai', 'siswa_id')
            ->toArray();

        return view('guru.nilai.form', compact(
            'mengajar',
            'siswaList',
            'nilaiTugas',
            'nilaiUts',
            'nilaiUas'
        ));
    }

    public function store(Request $request, $mengajarId)
    {
        $request->validate([
            'jenis' => 'required|in:tugas,uts,uas',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ]);

        $mengajar = Mengajar::findOrFail($mengajarId);

        $guru = Auth::user()->guru;
        if ($mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        // WAJIB: cross-check siswa-kelas SEBELUM masuk transaction
        foreach (array_keys($request->nilai) as $siswaId) {
            $siswa = Siswa::find($siswaId);
            if (!$siswa || $siswa->kelas_id !== $mengajar->kelas_id) {
                return back()->withErrors(['error' => 'Ada siswa yang tidak sesuai kelas.']);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($request->nilai as $siswaId => $nilai) {
                DB::statement('CALL sp_input_nilai_kelas(?, ?, ?, ?, ?)', [
                    $mengajarId, $request->jenis, $siswaId, $nilai, Auth::id()
                ]);
            }
            DB::commit();
            return back()->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal simpan nilai: ' . $e->getMessage()]);
        }
    }
}