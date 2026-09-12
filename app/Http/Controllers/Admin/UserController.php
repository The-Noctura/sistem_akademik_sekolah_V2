<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['guru', 'siswa.kelas'])->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $kelasList = Kelas::pluck('nama_kelas', 'id');
        return view('admin.users.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,guru,siswa',
            // Guru validations
            'nip' => 'required_if:role,guru|nullable|string|max:50|unique:guru,nip',
            'no_hp' => 'nullable|string|max:20',
            // Siswa validations
            'nis' => 'required_if:role,siswa|nullable|string|max:50|unique:siswa,nis',
            'kelas_id' => 'required_if:role,siswa|nullable|exists:kelas,id',
            'jenis_kelamin' => 'required_if:role,siswa|nullable|in:L,P',
            'tanggal_lahir' => 'required_if:role,siswa|nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'aktif',
            ]);

            if ($request->role === 'guru') {
                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                ]);
            } elseif ($request->role === 'siswa') {
                Siswa::create([
                    'user_id' => $user->id,
                    'nis' => $request->nis,
                    'nama' => $request->nama,
                    'kelas_id' => $request->kelas_id,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tanggal_lahir' => $request->tanggal_lahir,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal membuat user: ' . $e->getMessage()]);
        }
    }

    public function edit(User $user)
    {
        $user->load(['guru', 'siswa']);
        $kelasList = Kelas::pluck('nama_kelas', 'id');
        return view('admin.users.edit', compact('user', 'kelasList'));
    }

    public function update(Request $request, User $user)
    {
        $guruId = $user->guru?->id;
        $siswaId = $user->siswa?->id;

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,guru,siswa',
            'status' => 'required|in:aktif,nonaktif',
            // Guru validations
            'nip' => [
                'required_if:role,guru',
                'nullable',
                'string',
                'max:50',
                Rule::unique('guru', 'nip')->ignore($guruId),
            ],
            'no_hp' => 'nullable|string|max:20',
            // Siswa validations
            'nis' => [
                'required_if:role,siswa',
                'nullable',
                'string',
                'max:50',
                Rule::unique('siswa', 'nis')->ignore($siswaId),
            ],
            'kelas_id' => 'required_if:role,siswa|nullable|exists:kelas,id',
            'jenis_kelamin' => 'required_if:role,siswa|nullable|in:L,P',
            'tanggal_lahir' => 'required_if:role,siswa|nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($request->role === 'guru') {
                Guru::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                        'nama' => $request->nama,
                        'no_hp' => $request->no_hp,
                    ]
                );
            } elseif ($request->role === 'siswa') {
                Siswa::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nis' => $request->nis,
                        'nama' => $request->nama,
                        'kelas_id' => $request->kelas_id,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'tanggal_lahir' => $request->tanggal_lahir,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal mengupdate user: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        $newStatus = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $newStatus]);
        $pesan = $newStatus === 'nonaktif' ? 'User berhasil dinonaktifkan.' : 'User berhasil diaktifkan kembali.';
        return redirect()->route('admin.users.index')->with('success', $pesan);
    }
}