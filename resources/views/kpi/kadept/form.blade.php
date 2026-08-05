@extends('layouts.app')
@section('title', 'Isi Penilaian KPI — ' . $employee->name)

@section('content')

    <div class="mb-6">
        <a href="{{ route('kpi.kadept.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali
        </a>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Penilaian KPI — {{ $employee->name }}</h2>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ $employee->department?->name ?? '-' }} &mdash; {{ $employee->position?->name ?? '-' }}
            &mdash; Periode {{ $activePeriod->label }}
        </p>

        @if($isCross && !$isPrimary)
            <div class="mt-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg px-4 py-3 text-sm text-blue-700 dark:text-blue-400">
                ℹ️ Anda adalah penilai <strong>kualitatif tambahan (cross-departemen)</strong> untuk karyawan ini.
                Anda hanya mengisi bagian <strong>Kualitatif</strong>. Penilaian Kuantitatif diisi oleh Kadept departemen karyawan ini.
            </div>
        @endif
    </div>

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($evaluation && $evaluation->isWaitingCrossDept())
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg px-4 py-3 mb-5 text-sm text-yellow-700 dark:text-yellow-400">
            ⏳ Menunggu penilai lain untuk menyelesaikan bagian kualitatif sebelum nilai akhir bisa dihitung.
        </div>
    @endif

    <form method="POST" action="{{ route('kpi.kadept.save', $employee->id) }}" id="kpi-form">
        @csrf

        {{-- ===== BAGIAN 1: KUANTITATIF — HANYA UNTUK PRIMARY ===== --}}
        @if($isPrimary)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-5">
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <p class="font-semibold text-sm text-gray-700 dark:text-gray-200">1. Penilaian Kuantitatif</p>
                <button type="button" onclick="addQuantRow()"
                        class="text-xs text-primary-700 hover:underline font-medium">
                    + Tambah Indikator
                </button>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 text-xs border-b border-gray-100 dark:border-gray-700">
                        <th class="text-left px-5 py-2">Nama Indikator</th>
                        <th class="text-left px-3 py-2 w-24">Bobot %</th>
                        <th class="text-left px-3 py-2 w-28">Target</th>
                        <th class="text-left px-3 py-2 w-28">Actual</th>
                        <th class="text-left px-3 py-2 w-24">Achv. %</th>
                        <th class="text-left px-3 py-2 w-12"></th>
                    </tr>
                </thead>
                <tbody id="quant-rows-body" class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($quantValues as $i => $detail)
                        <tr class="quant-row" data-index="{{ $i }}">
                            <td class="px-5 py-2.5">
                                <input type="text" name="quant[{{ $i }}][indicator_name]" value="{{ $detail->indicator_name }}"
                                       placeholder="Contoh: Market Share"
                                       class="quant-name w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </td>
                            <td class="px-3 py-2.5">
                                <input type="number" step="0.01" name="quant[{{ $i }}][weight_percent]" value="{{ $detail->weight_percent }}"
                                       class="quant-weight w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </td>
                            <td class="px-3 py-2.5">
                                <input type="number" step="0.01" name="quant[{{ $i }}][target]" value="{{ $detail->target }}"
                                       class="quant-target w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </td>
                            <td class="px-3 py-2.5">
                                <input type="number" step="0.01" name="quant[{{ $i }}][actual]" value="{{ $detail->actual }}"
                                       class="quant-actual w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="quant-achievement font-medium text-gray-600 dark:text-gray-300">{{ $detail->achievement_percent ?? 0 }}%</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <button type="button" onclick="removeQuantRow(this)" class="text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <p class="text-xs text-gray-400">Total bobot sebaiknya 100%. Total saat ini: <strong id="total-weight">0</strong>%</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Total Poin Kuantitatif: <strong id="total-quant" class="text-primary-700">0</strong>
                </p>
            </div>
        </div>
        @endif

        {{-- ===== BAGIAN 2: KUALITATIF — UNTUK SEMUA (PRIMARY & CROSS) ===== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-5">
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <p class="font-semibold text-sm text-gray-700 dark:text-gray-200">
                    {{ $isPrimary ? '2.' : '' }} Penilaian Kualitatif
                    <span class="text-gray-400 font-normal text-xs">(yang Anda isi sendiri)</span>
                </p>
            </div>

            <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Kehadiran <span class="text-gray-400">(Bobot 40%)</span></p>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Target (hari)</label>
                        <input type="number" step="0.01" name="kehadiran_target" id="kehadiran_target"
                               value="{{ $myQualitySubmission?->kehadiran_target }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Actual (hari)</label>
                        <input type="number" step="0.01" name="kehadiran_actual" id="kehadiran_actual"
                               value="{{ $myQualitySubmission?->kehadiran_actual }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Achievement</label>
                        <p id="kehadiran-achievement" class="text-sm font-medium text-gray-600 dark:text-gray-300 py-2">
                            {{ $myQualitySubmission?->kehadiran_achievement ?? 0 }}%
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-5 py-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Core Value IKHLAS <span class="text-gray-400">(Bobot 60%, skala 50-100)</span></p>
                    <a href="{{ route('kpi.definisi-core-value') }}" target="_blank"
                       class="text-xs text-primary-700 hover:underline">
                        Lihat Definisi Lengkap →
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    @php
                        $coreValueFields = [
                            'integritas' => 'Integritas', 'kekeluargaan' => 'Kekeluargaan', 'handal' => 'Handal',
                            'loyalitas' => 'Loyalitas', 'amanah' => 'Amanah', 'saling_menghargai' => 'Saling Menghargai',
                        ];
                    @endphp
                    @foreach($coreValueFields as $field => $label)
                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $label }}</label>
                            <input type="number" step="0.01" min="0" max="100" name="{{ $field }}"
                                   value="{{ $myQualitySubmission?->{$field} }}"
                                   class="core-value-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Skor Kualitatif (versi Anda): <strong id="total-quality" class="text-primary-700">0</strong>
                </p>
            </div>
        </div>

        @if($isPrimary)
        {{-- ===== TOTAL PENILAIAN 1 (estimasi, final dihitung server setelah semua submit) ===== --}}
        <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800 rounded-xl px-5 py-3 mb-5 flex justify-between items-center">
            <p class="text-sm font-semibold text-primary-800 dark:text-primary-300">Estimasi Total Penilaian 1</p>
            <p class="text-lg font-bold text-primary-700 dark:text-primary-400" id="penilaian-1">0</p>
        </div>

        {{-- ===== BAGIAN 3: SURAT PERINGATAN ===== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-5">
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <p class="font-semibold text-sm text-gray-700 dark:text-gray-200">3. Surat Peringatan <span class="text-gray-400 font-normal">(pengurang poin)</span></p>
            </div>
            <div class="p-5 grid grid-cols-3 gap-4">
                @php $spList = ['sp1' => ['label' => 'SP 1', 'weight' => 20], 'sp2' => ['label' => 'SP 2', 'weight' => 30], 'sp3' => ['label' => 'SP 3', 'weight' => 50]]; @endphp
                @foreach($spList as $key => $sp)
                    @php $existingSp = $warningValues->get($key); @endphp
                    <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-red-200">
                        <input type="checkbox" name="{{ $key }}" value="1" class="sp-checkbox w-4 h-4 text-red-600 border-gray-300 rounded"
                               data-weight="{{ $sp['weight'] }}"
                               {{ $existingSp && $existingSp->is_present ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $sp['label'] }}</p>
                            <p class="text-xs text-gray-400">Bobot {{ $sp['weight'] }}%</p>
                        </div>
                    </label>
                @endforeach
            </div>
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Total Pengurang: <strong id="total-warning" class="text-red-500">0</strong>
                </p>
            </div>
        </div>

        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800 rounded-xl px-5 py-3 mb-5 flex justify-between items-center">
            <p class="text-sm font-semibold text-orange-800 dark:text-orange-300">Estimasi Total Penilaian 2</p>
            <p class="text-lg font-bold text-orange-700 dark:text-orange-400" id="penilaian-2">0</p>
        </div>

       {{-- ===== BAGIAN 4: SPECIAL ASSIGNMENT / PROJECT ===== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-5">
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <p class="font-semibold text-sm text-gray-700 dark:text-gray-200">4. Special Assignment / Project <span class="text-gray-400 font-normal">(tiap project +3.0 poin)</span></p>
                <button type="button" onclick="addProjectRow()" class="text-xs text-primary-700 hover:underline font-medium">
                    + Tambah Project
                </button>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 text-xs border-b border-gray-100 dark:border-gray-700">
                        <th class="text-left px-5 py-2">Nama Project</th>
                        <th class="text-left px-3 py-2 w-24">Poin</th>
                        <th class="text-left px-3 py-2 w-12"></th>
                    </tr>
                </thead>
                <tbody id="project-rows-body" class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($specialValues as $i => $special)
                        <tr class="project-row" data-index="{{ $i }}">
                            <td class="px-5 py-2.5">
                                <input type="text" name="project[{{ $i }}][name]" value="{{ $special->name }}"
                                       placeholder="Contoh: Implementasi Sistem X"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="font-medium text-green-600">+3.0</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <button type="button" onclick="removeProjectRow(this)" class="text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Total Poin Project: <strong id="total-special" class="text-green-600">0</strong>
                </p>
            </div>
        </div>

        <div class="bg-gray-800 dark:bg-gray-900 rounded-xl px-5 py-4 mb-6 flex justify-between items-center">
            <p class="text-sm font-semibold text-white">Estimasi Grand Total</p>
            <div class="text-right">
                <p class="text-2xl font-bold text-white" id="grand-total">0</p>
                <p class="text-xs text-gray-300" id="grade-label">-</p>
            </div>
        </div>
        @endif

        <div class="flex gap-3">
            <button type="submit" name="submit_type" value="draft"
                    class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                Simpan Draft
            </button>
            <button type="submit" name="submit_type" value="submit"
                    class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                Submit Penilaian
            </button>
        </div>

    </form>

@endsection

@push('scripts')
<script>
let quantRowIndex = {{ $quantValues->count() }};

function addQuantRow() {
    const tbody = document.getElementById('quant-rows-body');
    const tr = document.createElement('tr');
    tr.className = 'quant-row';
    tr.dataset.index = quantRowIndex;
    tr.innerHTML = `
        <td class="px-5 py-2.5">
            <input type="text" name="quant[${quantRowIndex}][indicator_name]" placeholder="Contoh: Market Share"
                   class="quant-name w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
        </td>
        <td class="px-3 py-2.5">
            <input type="number" step="0.01" name="quant[${quantRowIndex}][weight_percent]"
                   class="quant-weight w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
        </td>
        <td class="px-3 py-2.5">
            <input type="number" step="0.01" name="quant[${quantRowIndex}][target]"
                   class="quant-target w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
        </td>
        <td class="px-3 py-2.5">
            <input type="number" step="0.01" name="quant[${quantRowIndex}][actual]"
                   class="quant-actual w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
        </td>
        <td class="px-3 py-2.5"><span class="quant-achievement font-medium text-gray-600 dark:text-gray-300">0%</span></td>
        <td class="px-3 py-2.5">
            <button type="button" onclick="removeQuantRow(this)" class="text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    quantRowIndex++;
    calculateAll();
}

function removeQuantRow(btn) {
    btn.closest('tr').remove();
    calculateAll();
}

let projectRowIndex = {{ $specialValues->count() }};

function addProjectRow() {
    const tbody = document.getElementById('project-rows-body');
    const tr = document.createElement('tr');
    tr.className = 'project-row';
    tr.dataset.index = projectRowIndex;
    tr.innerHTML = `
        <td class="px-5 py-2.5">
            <input type="text" name="project[${projectRowIndex}][name]" placeholder="Contoh: Implementasi Sistem X"
                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
        </td>
        <td class="px-3 py-2.5"><span class="font-medium text-green-600">+3.0</span></td>
        <td class="px-3 py-2.5">
            <button type="button" onclick="removeProjectRow(this)" class="text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    projectRowIndex++;
    calculateAll();
}

function removeProjectRow(btn) {
    btn.closest('tr').remove();
    calculateAll();
}

function calculateAll() {
    let totalQuant = 0;
    let totalWeight = 0;
    document.querySelectorAll('.quant-row').forEach(row => {
        const weight = parseFloat(row.querySelector('.quant-weight')?.value) || 0;
        const target = parseFloat(row.querySelector('.quant-target')?.value) || 0;
        const actual = parseFloat(row.querySelector('.quant-actual')?.value) || 0;
        const achievement = target > 0 ? (actual / target) * 100 : 0;
        const score = (achievement / 100) * weight;

        const achEl = row.querySelector('.quant-achievement');
        if (achEl) achEl.textContent = achievement.toFixed(2) + '%';

        totalQuant += score;
        totalWeight += weight;
    });

    const totalQuantEl = document.getElementById('total-quant');
    const totalWeightEl = document.getElementById('total-weight');
    if (totalQuantEl) totalQuantEl.textContent = totalQuant.toFixed(2);
    if (totalWeightEl) totalWeightEl.textContent = totalWeight.toFixed(2);

    const kehadiranTarget = parseFloat(document.getElementById('kehadiran_target')?.value) || 0;
    const kehadiranActual = parseFloat(document.getElementById('kehadiran_actual')?.value) || 0;
    let kehadiranAchievement = kehadiranTarget > 0 ? (kehadiranActual / kehadiranTarget) * 100 : 0;
    kehadiranAchievement = Math.min(kehadiranAchievement, 100); // cap maksimal 100%
    const kehadiranEl = document.getElementById('kehadiran-achievement');
    if (kehadiranEl) kehadiranEl.textContent = kehadiranAchievement.toFixed(2) + '%';

    let coreSum = 0, coreCount = 0;
    document.querySelectorAll('.core-value-input').forEach(input => {
        coreSum += parseFloat(input.value) || 0;
        coreCount++;
    });
    const coreAverage = coreCount > 0 ? coreSum / coreCount : 0;

    const totalQuality = (Math.min(kehadiranAchievement, 100) / 100 * 40) + (coreAverage / 100 * 60);
    const totalQualityEl = document.getElementById('total-quality');
    if (totalQualityEl) totalQualityEl.textContent = totalQuality.toFixed(2);

    const penilaian1 = totalQuant + totalQuality;
    const p1El = document.getElementById('penilaian-1');
    if (p1El) p1El.textContent = penilaian1.toFixed(2);

    let totalWarning = 0;
    document.querySelectorAll('.sp-checkbox').forEach(cb => {
        if (cb.checked) totalWarning += parseFloat(cb.dataset.weight) || 0;
    });
    const totalWarningEl = document.getElementById('total-warning');
    if (totalWarningEl) totalWarningEl.textContent = totalWarning.toFixed(2);

    const penilaian2 = penilaian1 - totalWarning;
    const p2El = document.getElementById('penilaian-2');
    if (p2El) p2El.textContent = penilaian2.toFixed(2);

    let specialScore = 0;
    document.querySelectorAll('.project-row').forEach(row => {
        const nameInput = row.querySelector('input[type="text"]');
        if (nameInput && nameInput.value.trim() !== '') {
            specialScore += 3;
        }
    });
    const totalSpecialEl = document.getElementById('total-special');
    if (totalSpecialEl) totalSpecialEl.textContent = specialScore.toFixed(2);

    const grandTotal = penilaian2 + specialScore;
    const gtEl = document.getElementById('grand-total');
    if (gtEl) gtEl.textContent = grandTotal.toFixed(2);

    let grade = 'Kurang';
    if (grandTotal > 100) grade = 'Istimewa';
    else if (grandTotal >= 90) grade = 'Baik Sekali';
    else if (grandTotal >= 80) grade = 'Baik';
    else if (grandTotal >= 65) grade = 'Cukup';
    const gradeEl = document.getElementById('grade-label');
    if (gradeEl) gradeEl.textContent = 'Estimasi Grade: ' + grade;
}

document.getElementById('kpi-form').addEventListener('input', calculateAll);
document.getElementById('kpi-form').addEventListener('change', calculateAll);
calculateAll();
</script>
@endpush