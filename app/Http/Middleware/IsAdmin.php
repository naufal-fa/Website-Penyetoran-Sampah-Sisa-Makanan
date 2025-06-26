<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login DAN rolenya adalah 'admin'
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }
        // Jika tidak, redirect ke dashboard nasabah atau halaman lain
        return redirect('/nasabah/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini!');
    }
}