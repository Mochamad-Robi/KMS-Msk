<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentBookmark;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = DocumentBookmark::with('document.department')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function toggle($documentId)
    {
        $user     = Auth::user();
        $document = Document::findOrFail($documentId);

        $existing = DocumentBookmark::where('user_id', $user->id)
                        ->where('document_id', $document->id)
                        ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Bookmark dihapus.';
        } else {
            DocumentBookmark::create([
                'user_id'     => $user->id,
                'document_id' => $document->id,
            ]);
            $message = 'Dokumen disimpan ke bookmark!';
        }

        return back()->with('success', $message);
    }
}