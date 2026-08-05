<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\KpiPeriod;
use App\Models\KpiEvaluation;
use App\Models\KpiQualityAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KpiRekapController extends Controller
{
    // Halaman utama: pilih departemen + periode untuk lihat rekap
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $periods = KpiPeriod::orderByDesc('year')->orderByDesc('quartal')->get();

        return view('admin.kpi.rekap.index', compact('departments', 'periods'));
    }

    // Detail rekap 1 departemen pada periode tertentu
    public function show($deptId, $periodId)
    {
        $department = Department::findOrFail($deptId);
        $period = KpiPeriod::findOrFail($periodId);

        $rows = $this->buildRows($deptId, $period);

        $totalEmployees = $rows->count();
        $completeCount = $rows->where('is_complete', true)->count();
        $allComplete = $totalEmployees > 0 && $completeCount === $totalEmployees;

        return view('admin.kpi.rekap.show', compact(
            'department', 'period', 'rows', 'totalEmployees', 'completeCount', 'allComplete'
        ));
    }

    // Generate PDF laporan KPI 1 departemen (semua karyawan, format lengkap per assessor)
    public function downloadPdf($deptId, $periodId)
    {
        $department = Department::findOrFail($deptId);
        $period = KpiPeriod::findOrFail($periodId);

        $rows = $this->buildRows($deptId, $period);

        $totalEmployees = $rows->count();
        $completeCount = $rows->where('is_complete', true)->count();
        $allComplete = $totalEmployees > 0 && $completeCount === $totalEmployees;

        abort_unless($allComplete, 403, 'Belum semua karyawan selesai dinilai. PDF tidak bisa di-generate.');

        // Siapkan data detail lengkap per karyawan untuk PDF
        $employeeReports = $rows->map(function ($row) {
            return $this->buildEmployeeReportData($row['employee'], $row['evaluation']);
        });

        $pdf = Pdf::loadView('admin.kpi.rekap.pdf', compact('department', 'period', 'employeeReports'))
                   ->setPaper('a4', 'portrait');

        $filename = 'KPI_' . str($department->name)->slug() . '_' . $period->quartal . '_' . $period->year . '.pdf';

        return $pdf->download($filename);
    }

    // Helper: bangun baris status per karyawan (dipakai di show() dan downloadPdf())
    private function buildRows($deptId, $period)
    {
        $employees = User::where('department_id', $deptId)
                        ->whereHas('role', fn($r) => $r->where('slug', 'user'))
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();

        return $employees->map(function ($employee) use ($period) {
            $evaluation = KpiEvaluation::where('kpi_period_id', $period->id)
                            ->where('employee_id', $employee->id)
                            ->with(['qualityDetails.submittedBy', 'quantDetails', 'warnings', 'specialAssignments'])
                            ->first();

            $hasCross = KpiQualityAssignment::where('employee_id', $employee->id)
                            ->where('is_primary', false)
                            ->where('is_active', true)
                            ->exists();

            $isComplete = false;
            if ($evaluation) {
                $submissionCount = $evaluation->qualityDetails->where('is_final_average', false)->count();
                $isComplete = $evaluation->status === 'submitted'
                    && (!$hasCross || $submissionCount >= 2);
            }

            return [
                'employee'    => $employee,
                'evaluation'  => $evaluation,
                'has_cross'   => $hasCross,
                'is_complete' => $isComplete,
            ];
        });
    }

    // Helper: susun data 1 karyawan untuk ditampilkan di PDF
    // (header info, breakdown core value per assessor, kuantitatif, SP, special)
    private function buildEmployeeReportData($employee, $evaluation)
    {
        $qualitySubmissions = $evaluation->qualityDetails->where('is_final_average', false)->values();
        $qualityFinal = $evaluation->qualityDetails->firstWhere('is_final_average', true);

        // Assessor list: primary dulu, baru cross
        $assignments = KpiQualityAssignment::where('employee_id', $employee->id)
                            ->where('is_active', true)
                            ->with('kadept')
                            ->orderByDesc('is_primary')
                            ->get();

        $assessors = $assignments->map(function ($assignment) use ($qualitySubmissions) {
            $submission = $qualitySubmissions->firstWhere('submitted_by', $assignment->kadept_id);
            return [
                'name'       => $assignment->kadept->name,
                'is_primary' => $assignment->is_primary,
                'submission' => $submission,
            ];
        });

        $coreValueFields = [
            'integritas'        => 'Integritas',
            'kekeluargaan'      => 'Kekeluargaan',
            'handal'            => 'Handal',
            'loyalitas'         => 'Loyalitas',
            'amanah'            => 'Amanah',
            'saling_menghargai' => 'Saling Menghargai',
        ];

        return [
            'employee'        => $employee,
            'evaluation'      => $evaluation,
            'assessors'       => $assessors,
            'qualityFinal'    => $qualityFinal,
            'coreValueFields' => $coreValueFields,
            'quantDetails'    => $evaluation->quantDetails->sortBy('order'),
            'warnings'        => $evaluation->warnings,
            'specialAssignments' => $evaluation->specialAssignments,
        ];
    }
}