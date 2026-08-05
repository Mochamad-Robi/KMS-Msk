<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('parent')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.master.departments.index', compact('departments'));
    }

    public function create()
    {
        $parents = Department::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.master.departments.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:departments,code',
            'parent_id' => 'nullable|exists:departments,id',
        ]);

        Department::create($request->only('name', 'code', 'parent_id'));

        return redirect()->route('admin.master.departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Department $department)
    {
        $parents = Department::whereNull('parent_id')
            ->where('id', '!=', $department->id)
            ->orderBy('name')
            ->get();

        return view('admin.master.departments.edit', compact('department', 'parents'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:departments,code,' . $department->id,
            'parent_id' => 'nullable|exists:departments,id',
        ]);

        $department->update($request->only('name', 'code', 'parent_id'));

        return redirect()->route('admin.master.departments.index')
            ->with('success', 'Departemen berhasil diupdate.');
    }

    public function destroy(Department $department)
    {
        if ($department->hasChildren()) {
            return back()->with('error', 'Tidak bisa hapus departemen yang memiliki sub-departemen.');
        }

        if ($department->users()->exists()) {
            return back()->with('error', 'Tidak bisa hapus departemen yang masih memiliki karyawan.');
        }

        if ($department->positions()->exists()) {
            return back()->with('error', 'Tidak bisa hapus departemen yang masih memiliki jabatan.');
        }

        $department->delete();

        return redirect()->route('admin.master.departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}