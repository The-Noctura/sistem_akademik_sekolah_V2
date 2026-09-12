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
        $guru = Auth::user()?->guru;

        if (!$guru) {
            abort(403, 'Data guru belum tersedia.');
        }

        $mengajarList = Mengajar::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.absensi.index', compact('mengajarList'));
    }

    public function form($mengajarId, Request $request)
    {
        $mengajar = Mengajar::with(['mapel', 'kelas.siswa'])->findOrFail($mengajarId);

        $guru = Auth::user()?->guru;
        if (!$guru || $mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        $siswaList = Siswa::where('kelas_id', $mengajar->kelas_id)
            ->orderBy('nama')
            ->get();

        $tanggal = $request->query('tanggal', date('Y-m-d'));

        $existingAbsensi = Absensi::where('mengajar_id', $mengajarId)
            ->where('tanggal', $tanggal)
            ->pluck('status', 'siswa_id')
            ->toArray();

        return view('guru.absensi.form', compact('mengajar', 'siswaList', 'tanggal', 'existingAbsensi'));
    }

    public function store(Request $request, $mengajarId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        $mengajar = Mengajar::findOrFail($mengajarId);

        $guru = Auth::user()?->guru;
        if (!$guru || $mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        foreach (array_keys($request->status) as $siswaId) {
            $siswa = Siswa::find($siswaId);
            if (!$siswa || $siswa->kelas_id !== $mengajar->kelas_id) {
                return back()->withErrors(['error' => 'Ada siswa yang tidak sesuai kelas.']);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($request->status as $siswaId => $status) {
                Absensi::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'mengajar_id' => $mengajarId,
                        'tanggal' => $request->tanggal,
                    ],
                    [
                        'status' => $status,
                    ]
                );

                // Manual call sp_rekap_absensi diperlukan karena:
                // - Trigger trg_absensi_insert hanya AFTER INSERT, tidak ada AFTER UPDATE
                // - updateOrCreate() bisa hasil UPDATE (edit absensi existing) → trigger tidak jalan
                // - Prosedur idempoten (SELECT ulang + INSERT ... ON DUPLICATE KEY UPDATE) → panggilan ganda aman
                DB::statement('CALL sp_rekap_absensi(?, ?, ?)', [
                    $siswaId,
                    $mengajarId,
                    $mengajar->semester,
                ]);
            }

            DB::commit();

            return redirect()->route('guru.absensi.history', $mengajarId)
                ->with('success', 'Presensi tanggal ' . date('d/m/Y', strtotime($request->tanggal)) . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal simpan absensi: ' . $e->getMessage()]);
        }
    }

    public function history($mengajarId)
    {
        $mengajar = Mengajar::with(['mapel', 'kelas'])->findOrFail($mengajarId);

        $guru = Auth::user()?->guru;
        if (!$guru || $mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        // Riwayat absensi per tanggal dengan ringkasan
        $history = Absensi::where('mengajar_id', $mengajarId)
            ->select(
                'tanggal',
                DB::raw("SUM(status = 'hadir') as hadir"),
                DB::raw("SUM(status = 'izin') as izin"),
                DB::raw("SUM(status = 'sakit') as sakit"),
                DB::raw("SUM(status = 'alpa') as alpa"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('guru.absensi.history', compact('mengajar', 'history'));
    }

    public function destroyDate($mengajarId, $tanggal)
    {
        $mengajar = Mengajar::findOrFail($mengajarId);

        $guru = Auth::user()?->guru;
        if (!$guru || $mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        $siswaIds = Absensi::where('mengajar_id', $mengajarId)
            ->where('tanggal', $tanggal)
            ->pluck('siswa_id')
            ->unique();

        DB::beginTransaction();
        try {
            Absensi::where('mengajar_id', $mengajarId)
                ->where('tanggal', $tanggal)
                ->delete();

            // Hitung ulang rekap absensi untuk siswa terkait
            // Manual call sp_rekap_absensi WAJIB karena tidak ada trigger AFTER DELETE pada tabel absensi
            foreach ($siswaIds as $siswaId) {
                DB::statement('CALL sp_rekap_absensi(?, ?, ?)', [
                    $siswaId,
                    $mengajarId,
                    $mengajar->semester
                ]);
            }

            DB::commit();
            return redirect()->route('guru.absensi.history', $mengajarId)
                ->with('success', 'Data presensi tanggal ' . date('d/m/Y', strtotime($tanggal)) . ' berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus absensi: ' . $e->getMessage()]);
        }
    }
}