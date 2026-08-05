<?php

namespace App\Http\Controllers;

use App\Models\KpiForm;
use App\Models\KpiSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KpiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $form = KpiForm::where('is_active', true)
                    ->where(function ($q) use ($user) {
                        $q->where('position_id', $user->position_id)
                          ->orWhere('department_id', $user->department_id)
                          ->orWhereNull('position_id');
                    })
                    ->first();

        $submissions = KpiSubmission::where('user_id', $user->id)
                            ->latest()
                            ->get();

        return view('kpi.index', compact('form', 'submissions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $form = KpiForm::findOrFail($request->kpi_form_id);

        // Cek sudah submit bulan ini belum
        $existing = KpiSubmission::where('user_id', $user->id)
                        ->where('kpi_form_id', $form->id)
                        ->where('period_month', now()->month)
                        ->where('period_year', now()->year)
                        ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengisi KPI untuk bulan ini.');
        }

        $request->validate([
            'kpi_form_id' => 'required|exists:kpi_forms,id',
            'form_data'   => 'required|array',
        ]);

        KpiSubmission::create([
            'kpi_form_id'  => $form->id,
            'user_id'      => $user->id,
            'form_data'    => $request->form_data,
            'period_month' => now()->month,
            'period_year'  => now()->year,
            'status'       => 'submitted',
        ]);

        return back()->with('success', 'KPI berhasil disimpan!');
    }

    // Download PDF per user
    public function downloadUser($userId = null)
    {
        $user = Auth::user();

        // Admin bisa download semua, user hanya miliknya
        if ($userId && $user->isAdmin()) {
            $targetUser = \App\Models\User::findOrFail($userId);
        } else {
            $targetUser = $user;
        }

        $submissions = KpiSubmission::with('form')
                            ->where('user_id', $targetUser->id)
                            ->orderBy('period_year', 'desc')
                            ->orderBy('period_month', 'desc')
                            ->get();

        $pdf = Pdf::loadView('kpi.pdf-user', compact('targetUser', 'submissions'));

        return $pdf->download('KPI_' . $targetUser->employee_id . '_' . now()->format('Ymd') . '.pdf');
    }

    // Download PDF summary per departemen (Admin only)
    public function downloadDept($deptId)
    {
        $dept = \App\Models\Department::findOrFail($deptId);

        $submissions = KpiSubmission::with(['user', 'form'])
                            ->whereHas('user', fn($q) => $q->where('department_id', $deptId))
                            ->where('period_year', now()->year)
                            ->orderBy('period_month')
                            ->get();

        $pdf = Pdf::loadView('kpi.pdf-dept', compact('dept', 'submissions'));

        return $pdf->download('KPI_' . $dept->code . '_' . now()->year . '.pdf');
    }
}