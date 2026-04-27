<?php

namespace Tests\Feature;

use App\Models\BlogArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RepairB2BCostArticleSlugTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_27_081509_repair_b2b_cost_article_slug.php');
        $migration->up();
    }

    public function test_migration_deletes_truncated_was_kostet_b_records(): void
    {
        // Simulate the live corruption: an article with the truncated slug.
        BlogArticle::create([
            'slug' => 'was-kostet-b',
            'title' => 'Truncated',
            'category' => 'X',
            'excerpt' => 'x',
            'intro' => 'x',
            'sections' => [],
            'conclusion' => 'x',
        ]);
        BlogArticle::create([
            'slug' => 'b',
            'title' => 'Phantom',
            'category' => 'X',
            'excerpt' => 'x',
            'intro' => 'x',
            'sections' => [],
            'conclusion' => 'x',
        ]);
        // Bypass model-level safety by setting created_at after insertion
        DB::table('blog_articles')->update(['created_at' => '2026-04-26 12:00:00']);

        $this->runMigration();

        $this->assertNull(BlogArticle::where('slug->de', 'was-kostet-b')->first());
        $this->assertNull(BlogArticle::where('slug->de', 'b')->first());
    }

    public function test_migration_creates_correct_article_when_missing(): void
    {
        $this->runMigration();

        $article = BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->first();
        $this->assertNotNull($article);
        $this->assertStringContainsString('B2B-Plattform', $article->title);
        $this->assertStringContainsString('Mittelständler', $article->title);
        $this->assertTrue($article->is_published);
    }

    public function test_migration_does_not_duplicate_when_run_twice(): void
    {
        // First run creates the article; second run must find it via
        // the slug->de lookup and skip the create call.
        $this->runMigration();
        $this->runMigration();

        $this->assertSame(
            1,
            BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->count()
        );
    }

    public function test_migration_is_idempotent(): void
    {
        $this->runMigration();
        $this->runMigration();

        $this->assertSame(
            1,
            BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->count()
        );
    }
}
