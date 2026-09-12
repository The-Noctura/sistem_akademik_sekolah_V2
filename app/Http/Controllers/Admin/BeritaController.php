<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');
        $query = Berita::with('penulis');

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $beritaList = $query->latest()->paginate(10)->withQueryString();
        $kategoriList = ['Prestasi', 'PPDB', 'Kerjasama', 'Akademik', 'Kegiatan', 'Umum'];

        return view('admin.berita.index', compact('beritaList', 'kategoriList', 'kategori'));
    }

    public function create()
    {
        $kategoriList = ['Prestasi', 'PPDB', 'Kerjasama', 'Akademik', 'Kegiatan', 'Umum'];
        return view('admin.berita.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'kategori'       => 'required|string|max:50',
            'thumbnail_file' => 'nullable|image|max:2048',
            'thumbnail_url'  => 'nullable|url|max:500',
            'ringkasan'      => 'nullable|string|max:500',
            'konten'         => 'required|string',
            'status'         => 'required|in:publikasi,draft',
        ]);

        $thumbnail = $request->thumbnail_url;
        if ($request->hasFile('thumbnail_file')) {
            $thumbnail = $request->file('thumbnail_file')->store('berita', 'public');
        }

        $slug = Berita::generateUniqueSlug($request->judul);

        Berita::create([
            'judul'      => $request->judul,
            'slug'       => $slug,
            'kategori'   => $request->kategori,
            'thumbnail'  => $thumbnail,
            'ringkasan'  => $request->ringkasan ?? Str::limit(strip_tags($request->konten), 160),
            'konten'     => $request->konten,
            'status'     => $request->status,
            'penulis_id' => Auth::id(),
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dipublikasikan.');
    }

    public function edit(Berita $berita)
    {
        $kategoriList = ['Prestasi', 'PPDB', 'Kerjasama', 'Akademik', 'Kegiatan', 'Umum'];
        return view('admin.berita.edit', compact('berita', 'kategoriList'));
    }

    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'kategori'       => 'required|string|max:50',
            'thumbnail_file' => 'nullable|image|max:2048',
            'thumbnail_url'  => 'nullable|url|max:500',
            'ringkasan'      => 'nullable|string|max:500',
            'konten'         => 'required|string',
            'status'         => 'required|in:publikasi,draft',
        ]);

        $thumbnail = $berita->thumbnail;
        if ($request->hasFile('thumbnail_file')) {
            $thumbnail = $request->file('thumbnail_file')->store('berita', 'public');
        } elseif ($request->filled('thumbnail_url')) {
            $thumbnail = $request->thumbnail_url;
        }

        $slug = $berita->slug;
        if ($request->judul !== $berita->judul) {
            $slug = Berita::generateUniqueSlug($request->judul, $berita->id);
        }

        $berita->update([
            'judul'      => $request->judul,
            'slug'       => $slug,
            'kategori'   => $request->kategori,
            'thumbnail'  => $thumbnail,
            'ringkasan'  => $request->ringkasan ?? Str::limit(strip_tags($request->konten), 160),
            'konten'     => $request->konten,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}

