<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Overrides Laravel's default Cache-Control: no-store on public pages so
 * a CDN or Googlebot can cache them. Without this, every request is fresh
 * which hurts TTFB and Google Ads Quality Score on ad landing pages.
 */
class SetPublicCacheHeaders
{
    /**
     * Path prefixes that should keep Laravel's default no-cache behaviour
     * (admin panels, authenticated areas, quote tokens, forms).
     *
     * @var list<string>
     */
    private const EXCLUDED_PREFIXES = [
        '/admin',
        '/dashboard',
        '/profile',
        '/kontakt',
        '/contact',
        '/angebot',
        '/livewire',
        '/login',
        '/logout',
        '/register',
        '/password',
        '/email',
        '/confirm-password',
        '/forgot-password',
        '/reset-password',
        '/verify-email',
        '/up',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $response;
        }

        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        if (Auth::check()) {
            return $response;
        }

        $path = $request->getPathInfo();
        foreach (self::EXCLUDED_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return $response;
            }
        }

        // 5 min browser cache, 15 min shared (CDN) cache, 1 day stale-while-revalidate.
        // Short enough that editorial updates propagate quickly, long enough to
        // offload most traffic from the origin.
        $response->headers->set(
            'Cache-Control',
            'public, max-age=300, s-maxage=900, stale-while-revalidate=86400'
        );

        return $response;
    }
}
