<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        $categories = [
            'all'           => 'Semua',
            'pemberitahuan' => 'Pemberitahuan',
            'himbauan'      => 'Himbauan',
            'promosi-umkm'  => 'Promosi UMKM',
            'birthday'      => 'Ulang Tahun 🎂',
        ];

        $subCategories = [
            'food-beverage' => 'Food & Beverage',
            'otomotif'      => 'Otomotif',
            'properti'      => 'Properti',
            'gadget'        => 'Gadget',
        ];

        $activeCategory    = request('category', 'all');
        $activeSubCategory = request('sub_category', 'all');

        $news = News::active()
                    ->withCount('comments')
                    ->when($activeCategory !== 'all', fn($q) => $q->where('category', $activeCategory))
                    ->when(
                        $activeCategory === 'promosi-umkm' && $activeSubCategory !== 'all',
                        fn($q) => $q->where('sub_category', $activeSubCategory)
                    )
                    ->latest()
                    ->paginate(12);

        return view('news.index', compact('news', 'categories', 'subCategories', 'activeCategory', 'activeSubCategory'));
    }

    public function show($id)
    {
        $news = News::with(['comments.user', 'birthdayUser.department'])
                    ->withCount('comments')
                    ->active()
                    ->findOrFail($id);

        $news->incrementViews();

        AuditLog::record(
            'view_news',
            'news',
            "Membaca berita: {$news->title}",
            ['news_id' => $news->id, 'title' => $news->title]
        );

        $comments = $news->comments;

        return view('news.show', compact('news', 'comments'));
    }

    public function comment(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|max:1000']);

        NewsComment::create([
            'news_id' => $id,
            'user_id' => Auth::id(),
            'body'    => $request->comment,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function likeComment(Request $request, $commentId)
    {
        $comment = NewsComment::findOrFail($commentId);
        $comment->increment('likes');

        return back();
    }
}