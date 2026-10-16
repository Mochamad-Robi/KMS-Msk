@extends('layouts.app')
@section('title', 'Kelola Employee Information')

@section('content')

<style>
  /* Ukuran modal dikunci lewat CSS supaya tidak bergantung build Tailwind */
  #news-modal{ padding:16px; }
  #news-modal-box{
    width:100%;
    max-width:620px !important;
    max-height:88vh;
    border-radius:18px !important;
    margin:auto;
  }
  @media (max-width:640px){
    #news-modal{ padding:0; align-items:flex-end; }
    #news-modal-box{
      max-width:100% !important;
      max-height:92vh;
      border-radius:22px 22px 0 0 !important;
    }
  }
</style>

    @php
        $subCategoryLabels = [
            'food-beverage' => 'Food & Beverage',
            'otomotif'      => 'Otomotif',
            'properti'      => 'Properti',
            'gadget'        => 'Gadget',
        ];
        $categoryLabels = [
            'pemberitahuan' => 'Pemberitahuan',
            'himbauan'      => 'Himbauan',
            'promosi-umkm'  => 'Promosi UMKM',
            'birthday'      => 'Ulang Tahun',
        ];
        $categoryColors = [
            'pemberitahuan' => 'bg-blue-50 text-blue-600',
            'himbauan'      => 'bg-orange-50 text-orange-600',
            'promosi-umkm'  => 'bg-green-50 text-green-600',
            'birthday'      => 'bg-pink-50 text-pink-600',
        ];
    @endphp

    <div class="mb-6 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Kelola Employee Information</h2>
            <p class="text-gray-400 text-sm mt-0.5">Total {{ $news->total() }} berita</p>
        </div>
        <button type="button" onclick="openNewsModal()"
                class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shrink-0">
            + Buat Berita
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Judul</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Kategori</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Tanggal</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Status</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($news as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-800 dark:text-gray-100">{{ $item->title }}</p>
                                <p class="text-gray-400 text-xs mt-0.5">{{ $item->creator?->name }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="text-xs px-2 py-1 rounded-full whitespace-nowrap
                                        {{ $categoryColors[$item->category] ?? 'bg-gray-100 text-gray-500' }}">
                                        {{ $categoryLabels[$item->category] ?? $item->category }}
                                        @if($item->category === 'birthday') &#127874; @endif
                                    </span>
                                    @if($item->category === 'promosi-umkm' && $item->sub_category)
                                        <span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $subCategoryLabels[$item->sub_category] ?? $item->sub_category }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $item->publish_at?->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                @if($item->is_active)
                                    <span class="bg-green-50 text-green-600 text-xs px-2 py-1 rounded-full whitespace-nowrap">Aktif</span>
                                @else
                                    <span class="bg-gray-100 text-gray-400 text-xs px-2 py-1 rounded-full whitespace-nowrap">Nonaktif</span>
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
        </div>
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $news->links() }}
        </div>
    </div>

    {{-- ===================== MODAL BUAT BERITA ===================== --}}
    <div id="news-modal" class="hidden fixed inset-0 z-50 items-center justify-center">
        <div class="absolute inset-0 bg-black/55 backdrop-blur-[2px]" onclick="closeNewsModal()"></div>

        <div id="news-modal-box"
             class="relative bg-white dark:bg-gray-800 overflow-y-auto shadow-2xl transition-all duration-300 opacity-0"
             style="transform:translateY(20px) scale(.96)">

            {{-- Header --}}
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-5 sm:px-6 py-4 flex items-center justify-between gap-3 z-10">
                <div class="min-w-0">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base">Buat Berita Baru</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi form di bawah untuk mempublikasikan berita</p>
                </div>
                <button type="button" onclick="closeNewsModal()"
                        class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center shrink-0 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-5 sm:px-6 py-5">

                @if($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" id="news-form">
                    @csrf

                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                   placeholder="Judul berita..."/>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                                <select name="category" id="category" required
                                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="pemberitahuan" {{ old('category') === 'pemberitahuan' ? 'selected' : '' }}>Pemberitahuan</option>
                                    <option value="himbauan"      {{ old('category') === 'himbauan' ? 'selected' : '' }}>Himbauan</option>
                                    <option value="promosi-umkm"  {{ old('category') === 'promosi-umkm' ? 'selected' : '' }}>Promosi UMKM</option>
                                </select>
                            </div>

                            <div id="sub-category-wrapper" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sub Kategori UMKM</label>
                                <select name="sub_category" id="sub_category"
                                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                    <option value="">-- Pilih Sub Kategori --</option>
                                    <option value="food-beverage" {{ old('sub_category') === 'food-beverage' ? 'selected' : '' }}>Food &amp; Beverage</option>
                                    <option value="otomotif"      {{ old('sub_category') === 'otomotif' ? 'selected' : '' }}>Otomotif</option>
                                    <option value="properti"      {{ old('sub_category') === 'properti' ? 'selected' : '' }}>Properti</option>
                                    <option value="gadget"        {{ old('sub_category') === 'gadget' ? 'selected' : '' }}>Gadget</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Isi Konten <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <textarea name="content" rows="5"
                                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                      placeholder="Tulis isi berita...">{{ old('content') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gambar <span class="text-gray-400 font-normal">(opsional, JPG/PNG maks 5MB)</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-5 text-center hover:border-primary-300 transition cursor-pointer"
                                 onclick="document.getElementById('image').click()">
                                <div id="image-preview-wrap" class="hidden mb-3">
                                    <img id="image-preview" src="" alt=""
                                         class="max-h-40 mx-auto rounded-lg object-contain">
                                </div>
                                <svg id="image-icon" class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400" id="image-label">Klik untuk pilih gambar</p>
                                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" class="hidden"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Tayang</label>
                                <input type="date" name="publish_at" value="{{ old('publish_at', today()->format('Y-m-d')) }}"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Berakhir <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <input type="date" name="expire_at" value="{{ old('expire_at') }}"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                                <p class="text-[11px] text-gray-400 mt-1">Kosongkan kalau berita tidak pernah kedaluwarsa</p>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 px-5 sm:px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeNewsModal()"
                        class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold px-5 py-2.5 rounded-lg transition text-sm">
                    Batal
                </button>
                <button type="submit" form="news-form" id="news-submit"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm flex items-center gap-2">
                    <span id="news-submit-label">Publikasikan</span>
                    <svg id="news-submit-spin" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    const newsModal = document.getElementById('news-modal');
    const newsBox   = document.getElementById('news-modal-box');

    function openNewsModal() {
        newsModal.classList.remove('hidden');
        newsModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(function () {
            newsBox.classList.remove('opacity-0');
            newsBox.style.transform = 'translateY(0) scale(1)';
        });
    }

    function closeNewsModal() {
        newsBox.classList.add('opacity-0');
        newsBox.style.transform = 'translateY(20px) scale(.96)';
        document.body.style.overflow = '';

        setTimeout(function () {
            newsModal.classList.add('hidden');
            newsModal.classList.remove('flex');
        }, 280);
    }

    // Tutup dengan ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !newsModal.classList.contains('hidden')) closeNewsModal();
    });

    // Sub-kategori hanya muncul untuk Promosi UMKM
    (function () {
        const categorySelect     = document.getElementById('category');
        const subCategoryWrapper = document.getElementById('sub-category-wrapper');
        const subCategorySelect  = document.getElementById('sub_category');

        function toggleSubCategory() {
            if (categorySelect.value === 'promosi-umkm') {
                subCategoryWrapper.classList.remove('hidden');
            } else {
                subCategoryWrapper.classList.add('hidden');
                subCategorySelect.value = '';
            }
        }

        categorySelect.addEventListener('change', toggleSubCategory);
        toggleSubCategory();
    })();

    // Pratinjau gambar
    document.getElementById('image').addEventListener('change', function () {
        const file    = this.files[0];
        const label   = document.getElementById('image-label');
        const wrap    = document.getElementById('image-preview-wrap');
        const preview = document.getElementById('image-preview');
        const icon    = document.getElementById('image-icon');

        if (!file) {
            label.textContent = 'Klik untuk pilih gambar';
            wrap.classList.add('hidden');
            icon.classList.remove('hidden');
            return;
        }

        label.textContent = file.name;
        icon.classList.add('hidden');
        wrap.classList.remove('hidden');

        const reader = new FileReader();
        reader.onload = function (e) { preview.src = e.target.result; };
        reader.readAsDataURL(file);
    });

    // Cegah double-submit
    document.getElementById('news-form').addEventListener('submit', function () {
        const btn = document.getElementById('news-submit');
        btn.disabled = true;
        document.getElementById('news-submit-label').textContent = 'Menyimpan';
        document.getElementById('news-submit-spin').classList.remove('hidden');
    });

    // Kalau validasi gagal, buka modal lagi dengan isian sebelumnya
    @if($errors->any() || old('title'))
        openNewsModal();
    @endif
</script>
@endpush