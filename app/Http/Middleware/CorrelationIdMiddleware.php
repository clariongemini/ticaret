<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CorrelationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $correlationId = $request->header('X-Correlation-ID', (string) Str::ulid());
        
        $request->headers->set('X-Correlation-ID', $correlationId);
        
        // Push to logs or global context if needed
        // Log::withContext(['correlation_id' => $correlationId]);

        $response = $next($request);
        
        $response->headers->set('X-Correlation-ID', $correlationId);

        return $response;
    }
}
