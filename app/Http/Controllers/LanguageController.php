<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, ['id', 'en'])) {
            abort(400);
        }

        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        } else {
            session(['locale' => $locale]);
        }

        return back();
    }
}