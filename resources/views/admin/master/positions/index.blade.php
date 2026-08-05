@extends('layouts.app')

@section('title', 'Master Jabatan')
@section('breadcrumb', 'Admin / Master Data / Jabatan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-800">Master Jabatan</h2>
        <p class="text-sm text-gray-500 mt-0.5">Kelola data jabatan PT MSK</p>
    </div>
    <a href="{{ route('admin.master.positions.create') }}"
       class="inline-flex items-center gap-2 bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-primary-800 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Jabatan
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Jabatan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Departemen</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grade</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Karyawan</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($positions as $i => $position)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $position->name }}</td>
                <td class="px-4 py-3 text-gray-500">
                    {{ $position->department?->name ?? '—' }}
                </td>
                <td class="px-4 py-3">
                    @if($position->grade)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-primary-100 text-primary-700">
                            {{ $position->grade->label() }}
                        </span>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500">
                    {{ $position->users->count() }} orang
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.master.positions.edit', $position) }}"
                           class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.master.positions.destroy', $position) }}"
                              onsubmit="return confirm('Hapus jabatan {{ $position->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                    Belum ada data jabatan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection