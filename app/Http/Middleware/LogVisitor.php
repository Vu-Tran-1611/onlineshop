<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
class LogVisitor
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, \Closure $next)
    {
        // Log visitor information
        $visitorData = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'time' => now(),
        ];
        Log::info('Visitor Information:', $visitorData);
        return $next($request);
    }
}
