<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (isset($request->api_password)) {
            if ($request->api_password !== env('API_PASSWORD','123123123')) {
                return response()->json(['message' => 'Unauthenticated.']);
            }
        }
        return $next($request);
    }
}
