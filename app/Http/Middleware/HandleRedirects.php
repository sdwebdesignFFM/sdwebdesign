<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/'.ltrim($request->path(), '/');

        // Check cache first for performance
        $redirect = Cache::remember(
            'redirect:'.md5($path),
            now()->addHour(),
            fn () => Redirect::findByPath($path)
        );

        if ($redirect) {
            // Record the hit asynchronously
            $redirect->recordHit();

            // Clear cache for this redirect (hit count changed)
            Cache::forget('redirect:'.md5($path));

            return redirect($redirect->to_url, $redirect->status_code);
        }

        return $next($request);
    }
}
