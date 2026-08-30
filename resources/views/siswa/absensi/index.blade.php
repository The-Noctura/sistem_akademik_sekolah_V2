@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Absensi Saya</h1>

    <div class="space-y-6">
        @forelse($dataPerMapel as $mapelNama => $data)
        <x-card :title="$mapelNama">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-slate-500">Kehadiran</span>
                <span class="text-2xl font-semibold text-accent">{{ number_format($data['persentase_hadir'] ?? 0, 2) }}%</span>
            </div>

            <x-table>
                <x-slot:head>
                    <tr>
                        <th class="text-left px-4 py-3 font-medium">Tanggal</th>
                        <th class="text-center px-4 py-3 font-medium">Status</th>
                    </tr>
                </x-slot:head>
                <tbody class="divide-y divide-slate-200">
                    @forelse($data['absensi'] as $a)
                    <tr class="hover:bg-surface">
                        <td class="px-4 py-3">{{ $a->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <x-badge :variant="['hadir'=>'success','izin'=>'warning','sakit'=>'default','alpa'=>'error'][$a->status]">
                                {{ ucfirst($a->status) }}
                            </x-badge>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-4 py-3 text-center text-slate-500" colspan="2">Belum ada data absensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </x-table>

            <div class="mt-4 grid grid-cols-4 gap-2 text-sm">
                <div class="bg-green-50 text-green-700 p-2 rounded text-center">
                    <div class="font-semibold">{{ $data['counts']['hadir'] }}</div>
                    <div>Hadir</div>
                </div>
                <div class="bg-amber-50 text-amber-700 p-2 rounded text-center">
                    <div class="font-semibold">{{ $data['counts']['izin'] }}</div>
                    <div>Izin</div>
                </div>
                <div class="bg-accent-soft text-accent p-2 rounded text-center">
                    <div class="font-semibold">{{ $data['counts']['sakit'] }}</div>
                    <div>Sakit</div>
                </div>
                <div class="bg-red-50 text-red-700 p-2 rounded text-center">
                    <div class="font-semibold">{{ $data['counts']['alpa'] }}</div>
                    <div>Alpa</div>
                </div>
            </div>
        </x-card>
        @empty
        <p class="text-slate-500 text-center py-8">Belum ada data absensi.</p>
        @endforelse
    </div>
@endsection