@extends('layouts.app')
@section('title', 'Assignment KPI Kualitatif Cross-Dept — ' . $department->name)

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.kpi.quality-assignments.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali ke Semua Departemen
        </a>
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $department->name }}</h2>
                <p class="text-gray-400 text-sm mt-0.5">{{ $employees->count() }} karyawan</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('admin.kpi.quality-assignments.sync-primary') }}">
                    @csrf
                    <button type="submit"
                            class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap">
                        ⚡ Sync Primary
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($crossKadepts->isEmpty())
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg px-4 py-3 mb-5 text-sm text-yellow-700 dark:text-yellow-400">
            ⚠ Belum ada <strong>Kadept</strong> dari departemen lain yang tersedia untuk cross-dept.
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Karyawan</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Penilai Primary (Dept Sendiri)</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Penilai Cross-Dept</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($employees as $employee)
                    @php
                        $primary = $primaryAssignments->get($employee->id);
                        $cross   = $crossAssignments->get($employee->id);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        {{-- Karyawan --}}
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

                        {{-- Penilai Primary --}}
                        <td class="px-5 py-3">
                            @if($primary?->kadept)
                                <span class="text-gray-700 dark:text-gray-200">{{ $primary->kadept->name }}</span>
                            @else
                                <span class="text-xs text-gray-300 dark:text-gray-600">Belum ada</span>
                            @endif
                        </td>

                        {{-- Penilai Cross-Dept (dropdown assign) --}}
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.kpi.quality-assignments.assign') }}" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <select name="kadept_id" onchange="this.form.submit()"
                                        class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-primary-700">
                                    <option value="">-- Belum ditentukan --</option>
                                    @foreach($crossKadepts as $kadept)
                                        <option value="{{ $kadept->id }}" {{ $cross?->kadept_id == $kadept->id ? 'selected' : '' }}>
                                            {{ $kadept->name }}
                                            @if($kadept->department)
                                                ({{ $kadept->department->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3">
                            @if($cross)
                                <form method="POST" action="{{ route('admin.kpi.quality-assignments.unassign', $cross->id) }}"
                                      onsubmit="return confirm('Hapus assignment cross-dept ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                            Tidak ada karyawan di departemen ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection