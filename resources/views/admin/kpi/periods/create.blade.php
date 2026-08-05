@extends('layouts.app')
@section('title', 'Tambah Periode KPI')

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.kpi.periods.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali
        </a>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Tambah Periode KPI</h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 max-w-lg">

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.kpi.periods.store') }}">
            @csrf

            <div class="space-y-5">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                        <input type="number" name="year" value="{{ old('year', now()->year) }}" min="2020" max="2100"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quartal</label>
                        <select name="quartal"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                            <option value="Q1" {{ old('quartal') === 'Q1' ? 'selected' : '' }}>Q1 (Jan - Mar)</option>
                            <option value="Q2" {{ old('quartal') === 'Q2' ? 'selected' : '' }}>Q2 (Apr - Jun)</option>
                            <option value="Q3" {{ old('quartal') === 'Q3' ? 'selected' : '' }}>Q3 (Jul - Sep)</option>
                            <option value="Q4" {{ old('quartal') === 'Q4' ? 'selected' : '' }}>Q4 (Okt - Des)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                </div>

                <p class="text-xs text-gray-400">
                    Setelah dibuat, periode masih dalam status <strong>Ditutup</strong>. Buka manual dari halaman daftar periode saat siap menerima pengisian KPI.
                </p>

            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Simpan Periode
                </button>
                <a href="{{ route('admin.kpi.periods.index') }}"
                   class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>

        </form>
    </div>

@endsection