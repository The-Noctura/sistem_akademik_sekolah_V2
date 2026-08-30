<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mengajar;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::with(['mengajar.guru', 'mengajar.mapel', 'mengajar.kelas'])->latest()->paginate(15);
        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $mengajarList = Mengajar::with(['guru', 'mapel', 'kelas'])->get()
            ->mapWithKeys(fn($m) => [$m->id => "{$m->guru->nama} - {$m->mapel->nama_mapel} - {$m->kelas->nama_kelas} ({$m->semester})"]);
        return view('admin.jadwal.create', compact('mengajarList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mengajar_id' => 'required|exists:mengajar,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:255',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $mengajarList = Mengajar::with(['guru', 'mapel', 'kelas'])->get()
            ->mapWithKeys(fn($m) => [$m->id => "{$m->guru->nama} - {$m->mapel->nama_mapel} - {$m->kelas->nama_kelas} ({$m->semester})"]);
        return view('admin.jadwal.edit', compact('jadwal', 'mengajarList'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'mengajar_id' => 'required|exists:mengajar,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:255',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diupdate.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}