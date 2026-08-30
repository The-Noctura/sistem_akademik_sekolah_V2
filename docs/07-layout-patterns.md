# 07 — Layout Patterns

**Aturan keras:** setiap halaman yang kamu buat WAJIB pakai salah satu dari 4 kerangka di bawah ini, persis seperti tertulis. Bagian yang boleh kamu isi ditandai `{{-- SLOT: ... --}}`. JANGAN mengubah struktur wrapper, urutan section, atau class Tailwind di bagian kerangka — hanya isi bagian slot.

Alasan file ini ada: token warna dan komponen yang benar tidak menjamin tata letak yang konsisten. Tanpa kerangka eksplisit, tiap halaman bisa punya struktur wrapper berbeda-beda meski memakai komponen yang sama. 4 kerangka ini menutup celah itu.

---

## Pola 1 — Halaman List/Index

Dipakai untuk: semua halaman `index` yang menampilkan tabel data (Manajemen User, Kelas, Mapel, Mengajar, Jadwal admin, dashboard list kelas guru, dst).

```blade
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">{{-- SLOT: Judul halaman, misal "Manajemen Kelas" --}}</h1>
        {{-- SLOT: Tombol aksi utama di kanan atas, kosongkan jika read-only, contoh: --}}
        {{-- <x-button variant="primary" type="button" onclick="location.href='{{ route('admin.kelas.create') }}'">Tambah Kelas</x-button> --}}
    </div>

    {{-- SLOT: Filter/search bar, opsional — kosongkan kalau tidak perlu. Kalau ada, taruh di sini sebelum tabel: --}}
    {{-- <div class="mb-4 flex gap-3">...</div> --}}

    <x-table>
        <x-slot:head>
            <tr>
                {{-- SLOT: Header kolom tabel, contoh: --}}
                {{-- <th class="text-left px-4 py-3 font-medium">Nama</th> --}}
            </tr>
        </x-slot:head>
        {{-- SLOT: Loop baris data, contoh: --}}
        {{--
        @foreach($items as $item)
        <tr class="hover:bg-surface">
            <td class="px-4 py-3">{{ $item->nama }}</td>
            <td class="px-4 py-3 text-right">
                <x-button variant="secondary" type="button" onclick="location.href='...'">Edit</x-button>
            </td>
        </tr>
        @endforeach
        --}}
    </x-table>

    {{-- SLOT: Pagination, kalau data dari controller pakai ->paginate(), taruh di sini: --}}
    {{-- <div class="mt-4">{{ $items->links() }}</div> --}}
@endsection
```

**Jangan** taruh tombol aksi di bawah tabel — selalu di kanan atas sejajar judul. **Jangan** buat tabel lebih dari max-width container (`max-w-6xl` sudah diatur di `layouts.app`, jangan override).

---

## Pola 2 — Halaman Form (Create/Edit)

Dipakai untuk: semua halaman tambah/edit data (form User, Kelas, Mapel, Mengajar, Jadwal admin, form input Nilai, form input Absensi).

```blade
@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">{{-- SLOT: Judul halaman, misal "Tambah Kelas" atau "Input Nilai" --}}</h1>

    <x-card>
        <form method="POST" action="{{-- SLOT: route tujuan submit --}}">
            @csrf
            {{-- SLOT: tambahkan @method('PUT') di sini kalau ini form edit --}}

            {{-- SLOT: field-field form, contoh: --}}
            {{-- <x-form-input name="nama_kelas" label="Nama Kelas" :value="$kelas->nama_kelas ?? null" /> --}}
            {{-- <x-form-select name="wali_kelas_id" label="Wali Kelas" :options="$guruList" :selected="$kelas->wali_kelas_id ?? null" /> --}}

            {{-- Untuk form dengan TABEL siswa (input nilai/absensi per kelas), slot field di atas
                 diganti dengan <x-table> penuh — lihat contoh lengkap di
                 docs/06-build-sequence.md pada tahap yang relevan. Tombol submit di bawah
                 tetap sama posisinya. --}}

            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
                <x-button variant="primary" type="submit">{{-- SLOT: label tombol, misal "Simpan" --}}</x-button>
            </div>
        </form>
    </x-card>
@endsection
```

**Jangan** buat form mentok lebar penuh halaman — `<x-card>` sudah membatasi lebar secara alami lewat padding, tapi kalau form terasa terlalu lebar untuk field sedikit (misal cuma 2-3 field), tambahkan `max-w-lg` di `<x-card>`. **Jangan** taruh tombol Simpan di kiri atau di tengah — selalu kanan bawah, sejajar dengan tombol Batal.

Untuk form dengan tabel siswa (input nilai/absensi), struktur di atas tetap dipakai — cuma bagian "field-field form" diganti jadi tabel, tombol submit tetap di posisi yang sama (kanan bawah, di luar/bawah tabel).

---

## Pola 3 — Halaman Dashboard (per Role)

Dipakai untuk: `/dashboard` yang redirect ke tampilan sesuai role setelah login.

```blade
@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-2">{{-- SLOT: sapaan, misal "Selamat datang, {{ auth()->user()->nama }}" --}}</h1>
    <p class="text-sm text-slate-500 mb-8">{{-- SLOT: sub-teks singkat, opsional, misal "Kelola data akademik dari sini." --}}</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- SLOT: satu <x-dashboard-link> per menu utama role ini, contoh untuk guru: --}}
        {{-- <x-dashboard-link href="{{ route('guru.nilai.index') }}" title="Input Nilai" description="Input nilai per kelas yang diajar" /> --}}
        {{-- <x-dashboard-link href="{{ route('guru.absensi.index') }}" title="Input Absensi" description="Tandai kehadiran siswa" /> --}}
        {{-- <x-dashboard-link href="{{ route('guru.jadwal.index') }}" title="Jadwal Mengajar" description="Lihat jadwal mengajar" /> --}}
    </div>
@endsection
```

Grid selalu 3 kolom di desktop (`md:grid-cols-3`), 1 kolom di mobile (default). **Jangan** ubah jadi 2 atau 4 kolom meski jumlah menu tidak habis dibagi 3 — biarkan baris terakhir tidak penuh, itu lebih baik daripada grid tidak konsisten antar halaman dashboard role berbeda.

---

## Pola 4 — Halaman Detail/Lihat (Read-Only untuk Siswa)

Dipakai untuk: `siswa.nilai.index`, `siswa.absensi.index` — halaman siswa melihat data miliknya sendiri, dikelompokkan per mapel.

```blade
@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">{{-- SLOT: Judul halaman, misal "Nilai Saya" atau "Rekap Absensi Saya" --}}</h1>

    <div class="space-y-6">
        {{-- SLOT: loop per mapel/mengajar, satu <x-card> per mapel, contoh: --}}
        {{--
        @foreach($dataPerMapel as $mapelNama => $data)
        <x-card :title="$mapelNama">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-slate-500">{{-- label ringkasan, misal "Rata-rata" atau "Kehadiran" --}}</span>
                <span class="text-2xl font-semibold text-accent">{{-- angka besar: rata-rata nilai ATAU persentase kehadiran --}}</span>
            </div>
            <x-table>
                <x-slot:head>
                    <tr>{{-- header sesuai konteks: Jenis+Nilai untuk nilai, Status+Tanggal untuk absensi --}}</tr>
                </x-slot:head>
                {{-- baris detail per jenis nilai ATAU pakai <x-badge> per status absensi --}}
            </x-table>
        </x-card>
        @endforeach
        --}}
    </div>
@endsection
```

**Wajib:** angka ringkasan (rata-rata nilai / persentase kehadiran) HARUS ditonjolkan dengan `text-2xl font-semibold text-accent` di bagian atas tiap card, sebelum detail tabel. Jangan taruh angka ringkasan di bawah tabel atau samakan ukurannya dengan teks biasa — ini poin visual paling penting untuk siswa yang mengecek nilai/kehadirannya sendiri.

---

## Ringkasan Pemetaan Halaman ke Pola

| Halaman | Pola |
|---|---|
| Manajemen User/Kelas/Mapel/Mengajar/Jadwal — list | Pola 1 |
| Manajemen User/Kelas/Mapel/Mengajar/Jadwal — form tambah/edit | Pola 2 |
| Guru: pilih kelas untuk input nilai/absensi (index) | Pola 1 |
| Guru: form input nilai/absensi per kelas | Pola 2 (varian tabel) |
| Guru: lihat jadwal mengajar | Pola 1 (tanpa tombol aksi) |
| Dashboard (semua role setelah login) | Pola 3 |
| Siswa: lihat nilai | Pola 4 |
| Siswa: lihat rekap absensi | Pola 4 |
| Siswa: lihat jadwal | Pola 1 (tanpa tombol aksi) |

Kalau ada halaman yang tidak jelas masuk pola mana, tanyakan lewat pesan sebelum melanjutkan — jangan menebak dan membuat struktur baru sendiri.
