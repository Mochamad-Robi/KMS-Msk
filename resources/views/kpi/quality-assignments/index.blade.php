@extends('layouts.app')
@section('title', 'Assignment Kualitatif Cross-Departemen')

@section('content')

    <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Assignment Kualitatif Cross-Departemen</h2>
            <p class="text-gray-400 text-sm mt-0.5">
                Tambahkan penilai kualitatif ke-2 (di luar kadept dept sendiri) untuk karyawan tertentu
            </p>
        </div>
        <form method="POST" action="{{ route('admin.kpi.quality-assignments.sync-primary') }}">
            @csrf
            <button type="submit"
                    class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-semibold px-4 py-2 rounded-lg transition">
                🔄 Sinkronkan Kadept Primary
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg px-4 py-3 mb-5 text-sm text-blue-700 dark:text-blue-400">
        ℹ️ Kadept <strong>dept sendiri</strong> otomatis jadi penilai kualitatif utama (mengikuti assignment kuantitatif). Halaman ini khusus untuk menambahkan penilai <strong>ke-2 dari departemen lain</strong> jika diperlukan.
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($departments as $dept)
            <a href="{{ route('admin.kpi.quality-assignments.department', $dept->id) }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md hover:border-primary-200 transition group">
                <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-100 group-hover:text-primary-700 transition">
                    {{ $dept->name }}
                </h3>
                <p class="text-gray-400 text-xs mt-1">Kelola penilai cross-dept</p>
            </a>
        @endforeach
    </div>

@endsection