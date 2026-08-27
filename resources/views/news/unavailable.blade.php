@extends('layouts.app')
@section('title', 'Berita Tidak Tersedia')

@section('content')
<div class="flex flex-col items-center justify-center py-20 px-6 text-center">
    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-5">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">
        Berita Sudah Tidak Tersedia
    </h2>
    <p class="text-gray-400 text-sm max-w-md leading-relaxed mb-6">
        Berita ini sudah berakhir masa tayangnya atau belum dipublikasikan.
        Silakan lihat berita lain yang sedang tayang.
    </p>

    <a href="{{ route('news.index') }}"
       class="inline-flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-3 rounded-xl transition text-sm">
        &larr; Lihat Semua Berita
    </a>
</div>
@endsection