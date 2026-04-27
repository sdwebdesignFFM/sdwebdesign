<?php

namespace Tests\Feature;

use App\Models\BlogArticle;
use Database\Seeders\B2BPlatformRatgeberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2BPlatformRatgeberSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_both_articles(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);

        $this->assertTrue(BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->exists());
        $this->assertTrue(BlogArticle::where('slug->de', 'software-agentur-frankfurt-mittelstand')->exists());
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);
        $this->seed(B2BPlatformRatgeberSeeder::class);

        $this->assertSame(1, BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->count());
        $this->assertSame(1, BlogArticle::where('slug->de', 'software-agentur-frankfurt-mittelstand')->count());
    }

    public function test_seeder_republishes_existing_unpublished_cost_article(): void
    {
        app()->setLocale('de');

        // Simulate the live state: a stub record exists but is_published
        // is false (the failed migration created it but didn't publish it).
        BlogArticle::create([
            'slug' => 'was-kostet-b2b-plattform',
            'category' => 'X',
            'title' => 'Stub',
            'excerpt' => 'x',
            'intro' => 'x',
            'sections' => [],
            'conclusion' => 'x',
            'is_published' => false,
            'published_at' => null,
        ]);

        $this->seed(B2BPlatformRatgeberSeeder::class);

        $article = BlogArticle::firstWhere('slug->de', 'was-kostet-b2b-plattform');
        $this->assertNotNull($article);
        $this->assertTrue($article->is_published);
        $this->assertNotNull($article->published_at);
        $this->assertTrue($article->published_at->isPast());
    }

    public function test_seeder_publishes_articles_so_sitemap_picks_them_up(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);

        foreach (['was-kostet-b2b-plattform', 'software-agentur-frankfurt-mittelstand'] as $slug) {
            $article = BlogArticle::firstWhere('slug->de', $slug);
            $this->assertTrue($article->is_published);
            $this->assertNotNull($article->published_at);
            $this->assertTrue($article->published_at->isPast());
        }
    }
}
