<?php

namespace App\Console\Commands;

use App\Models\BlogArticle;
use App\Models\Page;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap for SEO';

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Add static pages for each locale
        foreach (['de', 'en'] as $locale) {
            $this->addStaticPages($sitemap, $locale);
            $this->addDynamicPages($sitemap, $locale);
            $this->addBlogArticles($sitemap, $locale);
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at public/sitemap.xml');

        return self::SUCCESS;
    }

    private function addStaticPages(Sitemap $sitemap, string $locale): void
    {
        $baseUrl = config('app.url');
        $prefix = $locale === 'de' ? '' : '/'.$locale;

        // Homepage - highest priority
        $sitemap->add(
            Url::create($baseUrl.$prefix)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(1.0)
        );

        // Main pages
        $mainPages = [
            'loesungen' => ['en' => 'solutions', 'priority' => 0.9],
            'referenzen' => ['en' => 'references', 'priority' => 0.8],
            'ueber-uns' => ['en' => 'about', 'priority' => 0.7],
            'kontakt' => ['en' => 'contact', 'priority' => 0.8],
            'ratgeber' => ['en' => 'guides', 'priority' => 0.7],
            'impressum' => ['en' => 'imprint', 'priority' => 0.3],
            'datenschutz' => ['en' => 'privacy', 'priority' => 0.3],
        ];

        foreach ($mainPages as $dePath => $config) {
            $path = $locale === 'de' ? $dePath : $config['en'];
            $sitemap->add(
                Url::create($baseUrl.$prefix.'/'.$path)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority($config['priority'])
            );
        }
    }

    private function addDynamicPages(Sitemap $sitemap, string $locale): void
    {
        $baseUrl = config('app.url');

        // Temporarily set locale for URL generation
        $originalLocale = app()->getLocale();
        app()->setLocale($locale);

        // Get all active pages
        $pages = Page::where('is_active', true)
            ->whereNotNull('slug')
            ->get();

        foreach ($pages as $page) {
            // Skip pages that are handled as static pages
            $skipTypes = ['home', 'solutions', 'references', 'about', 'contact', 'imprint', 'privacy', 'guide-overview'];
            if (in_array($page->type, $skipTypes)) {
                continue;
            }

            $url = $page->getUrl();
            if (! $url || $url === '/') {
                continue;
            }

            $priority = match ($page->type) {
                'solution-hub' => 0.8,
                'solution-detail' => 0.7,
                'reference-detail' => 0.6,
                'guide' => 0.6,
                'seo', 'sea' => 0.7,
                'maintenance' => 0.6,
                'local-hub' => 0.5,
                'local' => 0.4,
                default => 0.5,
            };

            $sitemap->add(
                Url::create($baseUrl.$url)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority($priority)
                    ->setLastModificationDate($page->updated_at)
            );
        }

        // Restore original locale
        app()->setLocale($originalLocale);
    }

    private function addBlogArticles(Sitemap $sitemap, string $locale): void
    {
        $baseUrl = config('app.url');
        $prefix = $locale === 'de' ? '' : '/'.$locale;
        $blogPath = $locale === 'de' ? 'ratgeber' : 'guides';

        $articles = BlogArticle::published()->get();

        foreach ($articles as $article) {
            $slug = $article->getTranslation('slug', $locale, false);
            if (! $slug) {
                continue;
            }

            $sitemap->add(
                Url::create($baseUrl.$prefix.'/'.$blogPath.'/'.$slug)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6)
                    ->setLastModificationDate($article->updated_at)
            );
        }
    }
}
