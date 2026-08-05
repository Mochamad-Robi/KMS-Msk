<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::ordered()->get();
        return view('admin.master.grades.index', compact('grades'));
    }

    public function create()
    {
        return view('admin.master.grades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'code'     => 'nullable|string|max:20|unique:grades,code',
            'level'    => 'required|integer|min:1|unique:grades,level',
            'is_active' => 'boolean',
        ]);

        $grade = Grade::create([
            'name'      => $request->name,
            'code'      => $request->code,
            'level'     => $request->level,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('create', 'master-grade', "Tambah grade: {$grade->name}", ['grade_id' => $grade->id]);

        return redirect()->route('admin.master.grades.index')
            ->with('success', "Grade {$grade->name} berhasil ditambahkan.");
    }

    public function edit(Grade $grade)
    {
        return view('admin.master.grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'code'      => 'nullable|string|max:20|unique:grades,code,' . $grade->id,
            'level'     => 'required|integer|min:1|unique:grades,level,' . $grade->id,
            'is_active' => 'boolean',
        ]);

        $grade->update([
            'name'      => $request->name,
            'code'      => $request->code,
            'level'     => $request->level,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('update', 'master-grade', "Edit grade: {$grade->name}", ['grade_id' => $grade->id]);

        return redirect()->route('admin.master.grades.index')
            ->with('success', "Grade {$grade->name} berhasil diperbarui.");
    }

    public function destroy(Grade $grade)
    {
        // Cegah hapus kalau masih dipakai
        if ($grade->users()->exists() || $grade->positions()->exists()) {
            return redirect()->route('admin.master.grades.index')
                ->with('error', "Grade {$grade->name} tidak bisa dihapus karena masih digunakan.");
        }

        AuditLog::record('delete', 'master-grade', "Hapus grade: {$grade->name}", ['grade_id' => $grade->id]);

        $grade->delete();

        return redirect()->route('admin.master.grades.index')
            ->with('success', "Grade {$grade->name} berhasil dihapus.");
    }
}