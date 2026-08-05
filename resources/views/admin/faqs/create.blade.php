@extends('layouts.app')

@section('title', 'Tambah FAQ')
@section('breadcrumb', 'Admin / FAQ / Tambah')

@section('content')

<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-5">Tambah FAQ</h2>

        <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Pertanyaan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="question" value="{{ old('question') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('question') border-red-400 @enderror"
                       placeholder="Contoh: Bagaimana cara mengajukan cuti?">
                @error('question') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Jawaban <span class="text-red-500">*</span>
                </label>
                <textarea name="answer" rows="5"
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('answer') border-red-400 @enderror"
                          placeholder="Tulis jawaban lengkap di sini...">{{ old('answer') }}</textarea>
                @error('answer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('category') border-red-400 @enderror">
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Urutan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <p class="text-xs text-gray-400 mt-1">Angka kecil tampil lebih atas</p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-primary-700 text-white text-sm font-medium px-5 py-2 rounded-lg hover:bg-primary-800 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.faqs.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection