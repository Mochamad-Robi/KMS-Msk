@extends('layouts.app')
@section('title', 'Kelola Employee Information')

@section('content')

    @php
        $subCategoryLabels = [
            'food-beverage' => 'Food & Beverage',
            'otomotif'      => 'Otomotif',
            'properti'      => 'Properti',
            'gadget'        => 'Gadget',
        ];
    @endphp

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Kelola Employee Information</h2>
            <p class="text-gray-400 text-sm mt-0.5">Total {{ $news->total() }} berita</p>
        </div>
        <a href="{{ route('admin.news.create') }}"
           class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            + Buat Berita
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Judul</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Kategori</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Tanggal</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($news as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $item->title }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">{{ $item->creator?->name }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex flex-col gap-1 items-start">
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $item->category === 'pemberitahuan' ? 'bg-blue-50 text-blue-600' :
                                    ($item->category === 'poster' ? 'bg-purple-50 text-purple-600' :
                                    ($item->category === 'himbauan' ? 'bg-orange-50 text-orange-600' :
                                    ($item->category === 'birthday' ? 'bg-pink-50 text-pink-600' : 'bg-green-50 text-green-600'))) }}"></span>
                                    {{ ['pemberitahuan' => 'Pemberitahuan', 'poster' => 'Poster', 'himbauan' => 'Himbauan', 'promosi-umkm' => 'Promosi UMKM', 'birthday' => 'Ulang Tahun 🎂'][$item->category] ?? $item->category }}
                                </span>
                                @if($item->category === 'promosi-umkm' && $item->sub_category)
                                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                        {{ $subCategoryLabels[$item->sub_category] ?? $item->sub_category }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $item->publish_at?->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            @if($item->is_active)
                                <span class="bg-green-50 text-green-600 text-xs px-2 py-1 rounded-full">Aktif</span>
                            @else
                                <span class="bg-gray-100 text-gray-400 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('news.show', $item->id) }}"
                                   class="text-xs text-blue-500 hover:underline">Lihat</a>
                                <form method="POST" action="{{ route('admin.news.toggle', $item->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-orange-500 hover:underline">
                                        {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}"
                                      onsubmit="return confirm('Hapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada berita</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $news->links() }}
        </div>
    </div>

@endsection