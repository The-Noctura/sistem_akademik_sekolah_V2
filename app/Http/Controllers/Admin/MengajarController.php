<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mengajar;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MengajarController extends Controller
{
    public function index()
    {
        $mengajar = Mengajar::with(['guru', 'mapel', 'kelas'])->latest()->paginate(15);
        return view('admin.mengajar.index', compact('mengajar'));
    }

    public function create()
    {
        $guruList = Guru::pluck('nama', 'id');
        $mapelList = Mapel::pluck('nama_mapel', 'id');
        $kelasList = Kelas::pluck('nama_kelas', 'id');
        return view('admin.mengajar.create', compact('guruList', 'mapelList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => [
                'required',
                'exists:guru,id',
                Rule::unique('mengajar')
                    ->where(fn ($q) => $q->where('mapel_id', $request->mapel_id)
                                          ->where('kelas_id', $request->kelas_id)
                                          ->where('tahun_ajaran', $request->tahun_ajaran)
                                          ->where('semester', $request->semester)),
            ],
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ], [
            'guru_id.unique' => 'Kombinasi guru, mata pelajaran, kelas, tahun ajaran, dan semester sudah ada.',
        ]);

        Mengajar::create($validated);

        return redirect()->route('admin.mengajar.index')->with('success', 'Data mengajar berhasil ditambahkan.');
    }

    public function edit(Mengajar $mengajar)
    {
        $guruList = Guru::pluck('nama', 'id');
        $mapelList = Mapel::pluck('nama_mapel', 'id');
        $kelasList = Kelas::pluck('nama_kelas', 'id');
        return view('admin.mengajar.edit', compact('mengajar', 'guruList', 'mapelList', 'kelasList'));
    }

    public function update(Request $request, Mengajar $mengajar)
    {
        $validated = $request->validate([
            'guru_id' => [
                'required',
                'exists:guru,id',
                Rule::unique('mengajar')
                    ->where(fn ($q) => $q->where('mapel_id', $request->mapel_id)
                                          ->where('kelas_id', $request->kelas_id)
                                          ->where('tahun_ajaran', $request->tahun_ajaran)
                                          ->where('semester', $request->semester))
                    ->ignore($mengajar->id),
            ],
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ], [
            'guru_id.unique' => 'Kombinasi guru, mata pelajaran, kelas, tahun ajaran, dan semester sudah ada.',
        ]);

        $mengajar->update($validated);

        return redirect()->route('admin.mengajar.index')->with('success', 'Data mengajar berhasil diupdate.');
    }

    public function destroy(Mengajar $mengajar)
    {
        $mengajar->delete();
        return redirect()->route('admin.mengajar.index')->with('success', 'Data mengajar berhasil dihapus.');
    }
}