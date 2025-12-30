<?php

namespace Tests\Unit;

use App\Models\BlogArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_scope_returns_only_published_articles(): void
    {
        BlogArticle::factory()->published()->count(3)->create();
        BlogArticle::factory()->create(['is_published' => false]);

        $publishedArticles = BlogArticle::published()->get();

        $this->assertCount(3, $publishedArticles);
    }

    public function test_by_category_scope_filters_by_category(): void
    {
        BlogArticle::factory()->create(['category' => 'Technologie']);
        BlogArticle::factory()->create(['category' => 'Technologie']);
        BlogArticle::factory()->create(['category' => 'Strategie']);

        $techArticles = BlogArticle::byCategory('Technologie')->get();

        $this->assertCount(2, $techArticles);
    }

    public function test_formatted_date_accessor_returns_german_format(): void
    {
        $article = BlogArticle::factory()->create([
            'published_at' => '2024-03-15 10:00:00',
        ]);

        $this->assertEquals('15. März 2024', $article->formatted_date);
    }

    public function test_read_time_text_accessor_returns_minutes(): void
    {
        $article = BlogArticle::factory()->create([
            'read_time' => 5,
        ]);

        $this->assertEquals('5 Min.', $article->read_time_text);
    }

    public function test_sections_cast_to_array(): void
    {
        $sections = [
            ['heading' => 'Section 1', 'content' => 'Content 1'],
            ['heading' => 'Section 2', 'content' => 'Content 2'],
        ];

        $article = BlogArticle::factory()->create([
            'sections' => $sections,
        ]);

        $this->assertIsArray($article->sections);
        $this->assertCount(2, $article->sections);
        $this->assertEquals('Section 1', $article->sections[0]['heading']);
    }

    public function test_is_published_cast_to_boolean(): void
    {
        $article = BlogArticle::factory()->create([
            'is_published' => 1,
        ]);

        $this->assertIsBool($article->is_published);
        $this->assertTrue($article->is_published);
    }

    public function test_slug_is_unique(): void
    {
        BlogArticle::factory()->create(['slug' => 'unique-slug']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        BlogArticle::factory()->create(['slug' => 'unique-slug']);
    }
}
