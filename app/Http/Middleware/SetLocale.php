<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $locale = null): Response
    {
        // Set locale from route parameter or default to 'de'
        $locale = $locale ?? config('app.locale', 'de');

        if (in_array($locale, config('app.available_locales', ['de', 'en']))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
