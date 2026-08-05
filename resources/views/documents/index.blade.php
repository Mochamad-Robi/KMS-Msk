@extends('layouts.app')
@section('title', $title)

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">{{ $title }}</h2>
            <p class="text-gray-400 text-sm mt-0.5">{{ $documents->count() }} dokumen tersedia</p>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.documents.create') }}"
               class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                + Upload Dokumen
            </a>
        @endif
    </div>

    {{-- Pill Filter Sub Kategori (khusus Rules & Responsibilities) --}}
    @if(!empty($subCategories))
    <div class="flex items-center gap-2 mb-5 flex-wrap">
        <a href="{{ route('documents.roles') }}"
           class="px-4 py-2 rounded-full text-sm font-semibold transition border
                  {{ ($activeSubCategory ?? 'all') === 'all' ? 'bg-primary-700 text-white border-primary-700' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200' }}">
            Semua
        </a>
        @foreach($subCategories as $key => $label)
            <a href="{{ route('documents.roles', ['sub_category' => $key]) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition border
                      {{ ($activeSubCategory ?? 'all') === $key ? 'bg-primary-700 text-white border-primary-700' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    @endif

    {{-- Search Bar --}}
    @if(!$documents->isEmpty())
    <div class="mb-5">
        <input type="text" id="doc-search"
            placeholder="Cari dokumen..."
            onkeyup="filterDocs()"
            class="w-full max-w-sm border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
    </div>
    @endif

    @if($documents->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-400 text-sm">Belum ada dokumen tersedia</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="doc-grid">
            @foreach($documents as $doc)
                <a href="{{ route('documents.show', $doc->id) }}"
                   data-doc-title="{{ strtolower($doc->title) }}"
                   class="bg-white border border-gray-100 rounded-xl p-5 hover:border-primary-300 hover:shadow-md transition group">

                    {{-- Icon --}}
                    <div class="w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Sub Kategori badge --}}
                    @if($doc->sub_category)
                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full mb-2 bg-primary-50 text-primary-700">
                            {{ \App\Models\Document::SUB_CATEGORIES_ROLES[$doc->sub_category] ?? $doc->sub_category }}
                        </span>
                    @endif

                    {{-- Info --}}
                    <p class="font-semibold text-gray-800 text-sm group-hover:text-primary-700 leading-snug">
                        {{ $doc->title }}
                    </p>
                    <p class="text-gray-400 text-xs mt-1 line-clamp-2">
                        {{ $doc->description ?? 'Tidak ada deskripsi' }}
                    </p>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                        <span class="text-xs text-gray-400">
                            {{ $doc->created_at->format('d M Y') }}
                        </span>
                        @if($doc->isReadBy(Auth::user()))
                            <span class="text-xs text-green-600 font-medium">✓ Sudah dibaca</span>
                        @else
                            <span class="text-xs text-orange-500 font-medium">● Belum dibaca</span>
                        @endif
                    </div>

                </a>
            @endforeach
        </div>
    @endif

@endsection

@push('scripts')
<script>
function filterDocs() {
    const search = document.getElementById('doc-search').value.toLowerCase();
    const cards  = document.querySelectorAll('[data-doc-title]');
    cards.forEach(card => {
        const title = card.getAttribute('data-doc-title').toLowerCase();
        card.style.display = title.includes(search) ? '' : 'none';
    });
}
</script>
@endpush