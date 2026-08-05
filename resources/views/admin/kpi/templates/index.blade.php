@extends('layouts.app')
@section('title', 'Template Kuantitatif KPI')

@section('content')

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Template Penilaian Kuantitatif</h2>
        <p class="text-gray-400 text-sm mt-0.5">
            Indikator ini berlaku global untuk semua karyawan yang dinilai. Total bobot idealnya 100%.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Total Bobot Indicator --}}
    <div class="mb-5 flex items-center gap-3 p-4 rounded-xl border
        {{ $totalWeight == 100 ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-700' : 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-700' }}">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0
            {{ $totalWeight == 100 ? 'bg-green-100 dark:bg-green-800' : 'bg-yellow-100 dark:bg-yellow-800' }}">
            <span class="text-sm font-bold {{ $totalWeight == 100 ? 'text-green-600 dark:text-green-300' : 'text-yellow-600 dark:text-yellow-300' }}">
                {{ $totalWeight }}%
            </span>
        </div>
        <div>
            <p class="text-sm font-semibold {{ $totalWeight == 100 ? 'text-green-700 dark:text-green-300' : 'text-yellow-700 dark:text-yellow-300' }}">
                Total Bobot Indikator Aktif: {{ $totalWeight }}%
            </p>
            <p class="text-xs {{ $totalWeight == 100 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                @if($totalWeight == 100)
                    Sudah pas 100%. Template siap dipakai.
                @else
                    Sebaiknya total bobot 100% agar kalkulasi akurat. Selisih: {{ 100 - $totalWeight }}%
                @endif
            </p>
        </div>
    </div>

    {{-- Form Tambah Indikator --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 mb-6">
        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">+ Tambah Indikator Baru</p>
        <form method="POST" action="{{ route('admin.kpi.templates.store') }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Indikator</label>
                <input type="text" name="name" placeholder="Contoh: Market Share"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Bobot (%)</label>
                <input type="number" name="weight_percent" step="0.01" min="0" max="100" placeholder="30"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
            </div>
            <button type="submit"
                    class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm whitespace-nowrap">
                + Tambah
            </button>
        </form>
    </div>

    {{-- Daftar Indikator --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium w-12">No</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Nama Indikator</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Bobot</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($templates as $i => $template)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.kpi.templates.update', $template->id) }}"
                                  class="flex items-center gap-2" id="form-{{ $template->id }}">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $template->name }}"
                                       class="border-0 bg-transparent font-medium text-gray-700 dark:text-gray-200 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 rounded px-2 py-1 -ml-2 w-full"/>
                        </td>
                        <td class="px-5 py-3">
                                <input type="number" name="weight_percent" step="0.01" value="{{ $template->weight_percent }}"
                                       class="border-0 bg-transparent text-gray-600 dark:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 rounded px-2 py-1 w-20"/>%
                            </form>
                        </td>
                        <td class="px-5 py-3">
                            @if($template->is_active)
                                <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">Aktif</span>
                            @else
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs px-2 py-1 rounded-full font-medium">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <button type="submit" form="form-{{ $template->id }}" class="text-xs text-blue-500 hover:underline">
                                    Simpan
                                </button>

                                <form method="POST" action="{{ route('admin.kpi.templates.toggle', $template->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-orange-500 hover:underline">
                                        {{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.kpi.templates.destroy', $template->id) }}"
                                      onsubmit="return confirm('Hapus indikator ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                            Belum ada indikator. Tambahkan minimal 1 indikator di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection