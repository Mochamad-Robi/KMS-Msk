@extends('layouts.app')

@section('title', 'Analytics')
@section('breadcrumb', 'Admin / Analytics')

@section('content')

{{-- Summary Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">User Aktif</p>
        <p class="text-2xl font-bold text-primary-700">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Total Dokumen</p>
        <p class="text-2xl font-bold text-primary-700">{{ $stats['total_documents'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Total Berita</p>
        <p class="text-2xl font-bold text-primary-700">{{ $stats['total_news'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Total Aktivitas</p>
        <p class="text-2xl font-bold text-primary-700">{{ $stats['total_activities'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Baca Dokumen Hari Ini</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['reads_today'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">User Aktif Hari Ini</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['active_today'] }}</p>
    </div>
</div>

{{-- Grafik Aktivitas 7 Hari --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-gray-700 mb-4">Aktivitas 7 Hari Terakhir</h3>
    <canvas id="activityChart" height="80"></canvas>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Dokumen Paling Banyak Dibaca --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Dokumen Paling Banyak Dibaca</h3>
        @forelse($topDocuments as $i => $item)
            <div class="flex items-center gap-3 mb-3">
                <span class="w-6 h-6 rounded-full bg-primary-50 text-primary-700 text-xs font-bold flex items-center justify-center shrink-0">
                    {{ $i + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">
                        {{ $item->document?->title ?? 'Dokumen dihapus' }}
                    </p>
                    <p class="text-xs text-gray-400 capitalize">{{ $item->document?->category ?? '—' }}</p>
                </div>
                <span class="text-sm font-semibold text-primary-700 shrink-0">{{ $item->total }}x</span>
            </div>
            @if(!$loop->last)
                <div class="mb-3">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-primary-700 h-1.5 rounded-full"
                             style="width: {{ $topDocuments->max('total') > 0 ? round(($item->total / $topDocuments->max('total')) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
        @endforelse
    </div>

    {{-- User Paling Aktif --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">User Paling Aktif</h3>
        @forelse($topUsers as $i => $item)
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-primary-700 text-white text-xs font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($item->user?->name ?? '?', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">
                        {{ $item->user?->name ?? 'User dihapus' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $item->user?->employee_id ?? '—' }}</p>
                </div>
                <span class="text-sm font-semibold text-primary-700 shrink-0">{{ $item->total }} aktivitas</span>
            </div>
            @if(!$loop->last)
                <div class="mb-3">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-primary-700 h-1.5 rounded-full"
                             style="width: {{ $topUsers->max('total') > 0 ? round(($item->total / $topUsers->max('total')) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
        @endforelse
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Departemen Paling Aktif --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Departemen Paling Aktif</h3>
        @forelse($topDepartments as $i => $item)
            <div class="flex items-center gap-3 mb-3">
                <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                    {{ $i + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">
                        {{ $item->department?->name ?? 'Dept dihapus' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $item->department?->code ?? '—' }}</p>
                </div>
                <span class="text-sm font-semibold text-blue-600 shrink-0">{{ $item->total }} aktivitas</span>
            </div>
            @if(!$loop->last)
                <div class="mb-3">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-blue-500 h-1.5 rounded-full"
                             style="width: {{ $topDepartments->max('total') > 0 ? round(($item->total / $topDepartments->max('total')) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
        @endforelse
    </div>

    {{-- Aktivitas per Module --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Aktivitas per Modul</h3>
        <canvas id="moduleChart" height="200"></canvas>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const primaryColor = '#7f0d0d';

// Grafik aktivitas 7 hari
const activityCtx = document.getElementById('activityChart').getContext('2d');
new Chart(activityCtx, {
    type: 'line',
    data: {
        labels: @json($last7Days->pluck('date')),
        datasets: [{
            label: 'Aktivitas',
            data: @json($last7Days->pluck('total')),
            borderColor: primaryColor,
            backgroundColor: 'rgba(127,13,13,0.08)',
            borderWidth: 2,
            pointBackgroundColor: primaryColor,
            pointRadius: 4,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { size: 11 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: {
                ticks: { font: { size: 11 } },
                grid: { display: false }
            }
        }
    }
});

// Grafik per modul (donut)
const moduleCtx = document.getElementById('moduleChart').getContext('2d');
new Chart(moduleCtx, {
    type: 'doughnut',
    data: {
        labels: @json($activityByModule->pluck('module')->map(fn($m) => ucfirst($m))),
        datasets: [{
            data: @json($activityByModule->pluck('total')),
            backgroundColor: [
                '#7f0d0d', '#b91c1c', '#dc2626',
                '#1d4ed8', '#2563eb', '#3b82f6',
            ],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 11 }, padding: 12 }
            }
        },
        cutout: '65%',
    }
});
</script>
@endpush