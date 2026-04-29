<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $request->route()?->getName() ?? $request->method().' '.$request->path(),
                'description' => $this->description($request),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);
        }

        return $response;
    }

    private function description(Request $request): string
    {
        return trim($request->method().' /'.$request->path());
    }
}
