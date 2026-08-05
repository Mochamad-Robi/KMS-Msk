@extends('layouts.app')

@section('title', 'Edit FAQ')
@section('breadcrumb', 'Admin / FAQ / Edit')

@section('content')

<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-5">Edit FAQ</h2>

        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Pertanyaan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="question" value="{{ old('question', $faq->question) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('question') border-red-400 @enderror">
                @error('question') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Jawaban <span class="text-red-500">*</span>
                </label>
                <textarea name="answer" rows="5"
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('answer') border-red-400 @enderror">{{ old('answer', $faq->answer) }}</textarea>
                @error('answer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category', $faq->category) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan</label>
                    <input type="number" name="order" value="{{ old('order', $faq->order) }}" min="0"
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       class="w-4 h-4 text-primary-700 border-gray-300 rounded"
                       {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    FAQ Aktif (tampil ke user)
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-primary-700 text-white text-sm font-medium px-5 py-2 rounded-lg hover:bg-primary-800 transition">
                    Update
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