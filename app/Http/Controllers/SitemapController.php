<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Build a deterministic sitemap from the DB.
     *
     * Forces https on the canonical host (APP_URL may be misconfigured on some
     * environments), emits URLs without trailing slashes, and includes both
     * locales for pages that have EN equivalents.
     */
    public function __invoke(): Response
    {
        $baseUrl = $this->baseUrl();
        $sitemap = Sitemap::create();

        $originalLocale = app()->getLocale();

        try {
            foreach (Page::active()->get() as $page) {
                $this->addPageUrls($sitemap, $page, $baseUrl);
            }

            // Static lead-magnet pages (whitepapers etc.) — registered
            // via routes/web.php, not as Page records.
            $sitemap->add(
                Url::create($baseUrl.'/whitepaper/eigene-plattform-vs-standard-software')
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.8)
            );

            // NOTE: Legacy BlogArticle records are intentionally NOT emitted.
            // Guides are now Page records (TYPE_GUIDE, added above) and the
            // /ratgeber/{slug} route resolves against Page, so any leftover
            // published BlogArticle whose slug has no matching guide page would
            // 404 — polluting the sitemap with dead URLs.
        } finally {
            app()->setLocale($originalLocale);
        }

        return response(
            $sitemap->render(),
            200,
            ['Content-Type' => 'application/xml; charset=UTF-8']
        );
    }

    private function addPageUrls(Sitemap $sitemap, Page $page, string $baseUrl): void
    {
        app()->setLocale('de');
        $deUrl = $page->getUrl();
        $sitemap->add(
            Url::create($baseUrl.$deUrl)
                ->setChangeFrequency($this->changeFrequencyFor($page))
                ->setPriority($this->priorityFor($page))
        );

        // English routes do not exist for local landing pages.
        if (in_array($page->type, [Page::TYPE_LOCAL, Page::TYPE_LOCAL_HUB], true)) {
            return;
        }

        app()->setLocale('en');
        $enUrl = $page->getUrl();

        if ($enUrl !== $deUrl) {
            $sitemap->add(
                Url::create($baseUrl.$enUrl)
                    ->setChangeFrequency($this->changeFrequencyFor($page))
                    ->setPriority(max(0.1, $this->priorityFor($page) - 0.1))
            );
        }
    }

    private function changeFrequencyFor(Page $page): string
    {
        return match ($page->type) {
            Page::TYPE_HOME => Url::CHANGE_FREQUENCY_WEEKLY,
            default => Url::CHANGE_FREQUENCY_MONTHLY,
        };
    }

    private function priorityFor(Page $page): float
    {
        return match ($page->type) {
            Page::TYPE_HOME => 1.0,
            Page::TYPE_SOLUTIONS, Page::TYPE_SOLUTION_HUB => 0.9,
            Page::TYPE_LOCAL, Page::TYPE_SOLUTION_DETAIL, Page::TYPE_REFERENCES, Page::TYPE_SEO, Page::TYPE_SEA => 0.8,
            Page::TYPE_REFERENCE_DETAIL, Page::TYPE_LOCAL_HUB, Page::TYPE_CONTACT => 0.7,
            Page::TYPE_ABOUT, Page::TYPE_GUIDE_OVERVIEW, Page::TYPE_MAINTENANCE => 0.7,
            Page::TYPE_GUIDE => 0.6,
            Page::TYPE_IMPRINT, Page::TYPE_PRIVACY, Page::TYPE_ACCESSIBILITY => 0.3,
            default => 0.5,
        };
    }

    private function baseUrl(): string
    {
        $url = rtrim(config('app.url'), '/');

        // Keep dev environments as-is (Herd .test hostnames, localhost).
        if (str_contains($url, '.test') || str_contains($url, 'localhost')) {
            return $url;
        }

        // Production: never emit http://. Force https.
        if (! str_starts_with($url, 'https://')) {
            $url = 'https://'.preg_replace('#^https?://#', '', $url);
        }

        return $url;
    }
}
