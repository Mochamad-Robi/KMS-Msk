<?php
namespace App\Http\Controllers;

use App\Models\NewsComment;
use App\Models\News; // ← ganti PortalNews jadi News
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsCommentController extends Controller
{
    public function store(Request $request, News $news) // ← ganti PortalNews jadi News
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $news->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $request->body,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function like(NewsComment $comment)
    {
        $comment->increment('likes');
        return back();
    }

    public function destroy(NewsComment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Komentar dihapus.');
    }
}