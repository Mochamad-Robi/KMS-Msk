<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    // Halaman verifikasi OTP
    public function showVerify()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.2fa-verify');
    }

    // Proses verifikasi OTP
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::find(session('2fa_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        $valid = $this->google2fa->verifyKey(
            $user->two_fa_secret,
            $request->otp
        );

        if (!$valid) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah expired.']);
        }

        session()->forget('2fa_user_id');
        Auth::login($user);
        $user->update(['last_login_at' => now()]);

        return redirect()->route('dashboard');
    }

    // Halaman setup 2FA (generate QR)
    public function showSetup()
    {
        $user   = Auth::user();
        $secret = $this->google2fa->generateSecretKey();

        session(['2fa_secret_temp' => $secret]);

        $qrUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.2fa-setup', compact('qrUrl', 'secret'));
    }

    // Simpan 2FA setelah scan QR
    public function enableSetup(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $secret = session('2fa_secret_temp');
        $valid  = $this->google2fa->verifyKey($secret, $request->otp);

        if (!$valid) {
            return back()->withErrors(['otp' => 'Kode OTP tidak cocok. Coba scan ulang QR.']);
        }

        Auth::user()->update([
            'two_fa_secret'  => $secret,
            'two_fa_enabled' => true,
        ]);

        session()->forget('2fa_secret_temp');

        return redirect()->route('dashboard')->with('success', '2FA berhasil diaktifkan!');
    }

    // Nonaktifkan 2FA
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Password tidak sesuai.']);
        }

        Auth::user()->update([
            'two_fa_secret'  => null,
            'two_fa_enabled' => false,
        ]);

        return back()->with('success', '2FA berhasil dinonaktifkan.');
    }
}