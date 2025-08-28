<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CacheControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only apply caching headers to successful GET responses
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $response->header('Cache-Control', 'public, max-age=3600');
            $response->header('Expires', gmdate('D, d M Y H:i:s T', time() + 3600));
        }

        return $response;
    }
}