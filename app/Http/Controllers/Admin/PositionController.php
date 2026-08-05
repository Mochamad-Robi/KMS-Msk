<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with(['department', 'grade'])
            ->orderBy('department_id')
            ->orderBy('name')
            ->get();

        return view('admin.master.positions.index', compact('positions'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $grades      = Grade::active()->ordered()->get();
        return view('admin.master.positions.create', compact('departments', 'grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'grade_id'      => 'nullable|exists:grades,id',
        ]);

        Position::create($request->only('name', 'department_id', 'grade_id'));

        return redirect()->route('admin.master.positions.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Position $position)
    {
        $departments = Department::orderBy('name')->get();
        $grades      = Grade::active()->ordered()->get();
        return view('admin.master.positions.edit', compact('position', 'departments', 'grades'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'grade_id'      => 'nullable|exists:grades,id',
        ]);

        $position->update($request->only('name', 'department_id', 'grade_id'));

        return redirect()->route('admin.master.positions.index')
            ->with('success', 'Jabatan berhasil diupdate.');
    }

    public function destroy(Position $position)
    {
        if ($position->users()->exists()) {
            return back()->with('error', 'Tidak bisa hapus jabatan yang masih digunakan karyawan.');
        }

        $position->delete();

        return redirect()->route('admin.master.positions.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}