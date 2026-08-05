@extends('layouts.app')

@section('title', 'FAQ')
@section('breadcrumb', 'FAQ & Pertanyaan Umum')

@section('content')

{{-- Header --}}
<div class="bg-primary-700 rounded-2xl px-8 py-8 mb-6 text-white">
    <h2 class="text-xl font-bold mb-1">FAQ & Pertanyaan Umum</h2>
    <p class="text-primary-200 text-sm mb-5">Temukan jawaban atas pertanyaan yang sering ditanyakan</p>

    {{-- Search --}}
    <form method="GET" action="{{ route('faq.index') }}" class="flex gap-2">
        <input type="hidden" name="category" value="{{ $activeCategory }}">
        <div class="flex-1 relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $search }}"
                   placeholder="Cari pertanyaan..."
                   class="w-full pl-9 pr-4 py-2.5 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-white/50">
        </div>
        <button type="submit"
                class="bg-white text-primary-700 font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-primary-50 transition">
            Cari
        </button>
        @if($search)
            <a href="{{ route('faq.index') }}"
               class="bg-primary-600 text-white text-sm px-4 py-2.5 rounded-lg hover:bg-primary-500 transition">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="flex gap-6">

    {{-- Sidebar Kategori --}}
    <div class="w-48 shrink-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</p>
            </div>
            <nav class="p-2">
                <a href="{{ route('faq.index', ['q' => $search]) }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium
                          {{ $activeCategory === 'all' ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Semua
                </a>
                @foreach($categories as $value => $label)
                    @php
                        $count = \App\Models\Faq::active()->where('category', $value)->count();
                    @endphp
                    @if($count > 0)
                        <a href="{{ route('faq.index', ['category' => $value, 'q' => $search]) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition font-medium
                                  {{ $activeCategory === $value ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span>{{ $label }}</span>
                            <span class="text-xs {{ $activeCategory === $value ? 'text-primary-200' : 'text-gray-400' }}">
                                {{ $count }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>

    {{-- FAQ List --}}
    <div class="flex-1">
        @if($search)
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Hasil pencarian untuk <span class="font-semibold text-gray-700 dark:text-gray-200">"{{ $search }}"</span>
                — {{ $faqs->flatten()->count() }} pertanyaan ditemukan
            </p>
        @endif

        @forelse($faqs as $category => $items)
            @php
                $catLabels = [
                    'umum'        => 'Umum',
                    'hr'          => 'Human Resource',
                    'it'          => 'Information Technology',
                    'finance'     => 'Finance & Accounting',
                    'operasional' => 'Operasional',
                    'fasilitas'   => 'Fasilitas & GA',
                ];
                $catColors = [
                    'umum'        => 'bg-gray-100 text-gray-600',
                    'hr'          => 'bg-blue-100 text-blue-700',
                    'it'          => 'bg-purple-100 text-purple-700',
                    'finance'     => 'bg-green-100 text-green-700',
                    'operasional' => 'bg-orange-100 text-orange-700',
                    'fasilitas'   => 'bg-yellow-100 text-yellow-700',
                ];
            @endphp

            {{-- Category Header --}}
            @if($activeCategory === 'all')
                <div class="flex items-center gap-3 mb-3 mt-5 first:mt-0">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $catColors[$category] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $catLabels[$category] ?? ucfirst($category) }}
                    </span>
                    <div class="flex-1 h-px bg-gray-100 dark:bg-gray-700"></div>
                </div>
            @endif

            {{-- Accordion Items --}}
            <div class="space-y-2">
                @foreach($items as $faq)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button onclick="toggleFaq({{ $faq->id }})"
                                class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <span class="font-medium text-gray-800 dark:text-gray-100 text-sm pr-4">{{ $faq->question }}</span>
                            <svg id="arrow-{{ $faq->id }}"
                                 class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="faq-{{ $faq->id }}" class="hidden px-5 pb-4 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-3 leading-relaxed whitespace-pre-line">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-8 py-16 text-center">
                <svg class="w-12 h-12 text-gray-200 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-400 dark:text-gray-500 text-sm">
                    @if($search)
                        Tidak ada hasil untuk "{{ $search }}"
                    @else
                        Belum ada FAQ tersedia
                    @endif
                </p>
            </div>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script>
function toggleFaq(id) {
    const content = document.getElementById('faq-' + id);
    const arrow   = document.getElementById('arrow-' + id);
    content.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

// Auto buka kalau ada search
@if($search)
    document.querySelectorAll('[id^="faq-"]').forEach(el => {
        el.classList.remove('hidden');
        const id    = el.id.replace('faq-', '');
        const arrow = document.getElementById('arrow-' + id);
        if (arrow) arrow.classList.add('rotate-180');
    });
@endif
</script>
@endpush