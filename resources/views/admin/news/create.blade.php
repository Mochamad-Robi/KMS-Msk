@extends('layouts.app')
@section('title', 'Buat Berita')

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.news.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali
        </a>
        <h2 class="text-lg font-bold text-gray-800">Buat Berita Baru</h2>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-5 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                           placeholder="Judul berita..."/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" id="category"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="pemberitahuan" {{ old('category') === 'pemberitahuan' ? 'selected' : '' }}>Pemberitahuan</option>
                        <option value="himbauan"      {{ old('category') === 'himbauan' ? 'selected' : '' }}>Himbauan</option>
                        <option value="promosi-umkm"  {{ old('category') === 'promosi-umkm' ? 'selected' : '' }}>Promosi UMKM</option>
                    </select>
                </div>

                <div id="sub-category-wrapper" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Kategori UMKM</label>
                    <select name="sub_category" id="sub_category"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">-- Pilih Sub Kategori --</option>
                        <option value="food-beverage" {{ old('sub_category') === 'food-beverage' ? 'selected' : '' }}>Food & Beverage</option>
                        <option value="otomotif"      {{ old('sub_category') === 'otomotif' ? 'selected' : '' }}>Otomotif</option>
                        <option value="properti"      {{ old('sub_category') === 'properti' ? 'selected' : '' }}>Properti</option>
                        <option value="gadget"        {{ old('sub_category') === 'gadget' ? 'selected' : '' }}>Gadget</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Isi Konten <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="content" rows="5"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                              placeholder="Tulis isi berita...">{{ old('content') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Gambar <span class="text-gray-400 font-normal">(opsional, JPG/PNG maks 5MB)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-primary-300 transition cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400" id="image-label">Klik untuk pilih gambar</p>
                        <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" class="hidden"
                               onchange="document.getElementById('image-label').textContent = this.files[0]?.name ?? 'Pilih gambar'"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
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

            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Publikasikan
                </button>
                <a href="{{ route('admin.news.index') }}"
                   class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category');
        const subCategoryWrapper = document.getElementById('sub-category-wrapper');
        const subCategorySelect = document.getElementById('sub_category');

        function toggleSubCategory() {
            if (categorySelect.value === 'promosi-umkm') {
                subCategoryWrapper.classList.remove('hidden');
            } else {
                subCategoryWrapper.classList.add('hidden');
                subCategorySelect.value = '';
            }
        }

        categorySelect.addEventListener('change', toggleSubCategory);
        toggleSubCategory(); // jalankan saat load (untuk handle old('category') saat validasi gagal)
    });
</script>
@endpush