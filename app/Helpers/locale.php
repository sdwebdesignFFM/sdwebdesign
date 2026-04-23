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
     *
     * When $strict is true, returns null if no equivalent route exists in
     * the target locale — use this for hreflang tags, which should never
     * point at a non-equivalent fallback (Google flags those as unconfirmed
     * pairs in Search Console). For UX surfaces like a language switcher,
     * leave $strict off so the user still has somewhere to go.
     */
    function alternate_locale_url(string $locale, bool $strict = false): ?string
    {
        $fallback = $locale === 'de' ? url('/') : url('/en');
        $currentRouteName = request()->route()?->getName();

        if (! $currentRouteName) {
            return $strict ? null : $fallback;
        }

        $baseName = preg_replace('/^(de|en)\./', '', $currentRouteName);
        $parameters = request()->route()?->parameters() ?? [];
        $newRouteName = $locale.'.'.$baseName;

        if (! \Illuminate\Support\Facades\Route::has($newRouteName)) {
            return $strict ? null : $fallback;
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
