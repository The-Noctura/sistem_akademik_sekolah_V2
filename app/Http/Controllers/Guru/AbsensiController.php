<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mengajar;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        $mengajarList = Mengajar::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.absensi.index', compact('mengajarList'));
    }

    public function form($mengajarId)
    {
        $mengajar = Mengajar::with(['mapel', 'kelas.siswa'])->findOrFail($mengajarId);

        $guru = Auth::user()->guru;
        if ($mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        $siswaList = Siswa::where('kelas_id', $mengajar->kelas_id)->get();

        return view('guru.absensi.form', compact('mengajar', 'siswaList'));
    }

    public function store(Request $request, $mengajarId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        $mengajar = Mengajar::findOrFail($mengajarId);

        $guru = Auth::user()->guru;
        if ($mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        DB::beginTransaction();
        try {
            foreach ($request->status as $siswaId => $status) {
                Absensi::create([
                    'siswa_id' => $siswaId,
                    'mengajar_id' => $mengajarId,
                    'tanggal' => $request->tanggal,
                    'status' => $status,
                ]);
            }
            DB::commit();
            return back()->with('success', 'Absensi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal simpan absensi: ' . $e->getMessage()]);
        }
    }
}