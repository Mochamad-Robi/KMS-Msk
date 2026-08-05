@extends('layouts.app')
@section('title', 'Definisi Core Value IKHLAS')

@section('content')

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Definisi Core Value — IKHLAS</h2>
        <p class="text-gray-400 text-sm mt-0.5">
            Panduan indikator penilaian Core Value perusahaan, digunakan sebagai acuan saat mengisi Penilaian Kualitatif KPI.
        </p>
    </div>

    {{-- Banner Akronim --}}
    <div class="bg-primary-700 rounded-xl p-5 mb-6">
        <div class="grid grid-cols-6 gap-2 text-center">
            @foreach($coreValues as $key => $data)
                <div>
                    <p class="text-white text-2xl font-bold">{{ strtoupper(substr($data['label'], 0, 1)) }}</p>
                    <p class="text-white/70 text-xs mt-1">{{ $data['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="space-y-4">
        @foreach($coreValues as $key => $data)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary-700 rounded-lg flex items-center justify-center shrink-0">
                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($data['label'], 0, 1)) }}</span>
                    </div>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $data['label'] }}</p>
                </div>
                <div class="p-5">
                    <ol class="space-y-2">
                        @foreach($data['points'] as $i => $point)
                            <li class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <span class="w-5 h-5 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full flex items-center justify-center text-xs font-semibold shrink-0 mt-0.5">
                                    {{ $i + 1 }}
                                </span>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg px-4 py-3 text-sm text-blue-700 dark:text-blue-400">
        ℹ️ Gunakan poin-poin di atas sebagai pertimbangan saat memberi nilai 1-100 untuk setiap kategori Core Value pada form Penilaian KPI.
    </div>

@endsection