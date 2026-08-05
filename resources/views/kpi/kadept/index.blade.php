@extends('layouts.app')
@section('title', 'KPI — Penilaian Karyawan')

@section('content')

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Penilaian KPI</h2>
        <p class="text-gray-400 text-sm mt-0.5">Isi penilaian untuk karyawan yang menjadi tanggung jawab Anda</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(!$activePeriod)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-10 text-center">
            <div class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada periode penilaian yang dibuka</p>
            <p class="text-gray-400 text-sm mt-1">Admin belum membuka periode KPI saat ini. Silakan cek kembali nanti.</p>
        </div>

    @elseif($primaryRows->isEmpty() && $crossRows->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-10 text-center">
            <div class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 100-8 4 4 0 000 8zm6 3a4 4 0 100-8"/>
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada karyawan yang di-assign untuk Anda nilai</p>
            <p class="text-gray-400 text-sm mt-1">Hubungi Admin untuk mengatur assignment penilaian KPI.</p>
        </div>

    @else
        <div class="mb-5 flex items-center gap-2 bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800 rounded-lg px-4 py-3">
            <svg class="w-4 h-4 text-primary-700 dark:text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-primary-700 dark:text-primary-400 font-medium">
                Periode Aktif: {{ $activePeriod->label }}
                <span class="text-primary-500 dark:text-primary-500 font-normal">
                    ({{ $activePeriod->start_date->format('d M') }} - {{ $activePeriod->end_date->format('d M Y') }})
                </span>
            </p>
        </div>

        @php
            $statusBadge = function($status) {
                return match($status) {
                    'locked'    => '<span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs px-2 py-1 rounded-full font-medium">🔒 Terkunci</span>',
                    'submitted' => '<span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">✓ Selesai</span>',
                    'draft'     => '<span class="bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs px-2 py-1 rounded-full font-medium">Draft</span>',
                    default     => '<span class="bg-orange-50 dark:bg-orange-900/30 text-orange-500 dark:text-orange-400 text-xs px-2 py-1 rounded-full font-medium">Belum Diisi</span>',
                };
            };
        @endphp

        {{-- ===== KARYAWAN DEPT SENDIRI (PRIMARY) ===== --}}
        @if($primaryRows->isNotEmpty())
        <div class="mb-3">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Karyawan Departemen Anda</p>
            <p class="text-xs text-gray-400">Penilaian lengkap: Kuantitatif + Kualitatif</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Karyawan</th>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Jabatan</th>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Status</th>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($primaryRows as $row)
                        @php $employee = $row['employee']; @endphp
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
                                    <p class="font-medium text-gray-700 dark:text-gray-200">{{ $employee->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $employee->position?->name ?? '-' }}</td>
                            <td class="px-5 py-3">
                                {!! $statusBadge($row['status']) !!}
                                @if($row['is_waiting'])
                                    <span class="block text-xs text-yellow-500 mt-1">⏳ Menunggu penilai cross-dept</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if($row['status'] === 'locked')
                                    <span class="text-xs text-gray-300">Terkunci</span>
                                @else
                                    <a href="{{ route('kpi.kadept.form', $employee->id) }}" class="text-xs text-primary-700 hover:underline font-medium">
                                        {{ $row['status'] === 'belum_diisi' ? 'Isi Penilaian' : 'Edit Penilaian' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ===== KARYAWAN CROSS-DEPT ===== --}}
        @if($crossRows->isNotEmpty())
        <div class="mb-3">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Karyawan Departemen Lain (Cross-Dept)</p>
            <p class="text-xs text-gray-400">Penilaian: Kualitatif saja</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Karyawan</th>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Departemen</th>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Status</th>
                        <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($crossRows as $row)
                        @php $employee = $row['employee']; @endphp
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
                                    <p class="font-medium text-gray-700 dark:text-gray-200">{{ $employee->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $employee->department?->name ?? '-' }}</td>
                            <td class="px-5 py-3">{!! $statusBadge($row['status']) !!}</td>
                            <td class="px-5 py-3">
                                @if($row['status'] === 'locked')
                                    <span class="text-xs text-gray-300">Terkunci</span>
                                @else
                                    <a href="{{ route('kpi.kadept.form', $employee->id) }}" class="text-xs text-primary-700 hover:underline font-medium">
                                        {{ $row['status'] === 'belum_diisi' ? 'Isi Penilaian' : 'Edit Penilaian' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @endif

@endsection