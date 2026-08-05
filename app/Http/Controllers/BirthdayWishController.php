<?php

namespace App\Http\Controllers;

use App\Models\BirthdayWish;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BirthdayWishController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $fromUser = Auth::user();
        $year     = now()->year;

        // Cegah kirim ke diri sendiri
        if ($fromUser->id === $user->id) {
            return back()->with('error', 'Tidak bisa mengirim ucapan ke diri sendiri.');
        }

        // Cegah kirim 2 kali di tahun yang sama
        if (BirthdayWish::hasUserSentWish($fromUser->id, $user->id, $year)) {
            return back()->with('error', 'Kamu sudah mengirim ucapan untuk ' . $user->name . ' tahun ini.');
        }

        BirthdayWish::create([
            'from_user_id' => $fromUser->id,
            'to_user_id'   => $user->id,
            'message'      => $request->message,
            'year'         => $year,
        ]);

        // Kirim notif private ke yang ulang tahun
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'birthday',
            'title'   => '💌 Ucapan Ulang Tahun',
            'message' => "{$fromUser->name}: \"{$request->message}\"",
            'is_read' => false,
        ]);

        return back()->with('success', 'Ucapan berhasil dikirim ke ' . $user->name . '! 🎉');
    }
}