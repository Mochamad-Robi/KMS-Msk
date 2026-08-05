@extends('layouts.app')
@section('title', 'Bookmark Saya')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">{{ __('app.my_bookmark') }}</h2>
            <p class="text-gray-400 text-sm mt-0.5">{{ $bookmarks->count() }} {{ __('app.saved_document') }}</p>
        </div>
    </div>

    @if($bookmarks->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-16 text-center">
            <div class="text-5xl mb-3">🔖</div>
            <p class="text-gray-500 font-medium mb-1">{{ __('app.my_bookmark') }}</p>
            <p class="text-gray-400 text-sm">{{ __('app.Save_important_documents_by_clicking_the_Save_button_when_you_open_them.') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($bookmarks as $bookmark)
                @php $doc = $bookmark->document; @endphp
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md hover:border-primary-200 transition">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $doc->title }}</p>
                            <p class="text-gray-400 text-xs mt-0.5 capitalize">{{ $doc->category }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">{{ $doc->department?->name ?? 'Semua Dept' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-50">
                        <a href="{{ route('documents.show', $doc->id) }}"
                           class="flex-1 bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition text-center">
                            Buka Dokumen
                        </a>
                        <form method="POST" action="{{ route('bookmarks.toggle', $doc->id) }}">
                            @csrf
                            <button type="submit"
                                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition"
                                    title="Hapus bookmark">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <p class="text-gray-300 text-xs mt-2">
                        Disimpan {{ $bookmark->created_at->diffForHumans() }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

@endsection