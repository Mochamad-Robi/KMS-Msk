@extends('layouts.app')
@section('title', 'Semua Notifikasi')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800">Semua Notifikasi</h2>
        @if(Auth::user()->unreadNotificationsCount() > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit"
                        class="text-sm text-primary-700 border border-primary-700 px-4 py-2 rounded-lg hover:bg-primary-50 transition">
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-50">
            @forelse($notifications as $notif)
                <a href="{{ route('notifications.read', $notif->id) }}"
                   class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 transition {{ $notif->is_read ? 'opacity-60' : '' }}">

                    <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0
                        {{ $notif->type === 'birthday' ? 'bg-pink-100' :
                           ($notif->type === 'document' ? 'bg-blue-100' :
                           ($notif->type === 'news' ? 'bg-yellow-100' :
                           ($notif->type === 'reminder' ? 'bg-orange-100' : 'bg-primary-50'))) }}">
                        <span class="text-base">
                            {{ $notif->type === 'birthday' ? '🎂' :
                               ($notif->type === 'document' ? '📄' :
                               ($notif->type === 'news' ? '📰' :
                               ($notif->type === 'reminder' ? '📋' : '📢'))) }}
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 {{ !$notif->is_read ? 'font-semibold' : 'font-medium' }}">
                            {{ $notif->title }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $notif->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>

                    @if(!$notif->is_read)
                        <div class="w-2 h-2 bg-primary-700 rounded-full mt-2 shrink-0"></div>
                    @endif
                </a>
            @empty
                <div class="px-5 py-12 text-center text-gray-400 text-sm">
                    Belum ada notifikasi
                </div>
            @endforelse
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
    </div>

@endsection