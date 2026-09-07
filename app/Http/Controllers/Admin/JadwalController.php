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

        if ($response = $this->checkScheduleConflict($request, null)) {
            return $response;
        }

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

        if ($response = $this->checkScheduleConflict($request, $jadwal->id)) {
            return $response;
        }

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diupdate.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    private function checkScheduleConflict(Request $request, ?int $ignoreId = null)
    {
        $mengajar = Mengajar::findOrFail($request->mengajar_id);
        $guruId = $mengajar->guru_id;

        // Cek bentrok guru
        $guruConflict = Jadwal::whereHas('mengajar', fn($q) => $q->where('guru_id', $guruId))
            ->where('hari', $request->hari)
            ->where(function ($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($guruConflict) {
            return back()->withErrors([
                'error' => 'Guru sudah memiliki jadwal lain yang bentrok di hari dan jam ini.'
            ])->withInput();
        }

        // Cek bentrok ruangan
        $ruanganConflict = Jadwal::where('ruangan', $request->ruangan)
            ->where('hari', $request->hari)
            ->where(function ($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($ruanganConflict) {
            return back()->withErrors([
                'error' => 'Ruangan sudah digunakan oleh jadwal lain yang bentrok di hari dan jam ini.'
            ])->withInput();
        }
    }
}