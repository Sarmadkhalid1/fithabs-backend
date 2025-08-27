<?php

namespace App\Http\Middleware;

use Closure;
use Fruitcake\Cors\HandleCors as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle CORS (Cross-Origin Resource Sharing) for API requests.
 *
 * Extends the Fruitcake\LaravelCors HandleCors middleware with custom logic
 * for the health and fitness app's API. Configuration is managed in config/cors.php.
 */
class HandleCors extends Middleware
{
    /**
     * Handle an incoming request with custom CORS logic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow parent class to handle standard CORS logic
        $response = parent::handle($request, $next);

        // Add custom headers if needed (example)
        $response->header('X-API-Version', '1.0');

        // Example: Restrict origins dynamically (optional)
        $allowedOrigins = config('cors.allowed_origins');
        $origin = $request->header('Origin');
        if (!in_array($origin, $allowedOrigins) && $allowedOrigins !== ['*']) {
            return response()->json(['error' => 'Unauthorized origin'], 403);
        }

        return $response;
    }
}