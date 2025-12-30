<?php

namespace Tests\Feature;

use App\Models\BlogArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_returns_success(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Wissen');
    }

    public function test_blog_index_shows_published_articles(): void
    {
        $article = BlogArticle::factory()->published()->create([
            'title' => 'Test Published Article',
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Test Published Article');
    }

    public function test_blog_index_hides_unpublished_articles(): void
    {
        $article = BlogArticle::factory()->create([
            'title' => 'Test Unpublished Article',
            'is_published' => false,
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertDontSee('Test Unpublished Article');
    }

    public function test_blog_index_filters_by_category(): void
    {
        $techArticle = BlogArticle::factory()->published()->create([
            'title' => 'Tech Article',
            'category' => 'Technologie',
        ]);
        $strategyArticle = BlogArticle::factory()->published()->create([
            'title' => 'Strategy Article',
            'category' => 'Strategie',
        ]);

        $response = $this->get('/blog?category=Technologie');

        $response->assertStatus(200);
        $response->assertSee('Tech Article');
        $response->assertDontSee('Strategy Article');
    }

    public function test_blog_index_searches_articles(): void
    {
        $article1 = BlogArticle::factory()->published()->create([
            'title' => 'Laravel Best Practices',
        ]);
        $article2 = BlogArticle::factory()->published()->create([
            'title' => 'React Tutorial',
        ]);

        $response = $this->get('/blog?search=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Laravel Best Practices');
        $response->assertDontSee('React Tutorial');
    }

    public function test_blog_show_returns_success_for_published_article(): void
    {
        $article = BlogArticle::factory()->published()->create([
            'title' => 'Test Article',
            'slug' => 'test-article',
        ]);

        $response = $this->get('/blog/test-article');

        $response->assertStatus(200);
        $response->assertSee('Test Article');
    }

    public function test_blog_show_returns_404_for_unpublished_article(): void
    {
        $article = BlogArticle::factory()->create([
            'slug' => 'unpublished-article',
            'is_published' => false,
        ]);

        $response = $this->get('/blog/unpublished-article');

        $response->assertStatus(404);
    }

    public function test_blog_show_returns_404_for_non_existent_article(): void
    {
        $response = $this->get('/blog/non-existent-article');

        $response->assertStatus(404);
    }

    public function test_blog_show_displays_related_articles(): void
    {
        $mainArticle = BlogArticle::factory()->published()->create([
            'title' => 'Main Article',
            'slug' => 'main-article',
            'category' => 'Technologie',
        ]);
        $relatedArticle = BlogArticle::factory()->published()->create([
            'title' => 'Related Article',
            'category' => 'Technologie',
        ]);

        $response = $this->get('/blog/main-article');

        $response->assertStatus(200);
        $response->assertSee('Related Article');
    }
}
