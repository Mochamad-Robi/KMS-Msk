<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGrade
{
    public function handle(Request $request, Closure $next, int $maxGrade): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->grade > $maxGrade) {
            abort(403, 'Grade Anda tidak mencukupi untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}