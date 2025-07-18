<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = env('FHIR_API_KEY');
        $headerKey = $request->header('Authorization');
        // Accept 'Bearer {key}' or just the key
        $providedKey = $headerKey ? preg_replace('/^Bearer\s+/i', '', $headerKey) : null;
        if (!$providedKey || $providedKey !== $apiKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
} 