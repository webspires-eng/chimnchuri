<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     * Allows all origins by reflecting the Origin header back.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->header('Origin');

        if ($origin) {

            // Handle preflight OPTIONS request immediately
            if ($request->isMethod('OPTIONS')) {
                return response('', 204)
                    ->header('Access-Control-Allow-Origin', $origin)
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN')
                    ->header('Access-Control-Allow-Credentials', 'true')
                    ->header('Access-Control-Max-Age', '86400');
            }

            // For normal requests, add CORS headers to the response
            $response = $next($request);

            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            return $response;
        }

        return $next($request);
    }
}
