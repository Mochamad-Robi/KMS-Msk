<?php

namespace App\Http\Controllers;

use App\Models\KpiAssignment;
use App\Models\KpiQualityAssignment;
use App\Models\KpiPeriod;
use App\Models\KpiEvaluation;
use App\Models\KpiQuantDetail;
use App\Models\KpiQualityDetail;
use App\Models\KpiWarning;
use App\Models\KpiSpecialAssignment;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KadeptKpiController extends Controller
{
    // ===================================================================
    // INDEX — list karyawan yang harus dinilai (primary & cross dipisah)
    // ===================================================================
    public function index()
    {
        $user = Auth::user();

        $activePeriod = KpiPeriod::where('is_open', true)
                            ->orderByDesc('year')
                            ->orderByDesc('quartal')
                            ->first();

        $primaryAssignments = KpiQualityAssignment::where('kadept_id', $user->id)
                                ->where('is_primary', true)
                                ->where('is_active', true)
                                ->with('employee.department', 'employee.position')
                                ->get();

        $crossAssignments = KpiQualityAssignment::where('kadept_id', $user->id)
                                ->where('is_primary', false)
                                ->where('is_active', true)
                                ->with('employee.department', 'employee.position')
                                ->get();

        $primaryRows = collect();
        $crossRows = collect();

        if ($activePeriod) {
            foreach ($primaryAssignments as $assignment) {
                $primaryRows->push($this->buildRow($assignment->employee, $activePeriod));
            }

            foreach ($crossAssignments as $assignment) {
                $crossRows->push($this->buildRow($assignment->employee, $activePeriod));
            }
        }

        return view('kpi.kadept.index', compact('activePeriod', 'primaryRows', 'crossRows'));
    }

    private function buildRow($employee, $activePeriod)
    {
        $evaluation = KpiEvaluation::where('kpi_period_id', $activePeriod->id)
                        ->where('employee_id', $employee->id)
                        ->first();

        if (!$activePeriod->is_open) {
            $status = $evaluation ? 'locked' : 'belum_diisi';
        } elseif (!$evaluation) {
            $status = 'belum_diisi';
        } else {
            $mySubmission = $evaluation->qualityDetails()
                                ->where('submitted_by', Auth::id())
                                ->where('is_final_average', false)
                                ->exists();

            if ($evaluation->status === 'submitted') {
                $status = 'submitted';
            } else {
                $status = $mySubmission ? 'draft' : 'belum_diisi';
            }
        }

        return [
            'employee'   => $employee,
            'evaluation' => $evaluation,
            'status'     => $status,
            'is_waiting' => $evaluation && $evaluation->isWaitingCrossDept(),
        ];
    }

    // ===================================================================
    // SHOW FORM — tampilkan form isi KPI untuk 1 karyawan
    // ===================================================================
    public function showForm($employeeId)
    {
        $user = Auth::user();

        $activePeriod = KpiPeriod::orderByDesc('year')->orderByDesc('quartal')->first();
        abort_if(!$activePeriod, 404, 'Belum ada periode KPI dibuat.');

        $employee = User::findOrFail($employeeId);

        // Cek apakah user ini adalah kadept PRIMARY atau CROSS untuk karyawan ini
        $isPrimary = $user->isPrimaryKadeptFor($employeeId);
        $isCross   = $user->isCrossDeptKadeptFor($employeeId);

        abort_if(!$isPrimary && !$isCross, 403, 'Anda tidak ditugaskan untuk menilai karyawan ini.');

        if (!$activePeriod->is_open) {
            abort(403, 'Periode KPI ini sudah ditutup oleh Admin.');
        }

        $evaluation = KpiEvaluation::where('kpi_period_id', $activePeriod->id)
                        ->where('employee_id', $employeeId)
                        ->with(['quantDetails', 'qualityDetails', 'warnings', 'specialAssignments'])
                        ->first();

        // Ambil submission kualitatif milik kadept INI sendiri (kalau sudah pernah isi)
        $myQualitySubmission = $evaluation
            ? $evaluation->qualityDetails->firstWhere('submitted_by', $user->id)
            : null;

        $quantValues = $evaluation ? $evaluation->quantDetails->sortBy('order') : collect();
        $warningValues = $evaluation ? $evaluation->warnings->keyBy('type') : collect();
        $specialValues = $evaluation ? $evaluation->specialAssignments : collect();

        return view('kpi.kadept.form', compact(
            'activePeriod', 'employee', 'evaluation', 'isPrimary', 'isCross',
            'quantValues', 'myQualitySubmission', 'warningValues', 'specialValues'
        ));
    }

    // ===================================================================
    // SAVE FORM — simpan isi KPI (beda logic untuk primary vs cross)
    // ===================================================================
    public function saveForm(Request $request, $employeeId)
    {
        $user = Auth::user();

        $activePeriod = KpiPeriod::orderByDesc('year')->orderByDesc('quartal')->first();
        abort_if(!$activePeriod, 404, 'Belum ada periode KPI dibuat.');

        $isPrimary = $user->isPrimaryKadeptFor($employeeId);
        $isCross   = $user->isCrossDeptKadeptFor($employeeId);

        abort_if(!$isPrimary && !$isCross, 403, 'Anda tidak ditugaskan untuk menilai karyawan ini.');

        if (!$activePeriod->is_open) {
            abort(403, 'Periode KPI ini sudah ditutup oleh Admin.');
        }

        // Validasi dasar (kualitatif wajib untuk semua, kuantitatif hanya wajib untuk primary)
        $rules = [
            'kehadiran_target'   => 'required|numeric|min:0',
            'kehadiran_actual'   => 'required|numeric|min:0',
            'integritas'         => 'required|numeric|min:0|max:100',
            'kekeluargaan'       => 'required|numeric|min:0|max:100',
            'handal'             => 'required|numeric|min:0|max:100',
            'loyalitas'          => 'required|numeric|min:0|max:100',
            'amanah'             => 'required|numeric|min:0|max:100',
            'saling_menghargai' => 'required|numeric|min:0|max:100',
            'submit_type'        => 'required|in:draft,submit',
        ];

        if ($isPrimary) {
            $rules['quant']                  = 'required|array|min:1';
            $rules['quant.*.indicator_name'] = 'required|string|max:255';
            $rules['quant.*.weight_percent'] = 'required|numeric|min:0|max:100';
            $rules['quant.*.target']          = 'required|numeric|min:0';
            $rules['quant.*.actual']           = 'required|numeric|min:0';
            $rules['sp1']            = 'nullable|boolean';
            $rules['sp2']            = 'nullable|boolean';
            $rules['sp3']            = 'nullable|boolean';
            $rules['project']        = 'nullable|array';
            $rules['project.*.name'] = 'required_with:project|string|max:255';
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $employeeId, $activePeriod, $user, $isPrimary) {

            $evaluation = KpiEvaluation::firstOrCreate(
                ['kpi_period_id' => $activePeriod->id, 'employee_id' => $employeeId],
                ['evaluated_by' => $user->id, 'status' => 'draft']
            );

            // ===== KUALITATIF: simpan submission MILIK KADEPT INI sendiri =====
            $kehadiranTarget = (float) $request->kehadiran_target;
            $kehadiranActual = (float) $request->kehadiran_actual;
            $kehadiranAchievement = $kehadiranTarget > 0
            ? min(round(($kehadiranActual / $kehadiranTarget) * 100, 2), 100)
            : 0;

            $coreValues = [
                $request->integritas, $request->kekeluargaan, $request->handal,
                $request->loyalitas, $request->amanah, $request->saling_menghargai,
            ];
            $coreValueAverage = round(array_sum($coreValues) / count($coreValues), 2);
            $myQualityScore = round((min($kehadiranAchievement, 100) / 100) * 40, 2)
                             + round(($coreValueAverage / 100) * 60, 2);

            KpiQualityDetail::updateOrCreate(
                [
                    'kpi_evaluation_id' => $evaluation->id,
                    'submitted_by'      => $user->id,
                    'is_final_average'  => false,
                ],
                [
                    'kehadiran_target'       => $kehadiranTarget,
                    'kehadiran_actual'        => $kehadiranActual,
                    'kehadiran_achievement'  => $kehadiranAchievement,
                    'integritas'              => $request->integritas,
                    'kekeluargaan'            => $request->kekeluargaan,
                    'handal'                  => $request->handal,
                    'loyalitas'               => $request->loyalitas,
                    'amanah'                  => $request->amanah,
                    'saling_menghargai'      => $request->saling_menghargai,
                    'core_value_average'    => $coreValueAverage,
                    'total_quality_score'   => $myQualityScore,
                ]
            );

            // ===== Cek apakah perlu menunggu kadept cross-dept =====
            $hasCrossAssignment = \App\Models\KpiQualityAssignment::where('employee_id', $employeeId)
                                    ->where('is_primary', false)
                                    ->where('is_active', true)
                                    ->exists();

            $allSubmissions = $evaluation->qualityDetails()
                                ->where('is_final_average', false)
                                ->get();

            $isComplete = !$hasCrossAssignment || $allSubmissions->count() >= 2;

            $totalQuantScore = (float) $evaluation->total_quant_score;
            $penilaian1 = (float) $evaluation->penilaian_1;
            $penilaian2 = (float) $evaluation->penilaian_2;
            $totalWarningScore = (float) $evaluation->total_warning_score;
            $specialScore = (float) $evaluation->total_special_score;
            $finalQualityScore = (float) $evaluation->total_quality_score;

            if ($isComplete) {
                // ===== Hitung FINAL AVERAGE kualitatif dari semua submission =====
                $finalAverage = KpiQualityDetail::calculateFinalAverage($allSubmissions);

                $finalCoreValueAvg = $finalAverage['core_value_average'];
                $finalKehadiranAchievement = $finalAverage['kehadiran_achievement'] ?? 0;

                $finalQualityScore = round((min($finalKehadiranAchievement, 100) / 100) * 40, 2)
                                   + round(($finalCoreValueAvg / 100) * 60, 2);

                KpiQualityDetail::updateOrCreate(
                    ['kpi_evaluation_id' => $evaluation->id, 'is_final_average' => true],
                    array_merge($finalAverage['fields'], [
                        'submitted_by'           => null,
                        'kehadiran_target'       => $finalAverage['kehadiran_target'],
                        'kehadiran_actual'        => $finalAverage['kehadiran_actual'],
                        'kehadiran_achievement'  => $finalKehadiranAchievement,
                        'core_value_average'    => $finalCoreValueAvg,
                        'total_quality_score'   => $finalQualityScore,
                    ])
                );
            }

            // ===== KUANTITATIF + SP + SPECIAL: hanya diisi oleh PRIMARY =====
            if ($isPrimary && $request->has('quant')) {
                // Hapus dulu detail kuantitatif lama (karena indikator manual, bisa beda tiap submit)
                $evaluation->quantDetails()->delete();

                $totalQuantScore = 0;
                foreach ($request->quant as $index => $data) {
                    $target = (float) $data['target'];
                    $actual = (float) $data['actual'];
                    $weight = (float) $data['weight_percent'];
                    $achievement = $target > 0 ? round(($actual / $target) * 100, 2) : 0;
                    $score = round(($achievement / 100) * $weight, 2);

                    KpiQuantDetail::create([
                        'kpi_evaluation_id'   => $evaluation->id,
                        'indicator_name'      => $data['indicator_name'],
                        'weight_percent'      => $weight,
                        'order'               => $index,
                        'target'              => $target,
                        'actual'               => $actual,
                        'achievement_percent' => $achievement,
                        'score'                => $score,
                    ]);

                    $totalQuantScore += $score;
                }

                // Surat Peringatan
                $spWeights = ['sp1' => 20, 'sp2' => 30, 'sp3' => 50];
                $totalWarningScore = 0;
                foreach ($spWeights as $type => $weight) {
                    $isPresent = $request->boolean($type);
                    $score = $isPresent ? $weight : 0;

                    KpiWarning::updateOrCreate(
                        ['kpi_evaluation_id' => $evaluation->id, 'type' => $type],
                        ['is_present' => $isPresent, 'weight_percent' => $weight, 'score' => $score]
                    );
                    $totalWarningScore += $score;
                }

                // Special Assignment / Project — bisa lebih dari 1, tiap project +3.0 poin
                $evaluation->specialAssignments()->delete();

                $specialScore = 0;
                if ($request->has('project')) {
                    foreach ($request->project as $data) {
                        if (empty($data['name'])) {
                            continue;
                        }

                        KpiSpecialAssignment::create([
                            'kpi_evaluation_id' => $evaluation->id,
                            'name'              => $data['name'],
                            'is_present'        => true,
                            'score'             => 3,
                        ]);

                        $specialScore += 3;
                    }
                }
            }

            // ===== Hitung ulang total keseluruhan =====
            $penilaian1 = round($totalQuantScore + $finalQualityScore, 2);
            $penilaian2 = round($penilaian1 - $totalWarningScore, 2);
            $grandTotal = round($penilaian2 + $specialScore, 2);
            $grade = KpiEvaluation::calculateGrade($grandTotal);

            // Status keseluruhan: submitted hanya kalau primary sudah isi DAN
            // (tidak ada cross-dept ATAU cross-dept juga sudah isi)
            $primarySubmitted = $evaluation->qualityDetails()
                                    ->where('is_final_average', false)
                                    ->whereHas('submittedBy', fn($q) => true) // ada submitted_by
                                    ->get()
                                    ->contains(function ($detail) use ($evaluation) {
                                        return \App\Models\KpiQualityAssignment::where('employee_id', $evaluation->employee_id)
                                                ->where('kadept_id', $detail->submitted_by)
                                                ->where('is_primary', true)
                                                ->exists();
                                    });

            $finalStatus = ($primarySubmitted && $isComplete && $request->submit_type === 'submit')
                            ? 'submitted'
                            : 'draft';

            $evaluation->update([
                'total_quant_score'    => $totalQuantScore,
                'total_quality_score'  => $finalQualityScore,
                'penilaian_1'          => $penilaian1,
                'total_warning_score'  => $totalWarningScore,
                'penilaian_2'          => $penilaian2,
                'total_special_score'  => $specialScore,
                'grand_total'          => $grandTotal,
                'grade'                => $grade,
                'status'               => $finalStatus,
                'submitted_at'         => $finalStatus === 'submitted' ? now() : $evaluation->submitted_at,
            ]);

            AuditLog::record(
                'save_kpi_evaluation',
                'kpi',
                "Kadept {$user->name} simpan penilaian KPI untuk karyawan ID {$employeeId}",
                ['evaluation_id' => $evaluation->id, 'is_primary' => $isPrimary, 'grand_total' => $grandTotal]
            );
        });

        $message = $request->submit_type === 'submit'
            ? 'Penilaian berhasil disimpan. Status final tergantung penilai lain (jika ada cross-dept).'
            : 'Draft penilaian berhasil disimpan.';

        return redirect()->route('kpi.kadept.index')->with('success', $message);
    }
}