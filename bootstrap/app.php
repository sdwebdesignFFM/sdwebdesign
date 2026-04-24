<?php

use App\Http\Middleware\HandleRedirects;
use App\Http\Middleware\RedirectTrailingSlash;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetPublicCacheHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'locale' => SetLocale::class,
        ]);

        // Redirect middleware must run globally — if we register only on the
        // `web` group, unmatched URLs (e.g. legacy /services/... pages) would
        // 404 before the redirect table is consulted.
        $middleware->prepend([
            RedirectTrailingSlash::class,
            HandleRedirects::class,
        ]);

        // Override Laravel's default no-store cache headers on public pages.
        $middleware->web(append: [
            SetPublicCacheHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
