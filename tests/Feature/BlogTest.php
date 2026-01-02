<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_redirects_to_ratgeber(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber');
    }

    public function test_blog_show_redirects_to_ratgeber_with_slug(): void
    {
        $response = $this->get('/blog/test-article');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber/test-article');
    }

    public function test_english_blog_redirects_to_guides(): void
    {
        $response = $this->get('/en/blog');

        $response->assertStatus(301);
        $response->assertRedirect('/en/guides');
    }

    public function test_english_blog_show_redirects_to_guides_with_slug(): void
    {
        $response = $this->get('/en/blog/test-article');

        $response->assertStatus(301);
        $response->assertRedirect('/en/guides/test-article');
    }
}
