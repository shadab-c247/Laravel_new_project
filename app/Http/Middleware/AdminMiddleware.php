<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        $user = auth()->user();

        // Check if user has userRole
        if (!$user || !$user->userRole) {
            return redirect('/');
        }

        // Check if user is admin
        if (!$user->isAdmin()) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
