<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->latest()->paginate(15);
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guruList = Guru::pluck('nama', 'id');
        return view('admin.kelas.create', compact('guruList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:guru,id',
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $guruList = Guru::pluck('nama', 'id');
        return view('admin.kelas.edit', compact('kelas', 'guruList'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:guru,id',
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        $kelas->update($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}