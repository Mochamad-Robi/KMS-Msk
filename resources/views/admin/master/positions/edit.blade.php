@extends('layouts.app')

@section('title', 'Edit Jabatan')
@section('breadcrumb', 'Admin / Master Data / Jabatan / Edit')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-5">Edit Jabatan</h2>

        <form method="POST" action="{{ route('admin.master.positions.update', $position) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jabatan <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $position->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Departemen <span class="text-red-500">*</span></label>
                <select name="department_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('department_id') border-red-400 @enderror">
                    <option value="">— Pilih Departemen —</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $position->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }} ({{ $dept->code }})
                        </option>
                    @endforeach
                </select>
                @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

           <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grade <span class="text-gray-400 font-normal">(opsional)</span></label>
                <select name="grade_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('grade_id') border-red-400 @enderror">
                    <option value="">— Pilih Grade —</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ old('grade_id', $position->grade_id) == $grade->id ? 'selected' : '' }}>
                            {{ $grade->label() }}
                        </option>
                    @endforeach
                </select>
                @error('grade_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-primary-700 text-white text-sm font-medium px-5 py-2 rounded-lg hover:bg-primary-800 transition">
                    Update
                </button>
                <a href="{{ route('admin.master.positions.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection