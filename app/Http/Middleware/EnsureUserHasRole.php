<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->user();

        if ($user->role !== $role) {
            abort(403, 'Tidak punya akses ke halaman ini.');
        }

        if ($user->status === 'nonaktif') {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            abort(403, 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}