@extends('layouts.app')

@section('title', 'Tambah Departemen')
@section('breadcrumb', 'Admin / Master Data / Departemen / Tambah')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-5">Tambah Departemen</h2>

        <form method="POST" action="{{ route('admin.master.departments.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Departemen <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-400 @enderror"
                       placeholder="Contoh: Human Resource">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Departemen <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('code') border-red-400 @enderror"
                       placeholder="Contoh: HR" style="text-transform:uppercase">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Departemen <span class="text-gray-400 font-normal">(opsional)</span></label>
                <select name="parent_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">— Tidak ada (departemen utama) —</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }} ({{ $parent->code }})
                        </option>
                    @endforeach
                </select>
                @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-primary-700 text-white text-sm font-medium px-5 py-2 rounded-lg hover:bg-primary-800 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.master.departments.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection