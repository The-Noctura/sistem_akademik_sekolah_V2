<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mengajar;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;

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
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ]);

        Mengajar::create($request->all());

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
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ]);

        $mengajar->update($request->all());

        return redirect()->route('admin.mengajar.index')->with('success', 'Data mengajar berhasil diupdate.');
    }

    public function destroy(Mengajar $mengajar)
    {
        $mengajar->delete();
        return redirect()->route('admin.mengajar.index')->with('success', 'Data mengajar berhasil dihapus.');
    }
}