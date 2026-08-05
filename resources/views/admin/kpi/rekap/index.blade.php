@extends('layouts.app')
@section('title', 'Rekap KPI')

@section('content')

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Rekap KPI</h2>
        <p class="text-gray-400 text-sm mt-0.5">Pilih departemen dan periode untuk melihat hasil & download laporan PDF</p>
    </div>

    @if($periods->isEmpty())
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
            Belum ada periode KPI dibuat.
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 mb-6">
            <form method="GET" id="rekap-filter-form" class="flex items-end gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Departemen</label>
                    <select id="dept-select"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Periode</label>
                    <select id="period-select"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}">{{ $period->label }} {{ $period->is_open ? '(Dibuka)' : '(Ditutup)' }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="goToRekap()"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm whitespace-nowrap">
                    Lihat Rekap
                </button>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($departments as $dept)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $dept->name }}</h3>
                <div class="mt-3 space-y-1.5">
                    @foreach($periods->take(4) as $period)
                        <a href="{{ route('admin.kpi.rekap.show', [$dept->id, $period->id]) }}"
                           class="block text-xs text-primary-700 hover:underline">
                            → {{ $period->label }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

@endsection

@push('scripts')
<script>
function goToRekap() {
    const dept = document.getElementById('dept-select').value;
    const period = document.getElementById('period-select').value;

    if (!dept || !period) {
        alert('Pilih departemen dan periode terlebih dahulu.');
        return;
    }

    window.location.href = `/admin/kpi/rekap/${dept}/${period}`;
}
</script>
@endpush