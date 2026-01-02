<?php

if (! function_exists('localized_route')) {
    /**
     * Generate a localized route URL.
     *
     * @param  array<string, mixed>  $parameters
     */
    function localized_route(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $routeName = $locale.'.'.$name;

        return route($routeName, $parameters);
    }
}

if (! function_exists('alternate_locale_url')) {
    /**
     * Get the URL for the current page in an alternate locale.
     */
    function alternate_locale_url(string $locale): string
    {
        $currentLocale = app()->getLocale();
        $currentRouteName = request()->route()?->getName();

        if (! $currentRouteName) {
            return $locale === 'de' ? url('/') : url('/en');
        }

        // Extract the base route name (without locale prefix)
        $baseName = preg_replace('/^(de|en)\./', '', $currentRouteName);

        // Get route parameters
        $parameters = request()->route()?->parameters() ?? [];

        // Build the new route name
        $newRouteName = $locale.'.'.$baseName;

        // Check if the route exists
        if (! \Illuminate\Support\Facades\Route::has($newRouteName)) {
            return $locale === 'de' ? url('/') : url('/en');
        }

        return route($newRouteName, $parameters);
    }
}

if (! function_exists('current_locale')) {
    /**
     * Get the current locale.
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (! function_exists('available_locales')) {
    /**
     * Get available locales.
     *
     * @return array<string>
     */
    function available_locales(): array
    {
        return config('app.available_locales', ['de', 'en']);
    }
}
