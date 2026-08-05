@extends('layouts.app')
@section('title', 'Portal News')

@section('content')

@php
    $categoryColors = [
        'pemberitahuan' => 'bg-blue-600',
        'poster'        => 'bg-purple-600',
        'himbauan'      => 'bg-orange-500',
        'promosi-umkm'  => 'bg-green-600',
        'birthday'      => 'bg-pink-500',
    ];
    $categoryLabels = [
        'pemberitahuan' => __('app.news_notice'),
        'poster'        => __('app.news_poster'),
        'himbauan'      => __('app.news_advisory'),
        'promosi-umkm'  => __('app.news_umkm'),
        'birthday'      => 'Ulang Tahun 🎂',
    ];
    $subCategoryIcons = [
        'food-beverage' => '🍔',
        'otomotif'      => '🚗',
        'properti'      => '🏠',
        'gadget'        => '📱',
    ];
@endphp

{{-- Hero Header --}}
<div class="bg-gradient-to-r from-primary-800 to-primary-900 rounded-2xl px-8 py-7 mb-6 flex items-center justify-between">
    <div>
        <p class="text-primary-300 text-xs uppercase tracking-widest font-semibold mb-1">PT MSK Internal</p>
        <h1 class="text-white text-2xl font-black">{{ __('app.employee_info') }}</h1>
        <p class="text-primary-300 text-sm mt-1">{{ __('app.portal_news_sub') }}</p>
    </div>
    <div class="text-right hidden sm:block">
        <p class="text-white font-bold text-lg">{{ now()->translatedFormat('d F Y') }}</p>
        <p class="text-primary-300 text-xs mt-0.5">{{ $news->total() }} artikel tersedia</p>
    </div>
</div>

{{-- Filter Kategori --}}
<div class="flex items-center gap-2 mb-3 flex-wrap">
    <a href="{{ route('news.index') }}"
       class="px-4 py-2 rounded-full text-sm font-semibold transition border
              {{ $activeCategory === 'all' ? 'bg-primary-700 text-white border-primary-700' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200' }}">
        Semua
    </a>
    @foreach($categories as $key => $label)
        @if($key !== 'all')
        <a href="{{ route('news.index', ['category' => $key]) }}"
           class="px-4 py-2 rounded-full text-sm font-semibold transition border
                  {{ $activeCategory === $key
                      ? ($key === 'birthday' ? 'bg-pink-500 text-white border-pink-500' : 'bg-primary-700 text-white border-primary-700')
                      : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200' }}">
            {{ $label }}
        </a>
        @endif
    @endforeach
</div>

{{-- Filter Sub-Kategori UMKM --}}
@if($activeCategory === 'promosi-umkm')
    <div class="flex items-center gap-2 mb-6 flex-wrap">
        <a href="{{ route('news.index', ['category' => 'promosi-umkm']) }}"
           class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border
                  {{ $activeSubCategory === 'all' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-500 hover:bg-gray-50 border-gray-200' }}">
            Semua Kategori
        </a>
        @foreach($subCategories as $key => $label)
            <a href="{{ route('news.index', ['category' => 'promosi-umkm', 'sub_category' => $key]) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border flex items-center gap-1
                      {{ $activeSubCategory === $key ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-500 hover:bg-gray-50 border-gray-200' }}">
                <span>{{ $subCategoryIcons[$key] ?? '' }}</span>
                {{ $label }}
            </a>
        @endforeach
    </div>
@else
    <div class="mb-6"></div>
@endif

@if($news->isEmpty())
    <div class="bg-white rounded-xl border border-gray-100 p-16 text-center">
        <div class="text-5xl mb-3">📭</div>
        <p class="text-gray-400 text-sm">Belum ada berita tersedia</p>
    </div>
@else

    @php $featured = $news->first(); $rest = $news->slice(1); @endphp

    {{-- Featured Article --}}
    <a href="{{ route('news.show', $featured->id) }}"
       class="block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6 hover:shadow-lg transition group">

        @if($featured->isBirthday())
            {{-- Birthday Featured Card --}}
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="bg-gradient-to-br from-pink-400 via-pink-500 to-rose-500 flex items-center justify-center p-10 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 text-8xl flex items-center justify-center select-none">🎂🎉🎊</div>
                    <div class="relative z-10 text-center">
                        @if($featured->birthdayUser?->avatar)
                            <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($featured->birthdayUser->avatar)]) }}"
                                 class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-xl mx-auto mb-3"/>
                        @else
                            <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center mx-auto mb-3 border-4 border-white shadow-xl">
                                <span class="text-pink-500 text-3xl font-black">
                                    {{ strtoupper(substr($featured->birthdayUser?->name ?? '?', 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <p class="text-white font-black text-xl">{{ $featured->birthdayUser?->name }}</p>
                        @if($featured->birthdayUser?->birth_date)
                            <p class="text-pink-100 text-sm mt-1">🎂 Ulang Tahun ke-{{ $featured->birthdayUser->birth_date->age }}</p>
                        @endif
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full">★ Utama</span>
                    </div>
                </div>
                <div class="p-7 flex flex-col justify-center">
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full mb-3 text-white w-fit bg-pink-500">
                        Ulang Tahun 🎂
                    </span>
                    <h2 class="text-xl font-black text-gray-900 group-hover:text-pink-500 transition leading-snug mb-3">
                        {{ $featured->title }}
                    </h2>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">
                        {{ $featured->birthdayUser?->department?->name ?? '-' }} • {{ $featured->birthdayUser?->position?->name ?? '-' }}
                    </p>
                    <div class="flex items-center gap-4 text-gray-400 text-xs mt-auto">
                        <span>{{ $featured->publish_at?->format('d M Y') }}</span>
                        <span>👀 {{ $featured->views_count ?? 0 }} views</span>
                        <span>🎉 {{ $featured->comments_count ?? 0 }} ucapan</span>
                    </div>
                </div>
            </div>
        @else
            {{-- Normal Featured Card --}}
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="h-64 md:h-auto overflow-hidden bg-gradient-to-br from-primary-700 to-primary-900 relative">
                    @if($featured->image_path)
                        <img src="{{ route('media.serve', ['type' => explode('/', $featured->image_path)[0], 'filename' => explode('/', $featured->image_path)[1]]) }}"
                             alt="{{ $featured->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-7xl opacity-30">📰</span>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="bg-primary-700 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            ★ Utama
                        </span>
                    </div>
                </div>
                <div class="p-7 flex flex-col justify-center">
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full mb-3 text-white w-fit {{ $categoryColors[$featured->category] ?? 'bg-gray-500' }}">
                        {{ $categoryLabels[$featured->category] ?? $featured->category }}
                    </span>
                    <h2 class="text-xl font-black text-gray-900 group-hover:text-primary-700 transition leading-snug mb-3">
                        {{ $featured->title }}
                    </h2>
                    @if($featured->content)
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-4">{!! strip_tags($featured->content) !!}</p>
                    @endif
                    <div class="flex items-center gap-4 text-gray-400 text-xs mt-auto">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $featured->publish_at?->format('d M Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $featured->creator?->name }}
                        </span>
                        <span>👀 {{ $featured->views_count ?? 0 }}</span>
                        <span>💬 {{ $featured->comments_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
        @endif
    </a>

    {{-- Rest of Articles --}}
    @if($rest->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($rest as $item)
                <a href="{{ route('news.show', $item->id) }}"
                   class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition group flex flex-col
                          {{ $item->isBirthday() ? 'hover:border-pink-200' : 'hover:border-primary-200' }}">

                    {{-- Image / Birthday Avatar --}}
                    <div class="h-44 overflow-hidden relative {{ $item->isBirthday() ? 'bg-gradient-to-br from-pink-400 to-rose-500' : 'bg-gradient-to-br from-gray-100 to-gray-200' }}">
                        @if($item->isBirthday())
                            <div class="w-full h-full flex items-center justify-center flex-col gap-2">
                                @if($item->birthdayUser?->avatar)
                                    <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($item->birthdayUser->avatar)]) }}"
                                         class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg"/>
                                @else
                                    <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                                        <span class="text-pink-500 text-2xl font-black">
                                            {{ strtoupper(substr($item->birthdayUser?->name ?? '?', 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                @if($item->birthdayUser?->birth_date)
                                    <span class="text-white text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">
                                        🎂 {{ $item->birthdayUser->birth_date->age }} tahun
                                    </span>
                                @endif
                            </div>
                        @elseif($item->image_path)
                            <img src="{{ route('media.serve', ['type' => explode('/', $item->image_path)[0], 'filename' => explode('/', $item->image_path)[1]]) }}"
                                 alt="{{ $item->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-4xl opacity-30">
                                    {{ $item->category === 'pemberitahuan' ? '📢' :
                                       ($item->category === 'poster' ? '🖼️' :
                                       ($item->category === 'himbauan' ? '⚠️' : '🛒')) }}
                                </span>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="text-white text-xs font-bold px-2.5 py-1 rounded-full {{ $categoryColors[$item->category] ?? 'bg-gray-500' }}">
                                {{ $categoryLabels[$item->category] ?? $item->category }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-800 text-sm group-hover:text-primary-700 transition line-clamp-2 leading-snug mb-2">
                            {{ $item->title }}
                        </h3>
                        @if($item->isBirthday())
                            <p class="text-gray-400 text-xs flex-1">
                                {{ $item->birthdayUser?->department?->name ?? '-' }} • {{ $item->birthdayUser?->position?->name ?? '-' }}
                            </p>
                       @elseif($item->content)
                            <p class="text-gray-400 text-xs line-clamp-2 leading-relaxed flex-1">{!! strip_tags($item->content) !!}</p>
                        @endif
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <span class="text-gray-400 text-xs">{{ $item->publish_at?->format('d M Y') }}</span>
                            <div class="flex items-center gap-3 text-gray-400 text-xs">
                                <span>👀 {{ $item->views_count ?? 0 }}</span>
                                <span>{{ $item->isBirthday() ? '🎉' : '💬' }} {{ $item->comments_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    @endif

    <div class="mt-6">
        {{ $news->links() }}
    </div>

@endif

@endsection