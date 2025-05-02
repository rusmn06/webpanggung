<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if ($role == 'admin' && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if ($role == 'user' && Auth::user()->role !== 'user') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}