<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'password'    => 'required|string',
            'captcha'     => 'required|captcha',
        ], [
            'captcha.captcha' => 'Kode captcha tidak valid, coba lagi.',
        ]);

        $user = User::where('employee_id', $request->employee_id)
                    ->where('is_active', true)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            LoginAttempt::create([
                'employee_id' => $request->employee_id,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'is_success'  => false,
            ]);

            return back()->withErrors([
                'employee_id' => 'ID Karyawan atau password salah.',
            ])->withInput();
        }

        LoginAttempt::create([
            'user_id'     => $user->id,
            'employee_id' => $user->employee_id,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'is_success'  => true,
        ]);

        if ($user->two_fa_enabled) {
            session(['2fa_user_id' => $user->id]);
            return redirect()->route('2fa.verify');
        }

        Auth::login($user, $request->boolean('remember'));
        $user->update(['last_login_at' => now()]);

        AuditLog::record('login', 'auth', "User {$user->name} login", ['employee_id' => $user->employee_id]);

        // Auto-generate birthday post
        $this->generateBirthdayPosts();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function generateBirthdayPosts(): void
    {
        $birthdayUsers = User::whereNotNull('birth_date')
            ->whereMonth('birth_date', now()->month)
            ->whereDay('birth_date', now()->day)
            ->where('is_active', true)
            ->get();

        foreach ($birthdayUsers as $birthdayUser) {
            // Cek apakah post birthday sudah ada hari ini
            $alreadyExists = \App\Models\News::where('category', 'birthday')
                ->where('birthday_user_id', $birthdayUser->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($alreadyExists) continue;

            // Hitung umur
            $age      = $birthdayUser->birth_date->age;
            $deptName = $birthdayUser->department?->name ?? '-';

            // Buat konten
            $content = "🎂 Hari ini, <strong>{$birthdayUser->name}</strong> dari departemen <strong>{$deptName}</strong> merayakan ulang tahun yang ke-<strong>{$age}</strong>!<br><br>Yuk ucapkan selamat ulang tahun dan doakan yang terbaik! 🎉🎊";

            // Buat post berita birthday
            $news = \App\Models\News::create([
                'title'            => "🎂 Selamat Ulang Tahun, {$birthdayUser->name}!",
                'category'         => 'birthday',
                'content'          => $content,
                'image_path'       => $birthdayUser->avatar,
                'created_by'       => 1,
                'birthday_user_id' => $birthdayUser->id,
                'is_active'        => true,
                'publish_at'       => today(),
            ]);

            // Notif ke semua user kecuali si ultah
            $allUsers = User::where('is_active', true)
                ->where('id', '!=', $birthdayUser->id)
                ->get();

            $notifs = $allUsers->map(fn($u) => [
                'user_id'    => $u->id,
                'type'       => 'birthday',
                'title'      => '🎂 Ulang Tahun Karyawan',
                'message'    => "{$birthdayUser->name} berulang tahun hari ini! Yuk ucapkan selamat! 🎉",
                'link'       => route('news.show', $news->id),
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            Notification::insert($notifs);
        }
    }
}