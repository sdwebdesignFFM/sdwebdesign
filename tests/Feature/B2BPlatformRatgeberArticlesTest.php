<?php

namespace Tests\Feature;

use App\Models\BlogArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2BPlatformRatgeberArticlesTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_26_193432_add_b2b_platform_ratgeber_articles.php');
        $migration->up();
    }

    public function test_migration_creates_two_published_articles(): void
    {
        $this->runMigration();

        $this->assertSame(2, BlogArticle::published()->count());
        $this->assertNotNull(BlogArticle::firstWhere('slug->de', 'was-kostet-b2b-plattform'));
        $this->assertNotNull(BlogArticle::firstWhere('slug->de', 'software-agentur-frankfurt-mittelstand'));
    }

    public function test_cost_article_anchors_b2b_platform_keyword_cluster(): void
    {
        $this->runMigration();

        $article = BlogArticle::firstWhere('slug->de', 'was-kostet-b2b-plattform');

        $this->assertStringContainsString('B2B-Plattform', $article->title);
        $this->assertStringContainsString('Mittelständler', $article->title);
        $this->assertStringContainsString('Kosten', $article->meta_title);
        $this->assertStringContainsString('Mittelständler', $article->meta_description);

        $sectionTitles = collect($article->sections)->pluck('heading')->all();
        $this->assertContains('Die fünf wichtigsten Preistreiber', $sectionTitles);
        $this->assertContains('Pilot-Strategie: Warum 4–6 Wochen am Anfang besser sind als 12 Monate Komplett-Projekt', $sectionTitles);
    }

    public function test_frankfurt_article_carries_local_seo_signals(): void
    {
        $this->runMigration();

        $article = BlogArticle::firstWhere('slug->de', 'software-agentur-frankfurt-mittelstand');

        // Local terms in title + meta
        $this->assertStringContainsString('Frankfurt', $article->title);
        $this->assertStringContainsString('Frankfurt', $article->meta_title);
        $this->assertStringContainsString('Mittelständler', $article->meta_description);

        // Body covers regional locations + local-context themes
        $sectionsText = collect($article->sections)->pluck('content')->implode(' ');
        $this->assertStringContainsString('Bad Homburg', $sectionsText);
        $this->assertStringContainsString('Rhein-Main', $sectionsText);
        $this->assertStringContainsString('IHK Frankfurt', $sectionsText);
        $this->assertStringContainsString('Bankenviertel', $sectionsText);
    }

    public function test_both_articles_anchor_discovery_workshop_funnel(): void
    {
        $this->runMigration();

        foreach (['was-kostet-b2b-plattform', 'software-agentur-frankfurt-mittelstand'] as $slug) {
            $article = BlogArticle::firstWhere('slug->de', $slug);
            $this->assertStringContainsString('Discovery', $article->getTranslation('conclusion', 'de'));
            $this->assertStringContainsString('990', $article->getTranslation('conclusion', 'de'));
        }
    }

    public function test_migration_is_idempotent(): void
    {
        $this->runMigration();
        $this->runMigration();

        $this->assertSame(1, BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->count());
        $this->assertSame(1, BlogArticle::where('slug->de', 'software-agentur-frankfurt-mittelstand')->count());
    }
}
