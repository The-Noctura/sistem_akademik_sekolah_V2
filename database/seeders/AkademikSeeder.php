<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Mengajar;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin user + guru
        $adminUser = User::create([
            'nama' => 'Admin Sekolah',
            'email' => 'admin@sekolah.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $adminGuru = Guru::create([
            'user_id' => $adminUser->id,
            'nip' => '197001011995031001',
            'nama' => 'Admin Sekolah',
            'no_hp' => '081234567890',
        ]);

        // 2. 2 Guru users + guru records
        $guru1User = User::create([
            'nama' => 'Budi Santoso, S.Pd',
            'email' => 'budi@sekolah.test',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        $guru1 = Guru::create([
            'user_id' => $guru1User->id,
            'nip' => '198002021998031002',
            'nama' => 'Budi Santoso, S.Pd',
            'no_hp' => '081234567891',
        ]);

        $guru2User = User::create([
            'nama' => 'Siti Rahayu, S.Pd',
            'email' => 'siti@sekolah.test',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        $guru2 = Guru::create([
            'user_id' => $guru2User->id,
            'nip' => '198503031999032003',
            'nama' => 'Siti Rahayu, S.Pd',
            'no_hp' => '081234567892',
        ]);

        // 3. 2 Mapel
        $mapel1 = Mapel::create([
            'nama_mapel' => 'Matematika',
            'kode_mapel' => 'MTK',
        ]);

        $mapel2 = Mapel::create([
            'nama_mapel' => 'Bahasa Indonesia',
            'kode_mapel' => 'BIN',
        ]);

        // 4. 1 Kelas
        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 'X',
            'wali_kelas_id' => $guru1->id,
            'tahun_ajaran' => '2024/2025',
        ]);

        // 5. 8-10 Siswa users + siswa records
        $siswaData = [
            ['nis' => '1001', 'nama' => 'Ahmad Fauzi', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2008-01-15'],
            ['nis' => '1002', 'nama' => 'Bella Putri', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2008-02-20'],
            ['nis' => '1003', 'nama' => 'Citra Dewi', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2008-03-10'],
            ['nis' => '1004', 'nama' => 'Deni Pratama', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2008-04-05'],
            ['nis' => '1005', 'nama' => 'Eka Sari', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2008-05-12'],
            ['nis' => '1006', 'nama' => 'Fajar Nugroho', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2008-06-18'],
            ['nis' => '1007', 'nama' => 'Gita Lestari', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2008-07-22'],
            ['nis' => '1008', 'nama' => 'Hadi Susanto', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2008-08-30'],
            ['nis' => '1009', 'nama' => 'Intan Permata', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2008-09-14'],
            ['nis' => '1010', 'nama' => 'Joko Widodo', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2008-10-25'],
        ];

        foreach ($siswaData as $index => $data) {
            $siswaUser = User::create([
                'nama' => $data['nama'],
                'email' => "siswa{$data['nis']}@sekolah.test",
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $siswaUser->id,
                'nis' => $data['nis'],
                'nama' => $data['nama'],
                'kelas_id' => $kelas->id,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'],
            ]);
        }

        // 6. Mengajar records
        Mengajar::create([
            'guru_id' => $guru1->id,
            'mapel_id' => $mapel1->id,
            'kelas_id' => $kelas->id,
            'tahun_ajaran' => '2024/2025',
            'semester' => 'ganjil',
        ]);

        Mengajar::create([
            'guru_id' => $guru2->id,
            'mapel_id' => $mapel2->id,
            'kelas_id' => $kelas->id,
            'tahun_ajaran' => '2024/2025',
            'semester' => 'ganjil',
        ]);

        // Output credentials for testing
        $this->command->info('=== SEEDER BERHASIL ===');
        $this->command->info('Admin: admin@sekolah.test / password');
        $this->command->info('Guru 1: budi@sekolah.test / password (Matematika)');
        $this->command->info('Guru 2: siti@sekolah.test / password (Bahasa Indonesia)');
        $this->command->info('Siswa: siswa1001@sekolah.test ... siswa1010@sekolah.test / password');
    }
}