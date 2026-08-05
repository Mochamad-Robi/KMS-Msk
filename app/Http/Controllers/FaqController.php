<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $categories = [
            'umum'        => 'Umum',
            'hr'          => 'Human Resource',
            'it'          => 'Information Technology',
            'finance'     => 'Finance & Accounting',
            'operasional' => 'Operasional',
            'fasilitas'   => 'Fasilitas & GA',
        ];

        $activeCategory = $request->get('category', 'all');
        $search         = $request->get('q');

        $faqs = Faq::active()
            ->when($activeCategory !== 'all', fn($q) => $q->where('category', $activeCategory))
            ->when($search, fn($q) => $q
                ->where('question', 'like', "%{$search}%")
                ->orWhere('answer', 'like', "%{$search}%")
            )
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category');

        return view('faq.index', compact('faqs', 'categories', 'activeCategory', 'search'));
    }
}