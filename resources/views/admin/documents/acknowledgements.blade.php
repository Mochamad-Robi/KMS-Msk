@extends('layouts.app')

@section('title', 'Acknowledgement — ' . $document->title)
@section('breadcrumb', 'Admin / Dokumen / Acknowledgement')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('admin.documents.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali ke Dokumen
        </a>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $document->title }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 capitalize">{{ $document->category }}</p>
    </div>
</div>

{{-- Summary Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total User</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $statuses->count() }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Sudah Acknowledge</p>
        <p class="text-2xl font-bold text-green-600">{{ $acknowledgedCount }}</p>
        <div class="mt-2 w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
            <div class="bg-green-500 h-1.5 rounded-full"
                 style="width: {{ $statuses->count() > 0 ? round(($acknowledgedCount / $statuses->count()) * 100) : 0 }}%">
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Belum Acknowledge</p>
        <p class="text-2xl font-bold text-red-500">{{ $pendingCount }}</p>
        <div class="mt-2 w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
            <div class="bg-red-400 h-1.5 rounded-full"
                 style="width: {{ $statuses->count() > 0 ? round(($pendingCount / $statuses->count()) * 100) : 0 }}%">
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="flex items-center gap-3 mb-4">
    <button onclick="filterTable('all')" id="btn-all"
            class="filter-btn text-xs font-medium px-3 py-1.5 rounded-lg bg-primary-700 text-white transition">
        Semua ({{ $statuses->count() }})
    </button>
    <button onclick="filterTable('acknowledged')" id="btn-acknowledged"
            class="filter-btn text-xs font-medium px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 transition">
        Sudah ({{ $acknowledgedCount }})
    </button>
    <button onclick="filterTable('pending')" id="btn-pending"
            class="filter-btn text-xs font-medium px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 transition">
        Belum ({{ $pendingCount }})
    </button>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm" id="ack-table">
        <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Karyawan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Departemen</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibaca</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Acknowledge</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($statuses as $i => $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                data-status="{{ $item['acknowledged_at'] ? 'acknowledged' : 'pending' }}">
                <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-700 text-white text-xs font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($item['user']->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ $item['user']->name }}</p>
                            <p class="text-xs text-gray-400">{{ $item['user']->employee_id }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                    {{ $item['user']->department?->name ?? '—' }}
                </td>
                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                    {{ $item['read_at'] ? $item['read_at']->format('d M Y H:i') : '—' }}
                </td>
                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                    {{ $item['acknowledged_at'] ? $item['acknowledged_at']->format('d M Y H:i') : '—' }}
                </td>
                <td class="px-4 py-3">
                    @if($item['acknowledged_at'])
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Acknowledge
                        </span>
                    @elseif($item['read_at'])
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sudah Baca
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Belum Baca
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                    Belum ada data.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
function filterTable(type) {
    const rows    = document.querySelectorAll('#ack-table tbody tr[data-status]');
    const buttons = document.querySelectorAll('.filter-btn');

    // Reset semua button
    buttons.forEach(btn => {
        btn.classList.remove('bg-primary-700', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-600');
    });

    // Active button
    const activeBtn = document.getElementById('btn-' + type);
    activeBtn.classList.add('bg-primary-700', 'text-white');
    activeBtn.classList.remove('bg-gray-100', 'text-gray-600');

    // Filter rows
    rows.forEach(row => {
        if (type === 'all' || row.dataset.status === type) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}
</script>
@endpush