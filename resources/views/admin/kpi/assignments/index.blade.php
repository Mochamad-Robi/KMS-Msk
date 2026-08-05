@extends('layouts.app')
@section('title', 'Assignment KPI')

@section('content')

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Assignment Penilai KPI</h2>
        <p class="text-gray-400 text-sm mt-0.5">Pilih departemen untuk mengatur siapa Kadept yang menilai karyawan</p>
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($deptStats as $stat)
            @php
                $dept = $stat['department'];
                $total = $stat['employee_count'];
                $assigned = $stat['assigned_count'];
                $percent = $total > 0 ? round(($assigned / $total) * 100) : 0;
            @endphp
            <a href="{{ route('admin.kpi.assignments.department', $dept->id) }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md hover:border-primary-200 transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/>
                        </svg>
                    </div>
                    @if($total > 0 && $assigned === $total)
                        <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">
                            ✓ Lengkap
                        </span>
                    @elseif($assigned > 0)
                        <span class="bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs px-2 py-1 rounded-full font-medium">
                            Sebagian
                        </span>
                    @else
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs px-2 py-1 rounded-full font-medium">
                            Belum di-assign
                        </span>
                    @endif
                </div>

                <h3 class="font-semibold text-gray-800 dark:text-gray-100 group-hover:text-primary-700 transition">
                    {{ $dept->name }}
                </h3>
                <p class="text-gray-400 text-xs mt-1">{{ $total }} karyawan</p>

                <div class="mt-3">
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                        <span>{{ $assigned }}/{{ $total }} sudah di-assign</span>
                        <span>{{ $percent }}%</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-700 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                Belum ada departemen.
            </div>
        @endforelse
    </div>

@endsection