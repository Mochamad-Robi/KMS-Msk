<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
   public function index(Request $request)
{
    $search = $request->input('search');

    $users = \App\Models\User::with(['role', 'department', 'position'])
                 ->withCount(['loginAttempts as login_count' => function ($q) {
                     $q->where('is_success', true);
                 }])
                 ->when($search, function ($q) use ($search) {
                     $q->where(function ($q) use ($search) {
                         $q->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                           ->orWhere('employee_id', 'like', "%{$search}%");
                     });
                 })
                 ->latest()
                 ->paginate(10)
                 ->withQueryString(); // supaya search query ikut di pagination link

    $birthdayGifts = \App\Models\BirthdayGift::where('year', now()->year)
                        ->get()
                        ->keyBy('user_id');

    return view('admin.users.index', compact('users', 'birthdayGifts', 'search'));
}

    public function create()
    {
        $roles       = Role::all();
        $departments = Department::orderBy('name')->get();
        $positions   = Position::orderBy('name')->get();
        $grades      = Grade::active()->ordered()->get();

        return view('admin.users.create', compact('roles', 'departments', 'positions', 'grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'   => 'required|string|unique:users,employee_id',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'phone'         => 'nullable|string|max:20',
            'birth_date'    => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'position_id'   => 'nullable|exists:positions,id',
            'role_id'       => 'required|exists:roles,id',
            'grade_id'      => 'required|exists:grades,id',
            'avatar'        => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'employee_id'   => $request->employee_id,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'birth_date'    => $request->birth_date,
            'department_id' => $request->department_id,
            'position_id'   => $request->position_id,
            'role_id'       => $request->role_id,
            'grade_id'      => $request->grade_id,
            'is_active'     => true,
        ];

        if ($request->hasFile('avatar')) {
            $file     = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('avatars', $filename);
            $data['avatar'] = 'avatars/' . $filename;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user        = User::findOrFail($id);
        $roles       = Role::all();
        $departments = Department::orderBy('name')->get();
        $positions   = Position::orderBy('name')->get();
        $grades      = Grade::active()->ordered()->get();

        return view('admin.users.edit', compact('user', 'roles', 'departments', 'positions', 'grades'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'employee_id'   => 'required|string|unique:users,employee_id,' . $id,
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'password'      => 'nullable|min:8|confirmed',
            'phone'         => 'nullable|string|max:20',
            'birth_date'    => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'position_id'   => 'nullable|exists:positions,id',
            'role_id'       => 'required|exists:roles,id',
            'grade_id'      => 'required|exists:grades,id',
            'avatar'        => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'employee_id'   => $request->employee_id,
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'birth_date'    => $request->birth_date,
            'department_id' => $request->department_id,
            'position_id'   => $request->position_id,
            'role_id'       => $request->role_id,
            'grade_id'      => $request->grade_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $file     = $request->file('avatar');
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $filename);
            $data['avatar'] = 'avatars/' . $filename;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Karyawan berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'Status karyawan diperbarui.');
    }

    public function updateBirthdayMessage(Request $request, $id)
    {
        $request->validate([
            'gift_code' => 'required|string|max:100',
            'message'   => 'nullable|string|max:500',
        ]);

        \App\Models\BirthdayGift::updateOrCreate(
            ['user_id' => $id, 'year' => now()->year],
            [
                'gift_code'  => strtoupper($request->gift_code),
                'message'    => $request->message,
                'is_claimed' => false,
            ]
        );

        return back()->with('success', 'Gift ulang tahun berhasil disimpan!');
    }
}