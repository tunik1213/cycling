<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localhost
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
        if (env('APP_DEBUG')) return $next($request);

        if (
            $request->server('SERVER_ADDR') != $request->server('REMOTE_ADDR')
            && $request->server('REMOTE_ADDR') != '127.0.0.1'
        ) {
            abort(403);
        }

        return $next($request);
    }
}
