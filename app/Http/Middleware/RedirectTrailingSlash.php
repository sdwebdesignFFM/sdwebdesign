<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectTrailingSlash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = $request->getPathInfo();

        if ($path === '/' || ! str_ends_with($path, '/')) {
            return $next($request);
        }

        $normalizedPath = rtrim($path, '/');
        $query = $request->getQueryString();
        $target = $request->getSchemeAndHttpHost().$normalizedPath.($query ? '?'.$query : '');

        return redirect($target, 301);
    }
}
