<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (auth()->user()->role !== $role) {
            abort(403, 'Tidak punya akses ke halaman ini.');
        }
        return $next($request);
    }
}