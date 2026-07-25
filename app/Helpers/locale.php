<?php

use App\Models\Page;
use Illuminate\Support\Facades\Route;

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

        if (! Route::has($newRouteName)) {
            return $strict ? null : $fallback;
        }

        // Page-detail routes carry a locale-specific slug/path parameter.
        // Reusing the current locale's slug against the target locale's route
        // yields the right path prefix with the wrong-language slug (e.g.
        // /en/solutions/plattformen/interne-tools), which 3XX-redirects or
        // 404s and breaks hreflang. Resolve the page and use its slug in the
        // target locale instead.
        $pageDetailRoutes = ['solutions.show', 'solutions.hierarchy', 'guide.show', 'references.show'];

        if (in_array($baseName, $pageDetailRoutes, true) && $parameters !== []) {
            $slugPath = (string) reset($parameters);
            $page = Page::findByHierarchicalSlug($slugPath);

            // No real equivalent in the target locale -> no hreflang pair.
            if (! $page || ($page->getTranslation('slug', $locale, false) ?: '') === '') {
                return $strict ? null : $fallback;
            }

            return url($page->getUrlForLocale($locale));
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
