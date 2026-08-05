@extends('layouts.app')
@section('title', 'Rekap KPI — ' . $department->name)

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.kpi.rekap.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali
        </a>
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $department->name }}</h2>
                <p class="text-gray-400 text-sm mt-0.5">Periode {{ $period->label }} &mdash; {{ $totalEmployees }} karyawan</p>
            </div>

            @if($allComplete)
                <a href="{{ route('admin.kpi.rekap.pdf', [$department->id, $period->id]) }}"
                   class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PDF Laporan
                </a>
            @else
                <div class="text-right">
                    <button disabled
                            class="bg-gray-200 dark:bg-gray-700 text-gray-400 text-sm font-semibold px-5 py-2.5 rounded-lg cursor-not-allowed flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z"/>
                        </svg>
                        PDF Belum Tersedia
                    </button>
                    <p class="text-xs text-gray-400 mt-1">{{ $completeCount }}/{{ $totalEmployees }} karyawan selesai dinilai</p>
                </div>
            @endif
        </div>
    </div>

    @if(!$allComplete)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg px-4 py-3 mb-5 text-sm text-yellow-700 dark:text-yellow-400">
            ⚠ PDF laporan hanya bisa di-download setelah <strong>semua karyawan</strong> di departemen ini selesai dinilai (termasuk penilai cross-dept jika ada).
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Karyawan</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Cross-Dept?</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Grand Total</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Grade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @foreach($rows as $row)
                    @php $employee = $row['employee']; $evaluation = $row['evaluation']; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if($employee->avatar)
                                    <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($employee->avatar)]) }}"
                                         class="w-8 h-8 rounded-full object-cover border border-gray-200 shrink-0">
                                @else
                                    <div class="w-8 h-8 bg-primary-700 rounded-full flex items-center justify-center shrink-0">
                                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($employee->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <span class="font-medium text-gray-700 dark:text-gray-200">{{ $employee->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            @if($row['has_cross'])
                                <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs px-2 py-1 rounded-full font-medium">Ya</span>
                            @else
                                <span class="text-xs text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($row['is_complete'])
                                <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">✓ Lengkap</span>
                            @elseif($evaluation)
                                <span class="bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs px-2 py-1 rounded-full font-medium">
                                    {{ $evaluation->status === 'submitted' ? 'Menunggu Cross-Dept' : 'Draft' }}
                                </span>
                            @else
                                <span class="bg-orange-50 dark:bg-orange-900/30 text-orange-500 dark:text-orange-400 text-xs px-2 py-1 rounded-full font-medium">Belum Diisi</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-200">
                            {{ $evaluation?->grand_total ?? '-' }}
                        </td>
                        <td class="px-5 py-3">
                            @if($row['is_complete'] && $evaluation?->grade)
                                @php
                                    $gradeColors = [
                                        'istimewa'    => 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                                        'baik_sekali' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                                        'baik'        => 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                                        'cukup'       => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'kurang'      => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="{{ $gradeColors[$evaluation->grade] ?? '' }} text-xs px-2 py-1 rounded-full font-medium">
                                    {{ $evaluation->grade_label }}
                                </span>
                            @else
                                <span class="text-xs text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection