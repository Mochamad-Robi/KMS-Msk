<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                            ->latest()
                            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

   public function read($id)
{
    $notif = Notification::where('user_id', Auth::id())->findOrFail($id);
    $notif->update(['is_read' => true]);

    if ($notif->link) {
        return redirect($notif->link);
    }

    return redirect()->route('dashboard');
}

    public function readAll()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return back();
    }
}