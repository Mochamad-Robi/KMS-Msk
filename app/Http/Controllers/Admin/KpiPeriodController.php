<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KpiPeriod;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class KpiPeriodController extends Controller
{
    // Tampilkan daftar periode KPI
    public function index()
    {
        $periods = KpiPeriod::orderByDesc('year')
                        ->orderByDesc('quartal')
                        ->get();

        return view('admin.kpi.periods.index', compact('periods'));
    }

    // Form tambah periode baru
    public function create()
    {
        return view('admin.kpi.periods.create');
    }

    // Simpan periode baru
    public function store(Request $request)
    {
        $request->validate([
            'year'       => 'required|integer|min:2020|max:2100',
            'quartal'    => 'required|in:Q1,Q2,Q3,Q4',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $exists = KpiPeriod::where('year', $request->year)
                    ->where('quartal', $request->quartal)
                    ->exists();

        if ($exists) {
            return back()->with('error', "Periode {$request->quartal} {$request->year} sudah ada.")
                          ->withInput();
        }

        $period = KpiPeriod::create([
            'year'       => $request->year,
            'quartal'    => $request->quartal,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'is_open'    => false,
        ]);

        AuditLog::record(
            'create_kpi_period',
            'kpi',
            "Buat periode KPI: {$period->quartal} {$period->year}",
            ['period_id' => $period->id]
        );

        return redirect()->route('admin.kpi.periods.index')
                         ->with('success', 'Periode KPI berhasil dibuat!');
    }

    // Toggle buka/tutup periode (gerbang utama: kadept bisa isi atau tidak)
    public function toggleOpen($id)
    {
        $period = KpiPeriod::findOrFail($id);
        $period->update(['is_open' => !$period->is_open]);

        $status = $period->is_open ? 'dibuka' : 'ditutup';

        AuditLog::record(
            'toggle_kpi_period',
            'kpi',
            "Periode KPI {$period->quartal} {$period->year} {$status}",
            ['period_id' => $period->id, 'is_open' => $period->is_open]
        );

        return back()->with('success', "Periode {$period->quartal} {$period->year} berhasil {$status}.");
    }

    // Hapus periode (hanya jika belum ada evaluasi terkait)
    public function destroy($id)
    {
        $period = KpiPeriod::findOrFail($id);

        if ($period->evaluations()->exists()) {
            return back()->with('error', 'Periode ini sudah punya data penilaian, tidak bisa dihapus.');
        }

        $period->delete();

        return back()->with('success', 'Periode KPI berhasil dihapus.');
    }
}