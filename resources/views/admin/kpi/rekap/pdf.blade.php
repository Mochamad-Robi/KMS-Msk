<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan KPI - {{ $department->name }}</title>
    <style>
        @page { margin: 25px 30px; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10px; color: #333; }
        .page-break { page-break-before: always; }

        .header-bar {
            background: #7f0d0d; color: white; padding: 10px 15px;
            text-align: center; font-size: 13px; font-weight: bold;
            margin-bottom: 12px; border-radius: 3px;
        }

        table.info-table { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
        table.info-table td { padding: 3px 6px; font-size: 10px; vertical-align: top; }
        table.info-table td.label { width: 140px; color: #666; }
        table.info-table td.value { font-weight: bold; color: #222; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.data-table th, table.data-table td {
            border: 1px solid #ccc; padding: 5px 7px; font-size: 9.5px;
        }
        table.data-table th { background: #eaeaea; font-weight: bold; text-align: center; }
        table.data-table td.text-center { text-align: center; }
        table.data-table td.text-right { text-align: right; }

        .section-title {
            background: #f3f3f3; font-weight: bold; padding: 6px 8px;
            font-size: 10.5px; margin-bottom: 6px; border-left: 3px solid #7f0d0d;
        }

        .total-row { background: #fff3cd; font-weight: bold; }
        .total-row-2 { background: #ffe0b3; font-weight: bold; }
        .grand-total-row { background: #7f0d0d; color: white; font-weight: bold; }

        .grade-box {
            display: inline-block; padding: 4px 14px; border-radius: 3px;
            font-weight: bold; font-size: 13px; color: white;
        }
        .grade-istimewa { background: #9333ea; }
        .grade-baik_sekali { background: #2563eb; }
        .grade-baik { background: #16a34a; }
        .grade-cukup { background: #ca8a04; }
        .grade-kurang { background: #dc2626; }

        .footer-note { font-size: 8px; color: #999; text-align: center; margin-top: 15px; }
    </style>
</head>
<body>

@foreach($employeeReports as $i => $report)
    @php
        $employee = $report['employee'];
        $evaluation = $report['evaluation'];
        $assessors = $report['assessors'];
        $qualityFinal = $report['qualityFinal'];
        $coreValueFields = $report['coreValueFields'];
    @endphp

    <div class="{{ $i > 0 ? 'page-break' : '' }}">

        <div class="header-bar">
            LAPORAN PENILAIAN KPI — {{ $period->label }}
        </div>

        {{-- Header Info --}}
        <table class="info-table">
            <tr>
                <td class="label">Divisi</td>
                <td class="value">{{ $employee->department?->parent?->name ?? $employee->department?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Departemen</td>
                <td class="value">{{ $employee->department?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Assessee (Dinilai)</td>
                <td class="value">{{ $employee->name }} — {{ $employee->position?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Assessor (Penilai)</td>
                <td class="value">
                    @foreach($assessors as $idx => $assessor)
                        {{ $idx + 1 }}. {{ $assessor['name'] }} {{ $assessor['is_primary'] ? '(Dept Sendiri)' : '(Cross-Dept)' }}<br>
                    @endforeach
                </td>
            </tr>
        </table>

        {{-- ===== KUANTITATIF ===== --}}
        @if($report['quantDetails']->count() > 0)
        <div class="section-title">1. Penilaian Kuantitatif</div>
        <table class="data-table">
            <tr>
                <th style="width: 35%">Indikator</th>
                <th style="width: 13%">Bobot</th>
                <th style="width: 17%">Target</th>
                <th style="width: 17%">Actual</th>
                <th style="width: 18%">Achievement</th>
            </tr>
            @foreach($report['quantDetails'] as $detail)
                <tr>
                    <td>{{ $detail->indicator_name }}</td>
                    <td class="text-center">{{ $detail->weight_percent }}%</td>
                    <td class="text-center">{{ $detail->target }}</td>
                    <td class="text-center">{{ $detail->actual }}</td>
                    <td class="text-center">{{ $detail->achievement_percent }}%</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">Total Poin Kuantitatif</td>
                <td class="text-center">{{ $evaluation->total_quant_score }}</td>
            </tr>
        </table>
        @endif

        {{-- ===== KUALITATIF — BREAKDOWN PER ASSESSOR ===== --}}
        <div class="section-title">2. Penilaian Kualitatif — Core Value IKHLAS</div>
        <table class="data-table">
            <tr>
                <th style="width: 8%">No</th>
                <th style="width: 27%">Core Value</th>
                @foreach($assessors as $assessor)
                    <th style="width: {{ 65 / max(count($assessors),1) / 2 }}%">{{ $assessor['name'] }}</th>
                @endforeach
                <th style="width: {{ 65 / max(count($assessors),1) / 2 }}%">Average</th>
            </tr>
            @foreach($coreValueFields as $field => $label)
                <tr>
                    <td class="text-center">{{ strtoupper(substr($label, 0, 1)) }}</td>
                    <td>{{ $label }}</td>
                    @foreach($assessors as $assessor)
                        <td class="text-center">{{ $assessor['submission']?->{$field} ?? '-' }}</td>
                    @endforeach
                    <td class="text-center"><strong>{{ $qualityFinal?->{$field} ?? '-' }}</strong></td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-right">Nilai Core Value (Average)</td>
                @foreach($assessors as $assessor)
                    <td class="text-center">
                        @php
                            $sub = $assessor['submission'];
                            $vals = $sub ? array_filter([$sub->integritas, $sub->kekeluargaan, $sub->handal, $sub->loyalitas, $sub->amanah, $sub->saling_menghargai], fn($v) => $v !== null) : [];
                        @endphp
                        {{ count($vals) ? round(array_sum($vals) / count($vals), 1) : '-' }}
                    </td>
                @endforeach
                <td class="text-center">{{ $qualityFinal?->core_value_average ?? '-' }}</td>
            </tr>
        </table>

        {{-- Kehadiran --}}
        <table class="data-table">
            <tr>
                <th style="width: 40%">Kehadiran</th>
                <th style="width: 20%">Target</th>
                <th style="width: 20%">Actual</th>
                <th style="width: 20%">Achievement</th>
            </tr>
            <tr>
                <td>Kehadiran (Bobot 40%)</td>
                <td class="text-center">{{ $qualityFinal?->kehadiran_target ?? '-' }}</td>
                <td class="text-center">{{ $qualityFinal?->kehadiran_actual ?? '-' }}</td>
                <td class="text-center">{{ $qualityFinal?->kehadiran_achievement ?? '-' }}%</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Poin Kualitatif (Kehadiran 40% + Core Value 60%)</td>
                <td class="text-center">{{ $qualityFinal?->total_quality_score ?? '-' }}</td>
            </tr>
        </table>

        <table class="data-table">
            <tr class="total-row-2">
                <td style="width: 80%" class="text-right"><strong>TOTAL PENILAIAN KARYAWAN 1 (Kuantitatif + Kualitatif)</strong></td>
                <td style="width: 20%" class="text-center"><strong>{{ $evaluation->penilaian_1 }}</strong></td>
            </tr>
        </table>

        {{-- ===== SURAT PERINGATAN ===== --}}
        @if($report['warnings']->count() > 0)
        <div class="section-title">3. Surat Peringatan (Pengurang Poin)</div>
        <table class="data-table">
            <tr>
                <th style="width: 40%">Jenis</th>
                <th style="width: 20%">Bobot</th>
                <th style="width: 20%">Status</th>
                <th style="width: 20%">Poin</th>
            </tr>
            @foreach($report['warnings'] as $warning)
                <tr>
                    <td>{{ $warning->type_label }}</td>
                    <td class="text-center">{{ $warning->weight_percent }}%</td>
                    <td class="text-center">{{ $warning->is_present ? 'ADA' : 'TIDAK ADA' }}</td>
                    <td class="text-center">{{ $warning->score }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Poin Pengurang</td>
                <td class="text-center">{{ $evaluation->total_warning_score }}</td>
            </tr>
        </table>

        <table class="data-table">
            <tr class="total-row-2">
                <td style="width: 80%" class="text-right"><strong>TOTAL PENILAIAN KARYAWAN 2 (Penilaian 1 - SP)</strong></td>
                <td style="width: 20%" class="text-center"><strong>{{ $evaluation->penilaian_2 }}</strong></td>
            </tr>
        </table>
        @endif

        {{-- ===== SPECIAL ASSIGNMENT ===== --}}
        @if($report['specialAssignments']->count() > 0)
        <div class="section-title">4. Special Assignment / Tugas Khusus</div>
        <table class="data-table">
            <tr>
                <th style="width: 50%">Nama</th>
                <th style="width: 25%">Status</th>
                <th style="width: 25%">Poin</th>
            </tr>
            @foreach($report['specialAssignments'] as $special)
                <tr>
                    <td>{{ $special->name }}</td>
                    <td class="text-center">{{ $special->is_present ? 'ADA' : 'TIDAK ADA' }}</td>
                    <td class="text-center">{{ $special->score }}</td>
                </tr>
            @endforeach
        </table>
        @endif

        {{-- ===== GRAND TOTAL ===== --}}
        <table class="data-table">
            <tr class="grand-total-row">
                <td style="width: 70%" class="text-right" style="font-size: 12px;">GRAND TOTAL PENILAIAN KARYAWAN</td>
                <td style="width: 30%" class="text-center" style="font-size: 14px;">{{ $evaluation->grand_total }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 10px;">
            <span class="grade-box grade-{{ $evaluation->grade }}">{{ $evaluation->grade_label }}</span>
        </div>

        <p class="footer-note">
            Dokumen ini digenerate otomatis oleh sistem DKMS PT Mitra Sendang Kemakmuran pada {{ now()->format('d M Y H:i') }}
        </p>

    </div>
@endforeach

</body>
</html>