    @extends('layouts.app')
    @section('title', 'Kelola Pengumuman')

    @section('content')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Form Buat Pengumuman --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="font-semibold text-gray-800 mb-4">Buat Pengumuman</h3>

                    <form method="POST" action="{{ route('admin.announcements.store') }}">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                    placeholder="Judul pengumuman"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                                <textarea name="content" rows="4"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                        placeholder="Tulis pengumuman...">{{ old('content') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tayang</label>
                                <input type="date" name="publish_at" value="{{ old('publish_at', today()->format('Y-m-d')) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Berakhir <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <input type="date" name="expire_at" value="{{ old('expire_at') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </div>
                        </div>

                        <button type="submit"
                                class="mt-4 w-full bg-primary-700 hover:bg-primary-800 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                            Publikasikan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Daftar Pengumuman --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Semua Pengumuman</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($announcements as $ann)
                            <div class="px-5 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="font-medium text-gray-800 text-sm">{{ $ann->title }}</p>
                                            @if($ann->is_active)
                                                <span class="bg-green-50 text-green-600 text-xs px-2 py-0.5 rounded-full">Aktif</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-400 text-xs px-2 py-0.5 rounded-full">Nonaktif</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-500 text-xs line-clamp-2">{{ $ann->content }}</p>
                                        <p class="text-gray-400 text-xs mt-1">
                                            Tayang: {{ $ann->publish_at?->format('d M Y') }}
                                            @if($ann->expire_at)
                                                — Berakhir: {{ $ann->expire_at->format('d M Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <form method="POST" action="{{ route('admin.announcements.toggle', $ann->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs text-blue-500 hover:underline">
                                                {{ $ann->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}"
                                            onsubmit="return confirm('Hapus pengumuman ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-gray-400 text-sm">
                                Belum ada pengumuman
                            </div>
                        @endforelse
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $announcements->links() }}
                    </div>
                </div>
            </div>

        </div>

    @endsection