<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KpiAssignment;
use App\Models\KpiEvaluation;
use App\Models\KpiPeriod;
use App\Models\User;
use App\Models\Department;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KpiAssignmentController extends Controller
{
    // Halaman utama: rekap semua departemen (untuk Admin)
    public function index()
    {
        $departments = Department::orderBy('name')->get();

        $deptStats = $departments->map(function ($dept) {
            $employeeCount = User::where('department_id', $dept->id)
                                  ->whereHas('role', fn($r) => $r->where('slug', 'user'))
                                  ->where('is_active', true)
                                  ->count();

            $assignedCount = KpiAssignment::whereHas('employee', fn($q) => $q->where('department_id', $dept->id))
                                  ->where('is_active', true)
                                  ->count();

            return [
                'department'     => $dept,
                'employee_count' => $employeeCount,
                'assigned_count' => $assignedCount,
            ];
        });

        return view('admin.kpi.assignments.index', compact('deptStats'));
    }

    // Detail 1 departemen: list karyawan + siapa kadept-nya + hasil KPI periode aktif
    public function showDepartment($deptId)
    {
        $department = Department::findOrFail($deptId);

        $employees = User::where('department_id', $deptId)
                        ->whereHas('role', fn($r) => $r->where('slug', 'user'))
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();

        $assignments = KpiAssignment::whereIn('employee_id', $employees->pluck('id'))
                            ->where('is_active', true)
                            ->with('kadept')
                            ->get()
                            ->keyBy('employee_id');

        $kadepts = User::whereHas('role', fn($r) => $r->where('slug', 'kadept'))
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();

        // Ambil periode terbaru (dipakai sebagai konteks tampilan hasil KPI)
        $latestPeriod = KpiPeriod::orderByDesc('year')->orderByDesc('quartal')->first();

        $evaluations = collect();
        if ($latestPeriod) {
            $evaluations = KpiEvaluation::where('kpi_period_id', $latestPeriod->id)
                                ->whereIn('employee_id', $employees->pluck('id'))
                                ->get()
                                ->keyBy('employee_id');
        }

        // Daftar semua periode untuk dropdown pilih periode lain (history)
        $allPeriods = KpiPeriod::orderByDesc('year')->orderByDesc('quartal')->get();

        return view('admin.kpi.assignments.department', compact(
            'department', 'employees', 'assignments', 'kadepts',
            'latestPeriod', 'evaluations', 'allPeriods'
        ));
    }

    // Lihat hasil KPI departemen pada periode tertentu (history)
    public function showDepartmentPeriod($deptId, $periodId)
    {
        $department = Department::findOrFail($deptId);
        $period = KpiPeriod::findOrFail($periodId);

        $employees = User::where('department_id', $deptId)
                        ->whereHas('role', fn($r) => $r->where('slug', 'user'))
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();

        $assignments = KpiAssignment::whereIn('employee_id', $employees->pluck('id'))
                            ->where('is_active', true)
                            ->with('kadept')
                            ->get()
                            ->keyBy('employee_id');

        $kadepts = User::whereHas('role', fn($r) => $r->where('slug', 'kadept'))
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();

        $evaluations = KpiEvaluation::where('kpi_period_id', $period->id)
                            ->whereIn('employee_id', $employees->pluck('id'))
                            ->get()
                            ->keyBy('employee_id');

        $allPeriods = KpiPeriod::orderByDesc('year')->orderByDesc('quartal')->get();

        return view('admin.kpi.assignments.department', [
            'department'   => $department,
            'employees'    => $employees,
            'assignments'  => $assignments,
            'kadepts'      => $kadepts,
            'latestPeriod' => $period,
            'evaluations'  => $evaluations,
            'allPeriods'   => $allPeriods,
        ]);
    }

    // Assign / ubah kadept untuk 1 karyawan
    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'kadept_id'   => 'required|exists:users,id',
        ]);

        $employee = User::findOrFail($request->employee_id);
        $kadept   = User::findOrFail($request->kadept_id);

        $isCrossDept = $employee->department_id !== $kadept->department_id;

        KpiAssignment::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'kadept_id'            => $kadept->id,
                'assigned_by'          => Auth::id(),
                'is_cross_department'  => $isCrossDept,
                'is_active'            => true,
            ]
        );

        AuditLog::record(
            'assign_kpi_kadept',
            'kpi',
            "Assign {$kadept->name} sebagai penilai KPI untuk {$employee->name}",
            ['employee_id' => $employee->id, 'kadept_id' => $kadept->id]
        );

        return back()->with('success', "{$kadept->name} berhasil di-assign menilai {$employee->name}.");
    }

    public function unassign($id)
    {
        $assignment = KpiAssignment::findOrFail($id);
        $employeeName = $assignment->employee->name ?? 'karyawan';

        $assignment->delete();

        return back()->with('success', "Assignment untuk {$employeeName} berhasil dihapus.");
    }

    public function autoAssignDepartment($deptId)
    {
        $department = Department::findOrFail($deptId);

        $kadept = User::where('department_id', $deptId)
                       ->whereHas('role', fn($r) => $r->where('slug', 'kadept'))
                       ->where('is_active', true)
                       ->first();

        if (!$kadept) {
            return back()->with('error', "Belum ada user dengan role Kadept di departemen {$department->name}.");
        }

        $employees = User::where('department_id', $deptId)
                        ->whereHas('role', fn($r) => $r->where('slug', 'user'))
                        ->where('is_active', true)
                        ->get();

        $count = 0;
        foreach ($employees as $employee) {
            $exists = KpiAssignment::where('employee_id', $employee->id)
                        ->where('is_active', true)
                        ->exists();

            if (!$exists) {
                KpiAssignment::create([
                    'kadept_id'           => $kadept->id,
                    'employee_id'         => $employee->id,
                    'assigned_by'         => Auth::id(),
                    'is_cross_department' => false,
                    'is_active'           => true,
                ]);
                $count++;
            }
        }

        AuditLog::record(
            'auto_assign_kpi',
            'kpi',
            "Auto-assign {$count} karyawan di {$department->name} ke kadept {$kadept->name}",
            ['department_id' => $deptId, 'kadept_id' => $kadept->id, 'count' => $count]
        );

        return back()->with('success', "{$count} karyawan berhasil di-assign ke {$kadept->name}.");
    }
}