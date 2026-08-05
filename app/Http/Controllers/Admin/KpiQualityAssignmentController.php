<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\KpiQualityAssignment;
    use App\Models\KpiAssignment;
    use App\Models\User;
    use App\Models\Department;
    use App\Models\AuditLog;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class KpiQualityAssignmentController extends Controller
    {
        // Halaman: pilih karyawan yang akan dapat penilai kualitatif TAMBAHAN (cross-dept)
  public function index()
{
    $departments = Department::orderBy('name')->get();
    return view('admin.kpi.quality-assignments.index', compact('departments'));
}

        // Detail 1 departemen: list karyawan + status cross-dept kualitatifnya
        public function showDepartment($deptId)
        {
            $department = Department::findOrFail($deptId);

            $employees = User::where('department_id', $deptId)
                            ->whereHas('role', fn($r) => $r->where('slug', 'user'))
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->get();

            // Assignment kualitatif yang sudah ada untuk karyawan-karyawan ini
            // (termasuk yang primary/dept sendiri, otomatis dari kpi_assignments)
            $primaryAssignments = KpiAssignment::whereIn('employee_id', $employees->pluck('id'))
                                    ->where('is_active', true)
                                    ->with('kadept')
                                    ->get()
                                    ->keyBy('employee_id');

            $crossAssignments = KpiQualityAssignment::whereIn('employee_id', $employees->pluck('id'))
                                    ->where('is_primary', false)
                                    ->where('is_active', true)
                                    ->with('kadept')
                                    ->get()
                                    ->keyBy('employee_id');

            // Daftar semua kadept DI LUAR departemen ini (kandidat cross-dept)
            $crossKadepts = User::whereHas('role', fn($r) => $r->where('slug', 'kadept'))
                                ->where('department_id', '!=', $deptId)
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get();

            return view('admin.kpi.quality-assignments.department', compact(
                'department', 'employees', 'primaryAssignments', 'crossAssignments', 'crossKadepts'
            ));
        }

        // Set / ubah kadept cross-dept untuk 1 karyawan
        public function assignCross(Request $request)
        {
            $request->validate([
                'employee_id' => 'required|exists:users,id',
                'kadept_id'   => 'required|exists:users,id',
            ]);

            $employee = User::findOrFail($request->employee_id);
            $kadept   = User::findOrFail($request->kadept_id);

            // Pastikan tidak assign kadept dept sendiri sebagai "cross"
            if ($kadept->department_id === $employee->department_id) {
                return back()->with('error', 'Kadept yang dipilih berasal dari departemen yang sama. Pilih kadept dari departemen lain untuk cross-dept.');
            }

            // Setiap karyawan hanya boleh punya 1 assignment cross-dept aktif
            // (hapus yang lama dulu kalau ada, baru buat yang baru)
            KpiQualityAssignment::where('employee_id', $employee->id)
                ->where('is_primary', false)
                ->delete();

            KpiQualityAssignment::create([
                'kadept_id'   => $kadept->id,
                'employee_id' => $employee->id,
                'assigned_by' => Auth::id(),
                'is_primary'  => false,
                'is_active'   => true,
            ]);

            AuditLog::record(
                'assign_kpi_quality_cross',
                'kpi',
                "Assign {$kadept->name} sebagai penilai kualitatif cross-dept untuk {$employee->name}",
                ['employee_id' => $employee->id, 'kadept_id' => $kadept->id]
            );

            return back()->with('success', "{$kadept->name} berhasil di-assign sebagai penilai kualitatif tambahan untuk {$employee->name}.");
        }

        // Hapus assignment cross-dept
        public function unassignCross($id)
        {
            $assignment = KpiQualityAssignment::where('is_primary', false)->findOrFail($id);
            $employeeName = $assignment->employee->name ?? 'karyawan';

            $assignment->delete();

            return back()->with('success', "Assignment kualitatif cross-dept untuk {$employeeName} berhasil dihapus.");
        }

        // Sinkronisasi otomatis: pastikan setiap karyawan yang punya kpi_assignments (kuantitatif)
        // juga punya kpi_quality_assignments dengan is_primary=true dari kadept yang sama
        public function syncPrimaryAssignments()
        {
            $primaryAssignments = KpiAssignment::where('is_active', true)->get();
            $synced = 0;

            foreach ($primaryAssignments as $assignment) {
                $exists = KpiQualityAssignment::where('employee_id', $assignment->employee_id)
                            ->where('is_primary', true)
                            ->where('is_active', true)
                            ->exists();

                if (!$exists) {
                    KpiQualityAssignment::create([
                        'kadept_id'   => $assignment->kadept_id,
                        'employee_id' => $assignment->employee_id,
                        'assigned_by' => Auth::id(),
                        'is_primary'  => true,
                        'is_active'   => true,
                    ]);
                    $synced++;
                }
            }

            return back()->with('success', "{$synced} assignment kualitatif (primary) berhasil disinkronkan dari assignment kuantitatif.");
        }
    }