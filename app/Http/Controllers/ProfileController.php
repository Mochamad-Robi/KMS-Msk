<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ];

        // Validasi avatar hanya untuk admin/super-user
        if ($user->isAdmin() || $user->isSuperUser()) {
            $rules['avatar'] = 'nullable|file|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        $data = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        // Upload avatar — hanya admin/super-user
        if (($user->isAdmin() || $user->isSuperUser()) && $request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $file     = $request->file('avatar');
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $filename);
            $data['avatar'] = 'avatars/' . $filename;
        }

        // Update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}