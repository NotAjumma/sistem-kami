<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperadminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth('superadmin')->user();

        if (!$user || $user->role !== 'superadmin') {
            return redirect()->route('superadmin.login');
        }

        return $next($request);
    }
}
