@extends('layouts.app')
@section('title', $news->title)

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
        'pemberitahuan' => 'Pemberitahuan',
        'poster'        => 'Poster',
        'himbauan'      => 'Himbauan',
        'promosi-umkm'  => 'Promosi UMKM',
        'birthday'      => 'Ulang Tahun 🎂',
    ];
@endphp

{{-- Back --}}
<a href="{{ route('news.index') }}"
   class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary-700 mb-5 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    Kembali ke Portal Berita
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Hero Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            @if($news->isBirthday())
                {{-- ===== BIRTHDAY CARD SPECIAL ===== --}}
                <div class="relative bg-gradient-to-br from-pink-400 via-pink-500 to-rose-500 p-8 text-center overflow-hidden">
                    {{-- Background decorations --}}
                    <div class="absolute inset-0 opacity-10 text-8xl flex items-center justify-center select-none pointer-events-none">
                        🎂🎉🎊🎈
                    </div>

                    {{-- Avatar --}}
                    <div class="relative z-10 mb-4">
                        @if($news->birthdayUser?->avatar)
                            <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($news->birthdayUser->avatar)]) }}"
                                 alt="{{ $news->birthdayUser->name }}"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-xl mx-auto"/>
                        @else
                            <div class="w-28 h-28 rounded-full bg-white flex items-center justify-center mx-auto border-4 border-white shadow-xl">
                                <span class="text-pink-500 text-4xl font-black">
                                    {{ strtoupper(substr($news->birthdayUser?->name ?? '?', 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-lg shadow-lg" style="left: calc(50% + 42px)">
                            🎂
                        </div>
                    </div>

                    {{-- Name & Age --}}
                    <div class="relative z-10">
                        <h1 class="text-2xl font-black text-white mb-1">
                            {{ $news->birthdayUser?->name }}
                        </h1>
                        <p class="text-pink-100 text-sm mb-2">
                            {{ $news->birthdayUser?->department?->name ?? '-' }} • {{ $news->birthdayUser?->position?->name ?? '-' }}
                        </p>
                        @if($news->birthdayUser?->birth_date)
                            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur rounded-full px-4 py-1.5 text-white text-sm font-semibold">
                                🎉 Ulang Tahun ke-{{ $news->birthdayUser->birth_date->age }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    {{-- Stats --}}
                    <div class="flex items-center flex-wrap gap-3 mb-4">
                        <span class="text-white text-xs font-bold px-3 py-1 rounded-full bg-pink-500">
                            Ulang Tahun 🎂
                        </span>
                        <span class="text-gray-400 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $news->publish_at?->format('d F Y') }}
                        </span>
                        <span class="text-gray-400 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $comments->count() }} ucapan
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="text-gray-700 text-sm leading-relaxed" style="white-space: pre-line">
                        {!! $news->content !!}
                    </div>
                </div>

            @else
                {{-- ===== NORMAL NEWS CARD ===== --}}
                @if($news->image_path)
                    <div class="h-80 overflow-hidden">
                        <img src="{{ route('media.serve', ['type' => explode('/', $news->image_path)[0], 'filename' => explode('/', $news->image_path)[1]]) }}"
                             alt="{{ $news->title }}"
                             class="w-full h-full object-cover"/>
                    </div>
                @else
                    <div class="h-40 bg-gradient-to-r from-primary-700 via-primary-800 to-primary-900 flex items-center justify-center">
                        <span class="text-6xl opacity-20">📰</span>
                    </div>
                @endif

                <div class="p-6">
                    {{-- Category + Stats --}}
                    <div class="flex items-center flex-wrap gap-3 mb-4">
                        <span class="text-white text-xs font-bold px-3 py-1 rounded-full {{ $categoryColors[$news->category] ?? 'bg-gray-500' }}">
                            {{ $categoryLabels[$news->category] ?? $news->category }}
                        </span>
                        <span class="text-gray-400 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $news->publish_at?->format('d F Y') }}
                        </span>
                        <span class="text-gray-400 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $news->views_count ?? 0 }} views
                        </span>
                        <span class="text-gray-400 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $comments->count() }} komentar
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-2xl font-black text-gray-900 leading-snug mb-5">
                        {{ $news->title }}
                    </h1>

                    {{-- Author --}}
                    <div class="flex items-center gap-3 pb-5 mb-5 border-b border-gray-100">
                        @if($news->creator?->avatar)
                            <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($news->creator->avatar)]) }}"
                                 alt="{{ $news->creator->name }}"
                                 class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                        @else
                            <div class="w-10 h-10 bg-primary-700 rounded-full flex items-center justify-center shrink-0">
                                <span class="text-white text-sm font-bold">
                                    {{ strtoupper(substr($news->creator?->name ?? 'A', 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $news->creator?->name }}</p>
                            <p class="text-xs text-gray-400">{{ $news->creator?->department?->name ?? 'PT MSK' }}</p>
                        </div>
                        <div class="ml-auto text-xs text-gray-400">
                            {{ $news->created_at->diffForHumans() }}
                        </div>
                    </div>

                    {{-- Content --}}
                    @if($news->content)
                        <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                            {{ $news->content }}
                        </div>
                    @else
                        <p class="text-gray-400 text-sm italic">Tidak ada konten teks.</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Comments --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-base mb-5 flex items-center gap-2">
                @if($news->isBirthday())
                    🎉 Ucapan Selamat
                @else
                    <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Komentar
                @endif
                <span class="bg-primary-50 text-primary-700 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ $comments->count() }}
                </span>
            </h3>

            {{-- Comment Form --}}
            <form method="POST" action="{{ route('news.comment', $news->id) }}" class="mb-6">
                @csrf
                <div class="flex gap-3">
                    @if(Auth::user()->avatar)
                        <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename(Auth::user()->avatar)]) }}"
                             class="w-9 h-9 rounded-full object-cover border border-gray-200 shrink-0">
                    @else
                        <div class="w-9 h-9 bg-primary-700 rounded-full flex items-center justify-center shrink-0">
                            <span class="text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <textarea name="comment" rows="3" required
                                  placeholder="{{ $news->isBirthday() ? 'Tulis ucapan selamat ulang tahun... 🎉' : 'Tulis komentarmu di sini...' }}"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700 resize-none bg-gray-50 focus:bg-white transition"></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit"
                                    class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-5 py-2 rounded-lg transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                {{ $news->isBirthday() ? 'Kirim Ucapan' : 'Kirim' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Comment List --}}
            @if($comments->count() > 0)
                <div class="space-y-4">
                    @foreach($comments as $comment)
                        <div class="flex gap-3">
                            @if($comment->user?->avatar)
                                <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($comment->user->avatar)]) }}"
                                     alt="{{ $comment->user->name }}"
                                     class="w-9 h-9 rounded-full object-cover border border-gray-200 shrink-0">
                            @else
                                <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 font-bold text-sm
                                    {{ ['bg-blue-500','bg-green-500','bg-purple-500','bg-orange-500','bg-pink-500'][($comment->user_id ?? 0) % 5] }} text-white">
                                    {{ strtoupper(substr($comment->user?->name ?? 'A', 0, 2)) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="bg-gray-50 rounded-2xl rounded-tl-none px-4 py-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-bold text-gray-800">{{ $comment->user?->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->body }}</p>
                                </div>
                                <form method="POST" action="{{ route('news.comment.like', $comment->id) }}" class="inline mt-1 ml-2">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1 text-xs text-gray-400 hover:text-primary-700 transition mt-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 1.97L7 12v8m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                        </svg>
                                        {{ $comment->likes ?? 0 }} Suka
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">{{ $news->isBirthday() ? '🎉' : '💬' }}</div>
                    <p class="text-gray-400 text-sm">
                        {{ $news->isBirthday() ? 'Belum ada ucapan. Jadilah yang pertama mengucapkan!' : 'Belum ada komentar. Jadilah yang pertama!' }}
                    </p>
                </div>
            @endif
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Info Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            @if($news->isBirthday())
                <h4 class="font-bold text-gray-700 text-sm mb-4">Info Ulang Tahun</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-semibold text-gray-700">{{ $news->birthdayUser?->name }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Departemen</span>
                        <span class="font-semibold text-gray-700">{{ $news->birthdayUser?->department?->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Jabatan</span>
                        <span class="font-semibold text-gray-700">{{ $news->birthdayUser?->position?->name ?? '-' }}</span>
                    </div>
                    @if($news->birthdayUser?->birth_date)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Tanggal Lahir</span>
                            <span class="font-semibold text-gray-700">{{ $news->birthdayUser->birth_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Umur</span>
                            <span class="font-semibold text-pink-600">{{ $news->birthdayUser->birth_date->age }} tahun 🎂</span>
                        </div>
                    @endif
                    <div class="pt-3 border-t border-gray-100 grid grid-cols-2 gap-3">
                        <div class="bg-pink-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-pink-500">{{ $news->views_count ?? 0 }}</p>
                            <p class="text-xs text-pink-400 mt-0.5">Views</p>
                        </div>
                        <div class="bg-pink-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-pink-500">{{ $comments->count() }}</p>
                            <p class="text-xs text-pink-400 mt-0.5">Ucapan</p>
                        </div>
                    </div>
                </div>
            @else
                <h4 class="font-bold text-gray-700 text-sm mb-4">Informasi Artikel</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Kategori</span>
                        <span class="font-semibold text-gray-700">{{ $categoryLabels[$news->category] ?? $news->category }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Diterbitkan</span>
                        <span class="font-semibold text-gray-700">{{ $news->publish_at?->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Penulis</span>
                        <span class="font-semibold text-gray-700">{{ $news->creator?->name }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Departemen</span>
                        <span class="font-semibold text-gray-700">{{ $news->creator?->department?->name ?? 'PT MSK' }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-100 grid grid-cols-2 gap-3">
                        <div class="bg-primary-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-primary-700">{{ $news->views_count ?? 0 }}</p>
                            <p class="text-xs text-primary-500 mt-0.5">Views</p>
                        </div>
                        <div class="bg-primary-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-primary-700">{{ $comments->count() }}</p>
                            <p class="text-xs text-primary-500 mt-0.5">Komentar</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Berita Lainnya --}}
        @php
            $others = \App\Models\News::active()
                        ->where('id', '!=', $news->id)
                        ->latest()
                        ->take(4)
                        ->get();
        @endphp

        @if($others->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h4 class="font-bold text-gray-700 text-sm mb-4">Berita Lainnya</h4>
            <div class="space-y-3">
                @foreach($others as $other)
                    <a href="{{ route('news.show', $other->id) }}" class="flex gap-3 group">
                        <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                            @if($other->image_path && !$other->isBirthday())
                                <img src="{{ route('media.serve', ['type' => explode('/', $other->image_path)[0], 'filename' => explode('/', $other->image_path)[1]]) }}"
                                     class="w-full h-full object-cover"/>
                            @elseif($other->isBirthday())
                                <span class="text-2xl">🎂</span>
                            @else
                                <span class="text-xl">📰</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 group-hover:text-primary-700 transition line-clamp-2 leading-snug">
                                {{ $other->title }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $other->publish_at?->format('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>

@endsection