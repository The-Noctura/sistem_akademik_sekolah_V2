<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Mapel::query();
        
        if ($status === 'aktif' || $status === 'nonaktif') {
            $query->where('status', $status);
        }
        
        $mapel = $query->latest()->paginate(15)->withQueryString();
        return view('admin.mapel.index', compact('mapel', 'status'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Mapel::create($validated);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $mapel->update($validated);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil diupdate.');
    }

    public function destroy(Mapel $mapel)
    {
        $newStatus = $mapel->status === 'aktif' ? 'nonaktif' : 'aktif';
        $mapel->update(['status' => $newStatus]);
        $pesan = $newStatus === 'nonaktif' ? 'Mata pelajaran dinonaktifkan.' : 'Mata pelajaran diaktifkan kembali.';
        return redirect()->route('admin.mapel.index')->with('success', $pesan);
    }
}