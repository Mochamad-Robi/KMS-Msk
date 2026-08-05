<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KpiQuantTemplate;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class KpiQuantTemplateController extends Controller
{
    // Tampilkan daftar template indikator kuantitatif
    public function index()
    {
        $templates = KpiQuantTemplate::orderBy('order')->get();
        $totalWeight = $templates->where('is_active', true)->sum('weight_percent');

        return view('admin.kpi.templates.index', compact('templates', 'totalWeight'));
    }

    // Simpan indikator baru
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'weight_percent' => 'required|numeric|min:0|max:100',
        ]);

        $maxOrder = KpiQuantTemplate::max('order') ?? 0;

        $template = KpiQuantTemplate::create([
            'name'           => $request->name,
            'weight_percent' => $request->weight_percent,
            'order'          => $maxOrder + 1,
            'is_active'      => true,
        ]);

        AuditLog::record(
            'create_kpi_template',
            'kpi',
            "Tambah indikator kuantitatif: {$template->name} ({$template->weight_percent}%)",
            ['template_id' => $template->id]
        );

        return back()->with('success', 'Indikator berhasil ditambahkan!');
    }

    // Update indikator (nama / bobot)
    public function update(Request $request, $id)
    {
        $template = KpiQuantTemplate::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'weight_percent' => 'required|numeric|min:0|max:100',
        ]);

        $template->update([
            'name'           => $request->name,
            'weight_percent' => $request->weight_percent,
        ]);

        AuditLog::record(
            'update_kpi_template',
            'kpi',
            "Update indikator kuantitatif: {$template->name}",
            ['template_id' => $template->id]
        );

        return back()->with('success', 'Indikator berhasil diperbarui!');
    }

    // Toggle aktif/nonaktif (tanpa hapus permanen, supaya data lama tetap konsisten)
    public function toggle($id)
    {
        $template = KpiQuantTemplate::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);

        return back()->with('success', 'Status indikator diperbarui.');
    }

    // Hapus indikator (hanya jika belum pernah dipakai di evaluasi manapun)
    public function destroy($id)
    {
        $template = KpiQuantTemplate::findOrFail($id);

        if ($template->details()->exists()) {
            return back()->with('error', 'Indikator ini sudah dipakai dalam penilaian, tidak bisa dihapus. Nonaktifkan saja.');
        }

        $template->delete();

        return back()->with('success', 'Indikator berhasil dihapus.');
    }

    // Reorder urutan tampil (drag-drop atau tombol naik/turun — sederhana dulu: swap order)
    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $index => $id) {
            KpiQuantTemplate::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}