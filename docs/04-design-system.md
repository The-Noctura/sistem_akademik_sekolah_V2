# 04 — Design System & Component Library

Arah visual: **modern minimalis** — putih bersih, 1 warna aksen, banyak whitespace. Tidak ada gradient, tidak ada shadow berat, tidak ada dekorasi yang tidak fungsional.

**Aturan keras:** setiap halaman yang kamu buat WAJIB pakai token dan komponen di file ini. Jangan generate warna baru, jangan generate struktur tombol/form/tabel baru dari nol. Kalau butuh elemen yang belum ada di sini, kombinasikan komponen yang sudah ada — jangan bikin elemen baru sendiri.

---

## Design Tokens

### Warna

```css
--color-bg:            #FFFFFF;
--color-surface:       #F8FAFC;
--color-border:        #E2E8F0;

--color-text:          #0F172A;
--color-text-muted:    #64748B;
--color-text-inverse:  #FFFFFF;

--color-accent:        #2563EB;
--color-accent-hover:  #1D4ED8;
--color-accent-soft:   #EFF6FF;

--color-success:       #16A34A;
--color-error:         #DC2626;
--color-warning:       #D97706;
```

Warna status (`success`/`error`/`warning`) HANYA untuk feedback aksi (notifikasi berhasil/gagal, status absensi). JANGAN dipakai untuk dekorasi atau kategori lain.

### Tipografi

```css
--font-family: 'Inter', -apple-system, sans-serif;

--text-xs:    0.75rem;
--text-sm:    0.875rem;
--text-base:  1rem;
--text-lg:    1.125rem;
--text-xl:    1.5rem;
--text-2xl:   2rem;

--font-normal:   400;
--font-medium:   500;
--font-semibold: 600;
```

JANGAN pakai `font-weight: 700`. `semibold` (600) adalah bobot tertinggi yang dipakai di seluruh project ini.

### Spacing

Skala 4px (Tailwind default): `1=4px, 2=8px, 3=12px, 4=16px, 6=24px, 8=32px, 12=48px`.

### Radius & Shadow

```css
--radius-sm:  0.375rem;
--radius-md:  0.5rem;
--radius-lg:  0.75rem;

--shadow-sm:  0 1px 2px rgba(15, 23, 42, 0.05);
--shadow-md:  0 4px 12px rgba(15, 23, 42, 0.08);
```

Tidak ada shadow lebih berat dari `shadow-md`. Tidak ada radius lebih besar dari `radius-lg` kecuali avatar/ikon kecil.

### Tailwind Config

```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        accent: { DEFAULT: '#2563EB', hover: '#1D4ED8', soft: '#EFF6FF' },
        surface: '#F8FAFC',
      },
      fontFamily: { sans: ['Inter', 'sans-serif'] },
    },
  },
};
```

Pakai sebagai `bg-accent`, `text-accent`, `hover:bg-accent-hover`. JANGAN tulis hex code langsung di class (`bg-[#2563EB]`).

---

## Component Library

Lokasi: `resources/views/components/`.

### Layout Dasar — `layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Akademik')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-white text-slate-900 font-sans">
    @include('components.navbar')
    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if ($errors->any())
            <x-alert type="error" :message="$errors->first()" />
        @endif
        @yield('content')
    </main>
</body>
</html>
```

### Navbar — `components/navbar.blade.php`

```blade
<nav class="border-b border-slate-200 bg-white">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <span class="font-semibold text-lg">Sistem Akademik</span>
        @auth
        <div class="flex items-center gap-4 text-sm">
            <span class="text-slate-500">{{ auth()->user()->nama }} · {{ ucfirst(auth()->user()->role) }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-accent">Keluar</button>
            </form>
        </div>
        @endauth
    </div>
</nav>
```

Tidak ada menu navigasi kompleks di navbar. Navigasi per role cukup lewat dashboard (lihat komponen Dashboard Card di bawah).

### Button — `components/button.blade.php`

```blade
@props(['variant' => 'primary', 'type' => 'button'])
@php
$classes = match($variant) {
    'primary'   => 'bg-accent text-white hover:bg-accent-hover',
    'secondary' => 'bg-transparent border border-slate-200 text-slate-900 hover:bg-surface',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
};
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => "px-4 py-2 rounded-md text-sm font-medium transition-colors $classes"]) }}>
    {{ $slot }}
</button>
```

Pemakaian: `<x-button variant="primary" type="submit">Simpan</x-button>`

### Card — `components/card.blade.php`

```blade
@props(['title' => null])
<div {{ $attributes->merge(['class' => 'bg-surface border border-slate-200 rounded-lg p-6 shadow-sm']) }}>
    @if($title)<h3 class="text-lg font-semibold mb-4">{{ $title }}</h3>@endif
    {{ $slot }}
</div>
```

### Form Input — `components/form-input.blade.php`

```blade
@props(['name', 'label', 'type' => 'text', 'value' => null])
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}
    >
    @error($name)<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
</div>
```

### Select Dropdown — `components/form-select.blade.php`

```blade
@props(['name', 'label', 'options' => [], 'selected' => null])
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}>
        <option value="">-- Pilih --</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error($name)<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
</div>
```

### Tabel — `components/table.blade.php`

```blade
<div class="overflow-x-auto border border-slate-200 rounded-lg">
    <table class="w-full text-sm">
        <thead class="bg-surface border-b border-slate-200">{{ $head }}</thead>
        <tbody class="divide-y divide-slate-200">{{ $slot }}</tbody>
    </table>
</div>
```

Pemakaian:
```blade
<x-table>
    <x-slot:head>
        <tr><th class="text-left px-4 py-3 font-medium">Nama</th></tr>
    </x-slot:head>
    @foreach($items as $item)
    <tr class="hover:bg-surface"><td class="px-4 py-3">{{ $item->nama }}</td></tr>
    @endforeach
</x-table>
```

### Badge — `components/badge.blade.php`

```blade
@props(['variant' => 'default'])
@php
$classes = match($variant) {
    'success' => 'bg-green-50 text-green-700',
    'error'   => 'bg-red-50 text-red-700',
    'warning' => 'bg-amber-50 text-amber-700',
    default   => 'bg-accent-soft text-accent',
};
@endphp
<span {{ $attributes->merge(['class' => "inline-block px-2 py-1 rounded-sm text-xs font-medium $classes"]) }}>{{ $slot }}</span>
```

### Alert — `components/alert.blade.php`

```blade
@props(['type' => 'success', 'message'])
@php
$classes = $type === 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200';
@endphp
<div class="border {{ $classes }} rounded-md px-4 py-3 mb-6 text-sm">{{ $message }}</div>
```

Dipanggil otomatis lewat `layouts.app.blade.php` — tidak perlu dipanggil manual di tiap halaman.

### Dashboard Link — `components/dashboard-link.blade.php`

```blade
@props(['href', 'title', 'description'])
<a href="{{ $href }}" class="block bg-surface border border-slate-200 rounded-lg p-6 hover:shadow-md hover:border-accent transition-all">
    <h3 class="font-semibold mb-1">{{ $title }}</h3>
    <p class="text-sm text-slate-500">{{ $description }}</p>
</a>
```

Dipakai sebagai pengganti navbar kompleks — dashboard tiap role berisi grid `<x-dashboard-link>` menuju modul masing-masing.

---

## Hal yang Dilarang

- Warna aksen kedua/ketiga di luar `accent` — kalau butuh variasi, pakai `accent-soft` atau `text-muted`
- Gradient di background atau tombol manapun
- Font selain Inter
- Border-radius lebih dari `radius-lg` (kecuali avatar/ikon kecil, `rounded-full`)
- Icon set campuran — kalau butuh ikon, pakai [Lucide](https://lucide.dev) secara konsisten
