<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->userRole) {
            return redirect('/');
        }

        if (!$user->isAdmin()) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
