<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Route as RouteObject;
use Illuminate\Support\Facades\Route;

class RobotsController extends Controller
{
    private const EXTRA_DISALLOWS = [
        '/sights/*/getMapPopupView',
        '/activities$',
    ];

    public function show()
    {
        $disallows = collect(self::EXTRA_DISALLOWS);

        foreach (Route::getRoutes() as $route) {
            if (!$this->isGetRoute($route)) {
                continue;
            }

            if (!in_array('auth', $route->gatherMiddleware(), true)) {
                continue;
            }

            $disallows->push($this->uriToDisallowPattern($route->uri()));
        }

        $lines = ['User-agent: *'];
        foreach ($disallows->unique()->sort()->values() as $path) {
            $lines[] = 'Disallow: ' . $path;
        }

        return response(implode("\n", $lines) . "\n", 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    private function isGetRoute(RouteObject $route): bool
    {
        return in_array('GET', $route->methods(), true);
    }

    private function uriToDisallowPattern(string $uri): string
    {
        $hadTrailingOptional = false;
        if (preg_match('/^(.*)\/\{[^}]+\?\}$/', $uri, $matches)) {
            $uri = $matches[1];
            $hadTrailingOptional = true;
        }

        $pattern = preg_replace('/\{[^}]+\?\}/', '*', $uri);
        $pattern = preg_replace('/\{[^}]+\}/', '*', $pattern);
        $pattern = '/' . ltrim($pattern, '/');

        if (!$hadTrailingOptional && !str_contains($pattern, '*')) {
            $pattern .= '$';
        }

        return $pattern;
    }
}
