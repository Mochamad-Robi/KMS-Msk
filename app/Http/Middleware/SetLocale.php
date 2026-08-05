<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            App::setLocale(Auth::user()->locale ?? 'id');
        } else {
            App::setLocale(session('locale', 'id'));
        }

        return $next($request);
    }
}