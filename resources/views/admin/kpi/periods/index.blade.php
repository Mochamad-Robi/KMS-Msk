@extends('layouts.app')
@section('title', 'Periode KPI')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Periode KPI</h2>
            <p class="text-gray-400 text-sm mt-0.5">Kelola periode penilaian per quartal</p>
        </div>
        <a href="{{ route('admin.kpi.periods.create') }}"
           class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            + Tambah Periode
        </a>
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

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Periode</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Tanggal Mulai</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Tanggal Selesai</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 dark:text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($periods as $period)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-200">
                            {{ $period->quartal }} {{ $period->year }}
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                            {{ $period->start_date->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                            {{ $period->end_date->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3">
                            @if($period->is_open)
                                <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">
                                    ● Dibuka
                                </span>
                            @else
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs px-2 py-1 rounded-full font-medium">
                                    ● Ditutup
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('admin.kpi.periods.toggle', $period->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="text-xs font-medium hover:underline {{ $period->is_open ? 'text-orange-500' : 'text-green-600' }}">
                                        {{ $period->is_open ? 'Tutup Periode' : 'Buka Periode' }}
                                    </button>
                                </form>

                                @if($period->evaluations()->count() === 0)
                                    <form method="POST" action="{{ route('admin.kpi.periods.destroy', $period->id) }}"
                                          onsubmit="return confirm('Hapus periode ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                            Belum ada periode KPI. Klik "+ Tambah Periode" untuk membuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection