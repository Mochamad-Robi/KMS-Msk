@extends('layouts.app')
@section('title', __('app.dashboard'))

@section('content')

@php $hour = now()->hour; @endphp

    {{-- Welcome / Birthday Banner --}}
    @if($isBirthday)
        <div class="bg-gradient-to-r from-pink-600 to-primary-800 text-white rounded-2xl px-6 py-6 mb-6 shadow-lg">
            <div class="flex items-center gap-5">
                <div class="text-5xl animate-bounce">🎂</div>
                <div class="flex-1">
                    <p class="text-lg font-bold">{{ __('app.happy_birthday') }}, {{ Auth::user()->name }}!</p>
                    <p class="text-pink-200 text-sm mt-0.5">{{ __('app.birthday_message') }} 🎉</p>
                    <p class="text-pink-200 text-xs mt-1">Semoga hari ini menjadi hari yang menyenangkan dan penuh berkah!</p>
                </div>
            </div>
            @if($birthdayGift && $birthdayGift->gift_code)
                <div class="mt-5 bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-2xl">🎁</span>
                        <p class="font-semibold text-sm">Kamu punya hadiah spesial dari PT MSK!</p>
                    </div>
                    @if($birthdayGift->message)
                        <p class="text-pink-100 text-xs mb-3 leading-relaxed">{{ $birthdayGift->message }}</p>
                    @endif
                    <div class="bg-white/20 rounded-lg p-3 text-center">
                        <p class="text-xs text-pink-200 mb-1">Kode Hadiah Kamu 🎉</p>
                        <p class="font-mono font-bold text-2xl tracking-widest">{{ $birthdayGift->gift_code }}</p>
                        <p class="text-pink-200 text-xs mt-2">Tunjukkan kode ini ke HRD untuk klaim hadiah</p>
                    </div>
                </div>
            @endif
        </div>

    @else
        <div class="relative overflow-hidden rounded-3xl bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 mb-6">
            <div class="absolute inset-0 opacity-40">
                <svg class="w-full h-full" viewBox="0 0 1200 160" preserveAspectRatio="none">
                    <path d="M0,60 C250,20 450,120 700,60 C900,20 1050,90 1200,40 L1200,0 L0,0 Z" fill="#dbeafe"/>
                </svg>
            </div>
            <div class="relative flex items-center justify-between px-8 py-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white dark:bg-gray-700 shadow-sm text-sm text-gray-600 dark:text-gray-300 mb-3">
                        <span>
                            {{
                                $hour < 11 ? __('app.good_morning') :
                                ($hour < 15 ? __('app.good_afternoon') :
                                ($hour < 18 ? __('app.good_evening') :
                                __('app.good_night')))
                            }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</h1>
                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <span>{{ Auth::user()->role?->name }}</span>
                        <span>•</span>
                        <span>{{ Auth::user()->department?->name ?? 'Human Resource' }}</span>
                        <span>•</span>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>
                <img src="{{ asset('assets/banner.svg') }}" alt="Dashboard Banner"
                     class="h-24 w-auto hidden lg:block opacity-90 dark:opacity-60">
            </div>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('app.total_documents') }}</p>
                    <p class="text-2xl font-bold text-primary-800 dark:text-primary-400 mt-1">{{ $totalDocuments }}</p>
                </div>
                <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-700 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('app.new_notifications') }}</p>
                    <p class="text-2xl font-bold text-primary-800 dark:text-primary-400 mt-1">{{ $notifications->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-700 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('app.active_news') }}</p>
                    <p class="text-2xl font-bold text-primary-800 dark:text-primary-400 mt-1">{{ $newsCount }}</p>
                </div>
                <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-700 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Latest News --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Berita Terbaru</h2>
                    <a href="{{ route('news.index') }}" class="text-xs text-primary-700 dark:text-primary-400 hover:underline">Lihat semua →</a>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    @php
                        $categoryColors = [
                            'pemberitahuan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'poster'        => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                            'himbauan'      => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                            'promosi-umkm'  => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'birthday'      => 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400',
                        ];
                        $categoryLabels = [
                            'pemberitahuan' => 'Pemberitahuan',
                            'poster'        => 'Poster',
                            'himbauan'      => 'Himbauan',
                            'promosi-umkm'  => 'Promosi UMKM',
                            'birthday'      => 'Ulang Tahun 🎂',
                        ];
                        $latestNews = \App\Models\News::active()->with('birthdayUser')->latest()->take(5)->get();
                    @endphp
                    @forelse($latestNews as $item)
                        <a href="{{ route('news.show', $item->id) }}"
                           class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition group">

                            {{-- Thumbnail --}}
                            <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0 flex items-center justify-center
                                {{ $item->isBirthday() ? 'bg-gradient-to-br from-pink-400 to-rose-500' : 'bg-gray-100 dark:bg-gray-700' }}">
                                @if($item->isBirthday())
                                    @if($item->birthdayUser?->avatar)
                                        <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($item->birthdayUser->avatar)]) }}"
                                             class="w-full h-full object-cover"/>
                                    @else
                                        <span class="text-white font-black text-sm">
                                            {{ strtoupper(substr($item->birthdayUser?->name ?? '?', 0, 2)) }}
                                        </span>
                                    @endif
                                @elseif($item->image_path)
                                    <img src="{{ route('media.serve', ['type' => explode('/', $item->image_path)[0], 'filename' => explode('/', $item->image_path)[1]]) }}"
                                         class="w-full h-full object-cover"/>
                                @else
                                    <span class="text-xl">
                                        {{ $item->category === 'pemberitahuan' ? '📢' :
                                           ($item->category === 'poster' ? '🖼️' :
                                           ($item->category === 'himbauan' ? '⚠️' : '🛒')) }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $categoryColors[$item->category] ?? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $categoryLabels[$item->category] ?? $item->category }}
                                    </span>
                                </div>
                                <p class="font-medium text-gray-800 dark:text-gray-100 text-sm group-hover:text-primary-700 dark:group-hover:text-primary-400 transition line-clamp-1">
                                    {{ $item->title }}
                                </p>
                                @if($item->content)
                                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-0.5 line-clamp-1">{!! strip_tags($item->content) !!}</p>
                                @endif
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">{{ $item->publish_at?->format('d M Y') }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">Belum ada berita</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ __('app.notifications') }}</h2>
                    <a href="{{ route('notifications.index') }}" class="text-xs text-primary-700 dark:text-primary-400 hover:underline">{{ __('app.see_all') }} →</a>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($notifications as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           class="block px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <p class="font-medium text-gray-800 dark:text-gray-100 text-sm">{{ $notif->title }}</p>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5 line-clamp-1">{{ $notif->message }}</p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">{{ __('app.no_notifications') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Dokumen Terbaru --}}
    <div class="mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ __('app.latest_documents') }}</h2>
                <a href="{{ route('documents.policy') }}" class="text-xs text-primary-700 dark:text-primary-400 hover:underline">{{ __('app.see_all') }} →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-5">
                @forelse($recentDocuments as $doc)
                    <a href="{{ route('documents.show', $doc->id) }}"
                       class="border border-gray-100 dark:border-gray-700 rounded-lg p-4 hover:border-primary-300 dark:hover:border-primary-600 hover:shadow-sm transition group">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-primary-700 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate group-hover:text-primary-700 dark:group-hover:text-primary-400">
                                    {{ $doc->title }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 capitalize">{{ $doc->category }}</p>
                                @if($doc->isReadBy(Auth::user()))
                                    <span class="inline-block mt-1 text-xs text-green-600 dark:text-green-400 font-medium">{{ __('app.already_read') }}</span>
                                @else
                                    <span class="inline-block mt-1 text-xs text-orange-500 dark:text-orange-400 font-medium">{{ __('app.not_read') }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center text-gray-400 dark:text-gray-500 text-sm py-8">{{ __('app.no_documents') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal Kirim Ucapan Ulang Tahun --}}
    @php
        $birthdayWishUserId = request('birthday_wish');
        $birthdayWishUser   = $birthdayWishUserId ? \App\Models\User::find($birthdayWishUserId) : null;
    @endphp

    @if($birthdayWishUser)
        <div id="birthday-wish-modal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/50" onclick="closeBirthdayWishModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md p-6 animate-[fadeIn_0.2s_ease-out]">
                <div class="text-center mb-5">
                    <div class="text-4xl mb-2">🎂</div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Ucapkan Selamat Ulang Tahun</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Kirim ucapan untuk <span class="font-semibold text-primary-700 dark:text-primary-400">{{ $birthdayWishUser->name }}</span>
                    </p>
                </div>
                @if(session('success'))
                    <div class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm rounded-lg px-4 py-3 mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm rounded-lg px-4 py-3 mb-4">{{ session('error') }}</div>
                @endif
                @if(!session('success'))
                    <form method="POST" action="{{ route('birthday.wish.store', $birthdayWishUser->id) }}">
                        @csrf
                        <textarea name="message" rows="4" required maxlength="500"
                                  class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                  placeholder="Tulis ucapan selamat ulang tahun...">{{ old('message') }}</textarea>
                        <div class="flex gap-3 mt-4">
                            <button type="submit"
                                    class="flex-1 bg-primary-700 hover:bg-primary-800 text-white font-semibold px-4 py-2.5 rounded-lg transition text-sm">
                                Kirim Ucapan 🎉
                            </button>
                            <button type="button" onclick="closeBirthdayWishModal()"
                                    class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-semibold px-4 py-2.5 rounded-lg transition text-sm">
                                Tutup
                            </button>
                        </div>
                    </form>
                @else
                    <button type="button" onclick="closeBirthdayWishModal()"
                            class="w-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-semibold px-4 py-2.5 rounded-lg transition text-sm">
                        Tutup
                    </button>
                @endif
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    function closeBirthdayWishModal() {
        const modal = document.getElementById('birthday-wish-modal');
        if (modal) modal.remove();
        const url = new URL(window.location.href);
        url.searchParams.delete('birthday_wish');
        window.history.replaceState({}, '', url);
    }
</script>
@endpush