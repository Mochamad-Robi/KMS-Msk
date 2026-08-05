<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('creator')->latest()->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'title'        => 'required|string|max:255',
        'category'     => 'required|in:pemberitahuan,himbauan,promosi-umkm',
        'sub_category' => 'nullable|required_if:category,promosi-umkm|in:food-beverage,otomotif,properti,gadget',
        'content'      => 'nullable|string',
        'image'        => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        'publish_at'   => 'nullable|date',
        'expire_at'    => 'nullable|date|after_or_equal:publish_at',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $file      = $request->file('image');
        $filename  = time() . '_' . str($request->title)->slug() . '.' . $file->getClientOriginalExtension();
        $imagePath = $file->storeAs('news', $filename);
    }

    $news = News::create([
        'title'        => $request->title,
        'category'     => $request->category,
        'sub_category' => $request->category === 'promosi-umkm' ? $request->sub_category : null,
        'content'      => $request->content,
        'image_path'   => $imagePath,
        'created_by'   => Auth::id(),
        'is_active'    => true,
        'publish_at'   => $request->publish_at ?? today(),
        'expire_at'    => $request->expire_at,
    ]);

    // Notif ke semua user
    $this->notifyAllUsers($news);

    return redirect()->route('admin.news.index')
                     ->with('success', 'Berita berhasil dipublikasikan!');
}

    private function notifyAllUsers(News $news): void
{
    $users = User::where('is_active', true)
                 ->where('id', '!=', Auth::id())
                 ->get();

    $categoryLabel = News::CATEGORIES[$news->category] ?? 'Berita';

    $notifs = $users->map(fn($user) => [
        'user_id'    => $user->id,
        'type'       => 'news',
        'title'      => '📰 ' . $categoryLabel . ' Baru',
        'message'    => $news->title,
        'link'       => route('news.show', $news->id),
        'is_read'    => false,
        'created_at' => now(),
        'updated_at' => now(),
    ])->toArray();

    Notification::insert($notifs);
}

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        if ($news->image_path) Storage::delete($news->image_path);
        $news->delete();
        return back()->with('success', 'Berita berhasil dihapus.');
    }

    public function toggle($id)
    {
        $news = News::findOrFail($id);
        $news->update(['is_active' => !$news->is_active]);
        return back()->with('success', 'Status berita diperbarui.');
    }
}