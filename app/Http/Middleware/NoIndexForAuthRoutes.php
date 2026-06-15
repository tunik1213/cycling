<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoIndexForAuthRoutes
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $route = $request->route();
        if ($route && in_array('auth', $route->gatherMiddleware(), true)) {
            $response->headers->set('X-Robots-Tag', 'noindex', false);
        }

        return $response;
    }
}
