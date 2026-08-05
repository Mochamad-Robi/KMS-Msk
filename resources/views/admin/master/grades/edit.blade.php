@extends('layouts.app')

@section('title', 'Edit Grade')

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
<div class="max-w-lg mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Edit Grade — {{ $grade->name }}</h2>

        <form action="{{ route('admin.master.grades.update', $grade) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Grade <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $grade->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       placeholder="Contoh: General Manager">
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kode --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Kode <span class="text-gray-400 text-xs">(opsional)</span>
                </label>
                <input type="text" name="code" value="{{ old('code', $grade->code) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('code') border-red-500 @enderror"
                       placeholder="Contoh: GM">
                @error('code')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Level --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Level <span class="text-red-500">*</span>
                    <span class="text-gray-400 text-xs ml-1">(1 = tertinggi)</span>
                </label>
                <input type="number" name="level" value="{{ old('level', $grade->level) }}" min="1"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('level') border-red-500 @enderror">
                @error('level')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $grade->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-primary-700 border-gray-300 rounded focus:ring-primary-500">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Aktif
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-lg transition">
                    Perbarui
                </button>
                <a href="{{ route('admin.master.grades.index') }}"
                   class="px-5 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection