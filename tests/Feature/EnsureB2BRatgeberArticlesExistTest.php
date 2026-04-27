<?php

namespace Tests\Feature;

use App\Models\BlogArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureB2BRatgeberArticlesExistTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_27_083524_ensure_b2b_ratgeber_articles_exist.php');
        $migration->up();
    }

    public function test_migration_creates_both_articles_when_missing(): void
    {
        $this->runMigration();

        $this->assertTrue(BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->exists());
        $this->assertTrue(BlogArticle::where('slug->de', 'software-agentur-frankfurt-mittelstand')->exists());
    }

    public function test_migration_does_not_duplicate_when_already_present(): void
    {
        $this->runMigration();
        $this->runMigration();

        $this->assertSame(1, BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->count());
        $this->assertSame(1, BlogArticle::where('slug->de', 'software-agentur-frankfurt-mittelstand')->count());
    }

    public function test_cost_article_carries_b2b_keyword_signals(): void
    {
        $this->runMigration();

        $article = BlogArticle::firstWhere('slug->de', 'was-kostet-b2b-plattform');

        $this->assertStringContainsString('B2B-Plattform', $article->title);
        $this->assertStringContainsString('Mittelständler', $article->title);
        $this->assertStringContainsString('Discovery', $article->conclusion);
    }

    public function test_frankfurt_article_carries_local_seo_signals(): void
    {
        $this->runMigration();

        $article = BlogArticle::firstWhere('slug->de', 'software-agentur-frankfurt-mittelstand');

        $this->assertStringContainsString('Frankfurt', $article->title);
        $sectionsText = collect($article->sections)->pluck('content')->implode(' ');
        $this->assertStringContainsString('Bad Homburg', $sectionsText);
        $this->assertStringContainsString('IHK Frankfurt', $sectionsText);
    }

    public function test_both_articles_are_published(): void
    {
        $this->runMigration();

        foreach (['was-kostet-b2b-plattform', 'software-agentur-frankfurt-mittelstand'] as $slug) {
            $article = BlogArticle::firstWhere('slug->de', $slug);
            $this->assertTrue($article->is_published);
            $this->assertNotNull($article->published_at);
        }
    }
}
