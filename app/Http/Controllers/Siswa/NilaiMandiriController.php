<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiMandiriSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiMandiriController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()?->siswa;

        if (!$siswa) {
            return view('siswa.nilai-mandiri.index', ['dataBySemester' => [], 'siswa' => null]);
        }

        $nilaiMandiri = NilaiMandiriSiswa::where('siswa_id', $siswa->id)
            ->orderBy('semester', 'asc')
            ->orderBy('nama_mapel', 'asc')
            ->get();

        $dataBySemester = [];

        foreach ($nilaiMandiri as $nilai) {
            if (!isset($dataBySemester[$nilai->semester])) {
                $dataBySemester[$nilai->semester] = [];
            }
            $dataBySemester[$nilai->semester][] = [
                'id' => $nilai->id,
                'nama_mapel' => $nilai->nama_mapel,
                'nilai' => $nilai->nilai,
            ];
        }

        return view('siswa.nilai-mandiri.index', compact('dataBySemester', 'siswa'));
    }

    public function create()
    {
        $siswa = Auth::user()?->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa belum tersedia.');
        }

        return view('siswa.nilai-mandiri.create');
    }

    public function store(Request $request)
    {
        $siswa = Auth::user()?->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa belum tersedia.');
        }

        $request->validate([
            'semester' => 'required|in:1,2,3,4,5',
            'nama_mapel' => 'required|array|min:1',
            'nama_mapel.*' => 'required|string|max:100',
            'nilai' => 'required|array|min:1',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ], [
            'nama_mapel.required' => 'Minimal 1 mata pelajaran harus diisi.',
            'nilai.required' => 'Minimal 1 nilai harus diisi.',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->nama_mapel as $key => $namaMapel) {
                $nilaiInput = $request->nilai[$key] ?? null;

                if ($namaMapel && $nilaiInput !== null) {
                    $exist = NilaiMandiriSiswa::where('siswa_id', $siswa->id)
                        ->where('nama_mapel', $namaMapel)
                        ->where('semester', $request->semester)
                        ->first();

                    if ($exist) {
                        $exist->update(['nilai' => $nilaiInput]);
                    } else {
                        NilaiMandiriSiswa::create([
                            'siswa_id' => $siswa->id,
                            'nama_mapel' => $namaMapel,
                            'semester' => $request->semester,
                            'nilai' => $nilaiInput,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('siswa.nilai-mandiri.index')->with('success', 'Nilai raport berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal simpan nilai: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $siswa = Auth::user()?->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa belum tersedia.');
        }

        $nilai = NilaiMandiriSiswa::where('siswa_id', $siswa->id)->findOrFail($id);

        return view('siswa.nilai-mandiri.edit', compact('nilai'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Auth::user()?->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa belum tersedia.');
        }

        $nilai = NilaiMandiriSiswa::where('siswa_id', $siswa->id)->findOrFail($id);

        $request->validate([
            'nama_mapel' => 'required|string|max:100',
            'semester' => 'required|in:1,2,3,4,5',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $nilai->update($request->only(['nama_mapel', 'semester', 'nilai']));

        return redirect()->route('siswa.nilai-mandiri.index')->with('success', 'Nilai raport berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Auth::user()?->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa belum tersedia.');
        }

        $nilai = NilaiMandiriSiswa::where('siswa_id', $siswa->id)->findOrFail($id);
        $nilai->delete();

        return redirect()->route('siswa.nilai-mandiri.index')->with('success', 'Nilai raport berhasil dihapus.');
    }
}
