<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
                            ->latest()
                            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title'      => 'required|string|max:255',
        'content'    => 'required|string',
        'publish_at' => 'nullable|date',
        'expire_at'  => 'nullable|date|after_or_equal:publish_at',
    ]);

    Announcement::create([
        'title'      => $request->title,
        'content'    => $request->content,
        'created_by' => Auth::user()->id,  // ← pastikan pakai ->id bukan ->employee_id
        'is_active'  => true,
        'publish_at' => $request->publish_at ?? today(),
        'expire_at'  => $request->expire_at,
    ]);

    return back()->with('success', 'Pengumuman berhasil dibuat!');
}

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function toggle($id)
    {
        $ann = Announcement::findOrFail($id);
        $ann->update(['is_active' => !$ann->is_active]);
        return back()->with('success', 'Status pengumuman diperbarui.');
    }
}