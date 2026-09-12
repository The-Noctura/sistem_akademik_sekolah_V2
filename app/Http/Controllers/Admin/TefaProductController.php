<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TefaProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TefaProductController extends Controller
{
    public function index(Request $request)
    {
        $jurusan = $request->query('jurusan');
        $query = TefaProduct::query();

        if ($jurusan) {
            $query->where('jurusan_code', $jurusan);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $jurusanList = TefaProduct::$jurusanMap;

        return view('admin.tefa.index', compact('products', 'jurusanList', 'jurusan'));
    }

    public function create()
    {
        $jurusanList = TefaProduct::$jurusanMap;
        return view('admin.tefa.create', compact('jurusanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'jurusan_code' => 'required|string|max:20',
            'kategori'     => 'required|in:barang,jasa',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'foto_file'    => 'nullable|image|max:2048',
            'foto_url'     => 'nullable|url|max:500',
            'status_stok'  => 'required|in:tersedia,habis',
            'nomor_wa'     => 'required|string|max:25',
        ]);

        $foto = $request->foto_url;
        if ($request->hasFile('foto_file')) {
            $foto = $request->file('foto_file')->store('tefa_products', 'public');
        }

        TefaProduct::create([
            'nama_produk'  => $request->nama_produk,
            'jurusan_code' => $request->jurusan_code,
            'kategori'     => $request->kategori,
            'harga'        => $request->harga,
            'deskripsi'    => $request->deskripsi,
            'foto'         => $foto,
            'status_stok'  => $request->status_stok,
            'nomor_wa'     => $request->nomor_wa,
        ]);

        return redirect()->route('admin.tefa.index')->with('success', 'Produk TEFA berhasil ditambahkan.');
    }

    public function edit(TefaProduct $tefa)
    {
        $jurusanList = TefaProduct::$jurusanMap;
        return view('admin.tefa.edit', compact('tefa', 'jurusanList'));
    }

    public function update(Request $request, TefaProduct $tefa)
    {
        $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'jurusan_code' => 'required|string|max:20',
            'kategori'     => 'required|in:barang,jasa',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'foto_file'    => 'nullable|image|max:2048',
            'foto_url'     => 'nullable|url|max:500',
            'status_stok'  => 'required|in:tersedia,habis',
            'nomor_wa'     => 'required|string|max:25',
        ]);

        $foto = $tefa->foto;
        if ($request->hasFile('foto_file')) {
            $foto = $request->file('foto_file')->store('tefa_products', 'public');
        } elseif ($request->filled('foto_url')) {
            $foto = $request->foto_url;
        }

        $tefa->update([
            'nama_produk'  => $request->nama_produk,
            'jurusan_code' => $request->jurusan_code,
            'kategori'     => $request->kategori,
            'harga'        => $request->harga,
            'deskripsi'    => $request->deskripsi,
            'foto'         => $foto,
            'status_stok'  => $request->status_stok,
            'nomor_wa'     => $request->nomor_wa,
        ]);

        return redirect()->route('admin.tefa.index')->with('success', 'Produk TEFA berhasil diperbarui.');
    }

    public function destroy(TefaProduct $tefa)
    {
        $tefa->delete();
        return redirect()->route('admin.tefa.index')->with('success', 'Produk TEFA berhasil dihapus.');
    }
}

