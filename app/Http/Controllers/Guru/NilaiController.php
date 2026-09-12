<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Mengajar;
use App\Models\Nilai;
use App\Models\RekapNilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index()
    {
        $guru = Auth::user()?->guru;

        if (!$guru) {
            abort(403, 'Data guru belum tersedia.');
        }

        $mengajarList = Mengajar::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.nilai.index', compact('mengajarList'));
    }

    public function form($mengajarId, Request $request)
    {
        $mengajar = Mengajar::with(['mapel', 'kelas.siswa'])->findOrFail($mengajarId);

        $guru = Auth::user()?->guru;
        if (!$guru || $mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        $siswaList = Siswa::where('kelas_id', $mengajar->kelas_id)->orderBy('nama')->get();

        $jenis = $request->query('jenis', 'tugas');

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

        $rekapNilai = RekapNilai::where('mengajar_id', $mengajarId)
            ->where('semester', $mengajar->semester)
            ->pluck('rata_rata', 'siswa_id')
            ->toArray();

        return view('guru.nilai.form', compact(
            'mengajar',
            'siswaList',
            'nilaiTugas',
            'nilaiUts',
            'nilaiUas',
            'rekapNilai',
            'jenis'
        ));
    }

    public function store(Request $request, $mengajarId)
    {
        $request->validate([
            'jenis' => 'required|in:tugas,uts,uas',
            'nilai' => 'nullable|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $nilaiInput = collect($request->input('nilai', []))
            ->filter(fn($value) => $value !== null && trim((string) $value) !== '')
            ->all();

        if (empty($nilaiInput)) {
            return back()->withErrors(['error' => 'Belum ada nilai yang diisi. Masukkan angka 0–100 lalu klik Simpan.']);
        }

        $mengajar = Mengajar::findOrFail($mengajarId);

        $guru = Auth::user()?->guru;
        if (!$guru || $mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        foreach (array_keys($nilaiInput) as $siswaId) {
            $siswa = Siswa::find($siswaId);
            if (!$siswa || $siswa->kelas_id !== $mengajar->kelas_id) {
                return back()->withErrors(['error' => 'Ada siswa yang tidak sesuai kelas.']);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($nilaiInput as $siswaId => $nilai) {
                DB::statement('CALL sp_input_nilai_kelas(?, ?, ?, ?, ?)', [
                    $mengajarId,
                    $request->jenis,
                    $siswaId,
                    $nilai,
                    Auth::id(),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Nilai ' . strtoupper($request->jenis) . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal simpan nilai: ' . $e->getMessage()]);
        }
    }

    public function destroyNilai($mengajarId, $siswaId, $jenis)
    {
        $mengajar = Mengajar::findOrFail($mengajarId);

        $guru = Auth::user()?->guru;
        if (!$guru || $mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        DB::beginTransaction();
        try {
            Nilai::where('mengajar_id', $mengajarId)
                ->where('siswa_id', $siswaId)
                ->where('jenis', $jenis)
                ->delete();

            // Hitung ulang rata-rata via Function MySQL
            $rata = DB::selectOne("SELECT fn_rata_rata_nilai(?, ?) as rata", [$siswaId, $mengajarId])->rata ?? 0;

            DB::table('rekap_nilai')->updateOrInsert(
                [
                    'siswa_id' => $siswaId,
                    'mengajar_id' => $mengajarId,
                    'semester' => $mengajar->semester,
                ],
                [
                    'rata_rata' => $rata,
                    'updated_at' => now(),
                ]
            );

            DB::commit();
            return back()->with('success', 'Nilai ' . strtoupper($jenis) . ' berhasil direset.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mereset nilai: ' . $e->getMessage()]);
        }
    }
}