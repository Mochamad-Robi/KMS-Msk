@extends('layouts.app')

@section('title', 'Kelola FAQ')
@section('breadcrumb', 'Admin / FAQ')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Kelola FAQ</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Total {{ $faqs->total() }} pertanyaan</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}"
       class="inline-flex items-center gap-2 bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-primary-800 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah FAQ
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">#</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutan</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($faqs as $i => $faq)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-5 py-3 text-gray-400">{{ $faqs->firstItem() + $i }}</td>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800 dark:text-gray-100 line-clamp-1">{{ $faq->question }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 line-clamp-1">{{ strip_tags($faq->answer) }}</p>
                </td>
                <td class="px-5 py-3">
                    @php
                        $catColors = [
                            'umum'        => 'bg-gray-100 text-gray-600',
                            'hr'          => 'bg-blue-100 text-blue-700',
                            'it'          => 'bg-purple-100 text-purple-700',
                            'finance'     => 'bg-green-100 text-green-700',
                            'operasional' => 'bg-orange-100 text-orange-700',
                            'fasilitas'   => 'bg-yellow-100 text-yellow-700',
                        ];
                        $catLabels = [
                            'umum'        => 'Umum',
                            'hr'          => 'HR',
                            'it'          => 'IT',
                            'finance'     => 'Finance',
                            'operasional' => 'Operasional',
                            'fasilitas'   => 'Fasilitas',
                        ];
                    @endphp
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $catColors[$faq->category] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $catLabels[$faq->category] ?? ucfirst($faq->category) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $faq->order }}</td>
                <td class="px-5 py-3">
                    @if($faq->is_active)
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400">Aktif</span>
                    @else
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400">Nonaktif</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.faqs.edit', $faq) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.faqs.toggle', $faq) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs text-gray-500 hover:text-gray-700 font-medium px-2.5 py-1.5 rounded-lg hover:bg-gray-100 transition">
                                {{ $faq->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                              onsubmit="return confirm('Hapus FAQ ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-600 hover:text-red-800 font-medium px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500 text-sm">
                    Belum ada FAQ. <a href="{{ route('admin.faqs.create') }}" class="text-primary-700 hover:underline">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $faqs->links() }}
    </div>
</div>

@endsection