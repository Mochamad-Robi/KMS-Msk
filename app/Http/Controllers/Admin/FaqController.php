<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('creator')
            ->orderBy('category')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question'  => 'required|string|max:500',
            'answer'    => 'required|string',
            'category'  => 'required|string|max:100',
            'order'     => 'nullable|integer|min:0',
        ]);

        Faq::create([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'category'   => $request->category,
            'order'      => $request->order ?? 0,
            'is_active'  => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan!');
    }

    public function edit(Faq $faq)
    {
        $categories = $this->getCategories();
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question'  => 'required|string|max:500',
            'answer'    => 'required|string',
            'category'  => 'required|string|max:100',
            'order'     => 'nullable|integer|min:0',
        ]);

        $faq->update([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'category'  => $request->category,
            'order'     => $request->order ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil diupdate!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    public function toggle(Faq $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);
        return back()->with('success', 'Status FAQ diperbarui.');
    }

    private function getCategories(): array
    {
        return [
            'umum'      => 'Umum',
            'hr'        => 'Human Resource',
            'it'        => 'Information Technology',
            'finance'   => 'Finance & Accounting',
            'operasional' => 'Operasional',
            'fasilitas' => 'Fasilitas & GA',
        ];
    }
}