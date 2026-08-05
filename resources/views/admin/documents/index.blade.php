@extends('layouts.app')
@section('title', 'Kelola Dokumen')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Kelola Dokumen</h2>
            <p class="text-gray-400 text-sm mt-0.5">Total {{ $documents->total() }} dokumen</p>
        </div>
        <a href="{{ route('admin.documents.create') }}"
           class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            + Upload Dokumen
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Judul</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Kategori</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Departemen</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Min Grade</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Acknowledge</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($documents as $doc)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ $doc->title }}</p>
                            <p class="text-gray-400 text-xs">{{ $doc->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-xs px-2 py-1 rounded-full capitalize">
                                {{ $doc->category }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                            {{ $doc->department?->name ?? 'Semua' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                            {{ $doc->minGrade?->label() ?? '-' }}
                        </td>
                        <td class="px-5 py-3">
                            @if($doc->requires_acknowledgement)
                                <a href="{{ route('admin.documents.acknowledgements', $doc->id) }}"
                                   class="inline-flex items-center gap-1 text-xs text-primary-700 dark:text-primary-400 font-medium hover:underline">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    {{ $doc->acknowledgedCount() }} acknowledge
                                </a>
                            @else
                                <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($doc->is_active)
                                <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full">Aktif</span>
                            @else
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.documents.toggle', $doc->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-blue-500 hover:underline">
                                        {{ $doc->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.documents.destroy', $doc->id) }}"
                                      onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">
                            Belum ada dokumen
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $documents->links() }}
        </div>
    </div>

@endsection