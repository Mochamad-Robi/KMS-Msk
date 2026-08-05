@extends('layouts.app')
@section('title', 'Assignment KPI Kualitatif Cross-Dept')

@section('content')

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Assignment Penilai KPI Kualitatif Cross-Dept</h2>
        <p class="text-gray-400 text-sm mt-0.5">Pilih departemen untuk mengatur penilai kualitatif tambahan (cross-departemen) bagi karyawan</p>
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
        @forelse($departments as $dept)
            <a href="{{ route('admin.kpi.quality-assignments.department', $dept->id) }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md hover:border-primary-200 transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs px-2 py-1 rounded-full font-medium">
                        Cross-Dept
                    </span>
                </div>

                <h3 class="font-semibold text-gray-800 dark:text-gray-100 group-hover:text-primary-700 transition">
                    {{ $dept->name }}
                </h3>
                <p class="text-gray-400 text-xs mt-1">Klik untuk lihat & atur penilai cross-dept</p>
            </a>
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                Belum ada departemen.
            </div>
        @endforelse
    </div>

@endsection