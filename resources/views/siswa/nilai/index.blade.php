@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Nilai Saya</h1>

    <div class="space-y-6">
        @forelse($dataPerMapel as $mapelNama => $data)
        <x-card :title="$mapelNama">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-slate-500">Rata-rata</span>
                <span class="text-2xl font-semibold text-accent">{{ number_format($data['rata_rata'] ?? 0, 2) }}</span>
            </div>

            <x-table>
                <x-slot:head>
                    <tr>
                        <th class="text-left px-4 py-3 font-medium">Jenis</th>
                        <th class="text-center px-4 py-3 font-medium">Nilai</th>
                    </tr>
                </x-slot:head>
                <tbody class="divide-y divide-slate-200">
                    <tr class="hover:bg-surface">
                        <td class="px-4 py-3">Tugas</td>
                        <td class="px-4 py-3 text-center">{{ $data['nilai']['tugas'] ?? '-' }}</td>
                    </tr>
                    <tr class="hover:bg-surface">
                        <td class="px-4 py-3">UTS</td>
                        <td class="px-4 py-3 text-center">{{ $data['nilai']['uts'] ?? '-' }}</td>
                    </tr>
                    <tr class="hover:bg-surface">
                        <td class="px-4 py-3">UAS</td>
                        <td class="px-4 py-3 text-center">{{ $data['nilai']['uas'] ?? '-' }}</td>
                    </tr>
                </tbody>
            </x-table>

            @if($data['nilai_akhir'] !== null)
            <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                <span>Nilai Akhir: <strong>{{ $data['nilai_akhir'] }}</strong></span>
                <span>Predikat: <strong>{{ $data['predikat'] }}</strong></span>
            </div>
            @endif
        </x-card>
        @empty
        <p class="text-slate-500 text-center py-8">Belum ada data nilai.</p>
        @endforelse
    </div>
@endsection