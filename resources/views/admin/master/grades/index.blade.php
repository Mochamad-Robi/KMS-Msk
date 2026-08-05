@extends('layouts.app')

@section('title', 'Master Data Grade')

@section('breadcrumb')
    <span class="text-gray-400 dark:text-gray-500">Admin</span>

    <svg class="inline w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>

    <span class="text-gray-400 dark:text-gray-500">Master Data</span>

    <svg class="inline w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>

    <span class="text-gray-700 dark:text-gray-200 font-medium">Grade</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Master Data Grade</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola level grade karyawan</p>
        </div>
        <a href="{{ route('admin.master.grades.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Grade
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg text-green-700 dark:text-green-300 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg text-red-700 dark:text-red-300 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Grade</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($grades as $grade)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 font-bold text-sm">
                            {{ $grade->level }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $grade->name }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                        @if($grade->code)
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs font-mono">{{ $grade->code }}</span>
                        @else
                            <span class="text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($grade->is_active)
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-xs font-medium">Aktif</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-xs font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.master.grades.edit', $grade) }}"
                               class="px-3 py-1.5 text-xs font-medium text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.master.grades.destroy', $grade) }}" method="POST"
                                  onsubmit="return confirm('Hapus grade {{ $grade->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                        Belum ada data grade.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection