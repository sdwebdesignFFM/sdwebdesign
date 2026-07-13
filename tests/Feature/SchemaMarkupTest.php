<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaMarkupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PageSeeder::class);

        Page::create([
            'slug' => ['de' => 'suchmaschinenoptimierung', 'en' => 'search-engine-optimization'],
            'title' => ['de' => 'Suchmaschinenoptimierung', 'en' => 'Search Engine Optimization'],
            'meta_title' => ['de' => 'SEO mit technischer Substanz', 'en' => 'Technical SEO'],
            'type' => Page::TYPE_SEO,
            'is_active' => true,
            'sort_order' => 5,
            'content' => [
                'de' => [
                    'hero' => [
                        'title' => 'Suchmaschinenoptimierung mit technischer Substanz',
                        'intro' => 'SEO ist kein Marketing-Trick.',
                        'icon' => 'magnifying-glass',
                    ],
                    'problem' => [
                        'title' => 'Warum SEO oft nicht funktioniert',
                        'items' => ['Technische Altlasten'],
                    ],
                    'when_useful' => [
                        'title' => 'Wann SEO sinnvoll ist',
                        'conditions' => ['Bedingung 1', 'Bedingung 2'],
                    ],
                    'cta' => [
                        'title' => 'SEO sinnvoll aufsetzen',
                        'button_text' => 'Projekt besprechen',
                    ],
                    'card' => [
                        'subtitle' => 'Nachhaltige Sichtbarkeit',
                        'description' => 'SEO als Teil eines funktionierenden Systems.',
                        'use_cases' => ['Technisches SEO'],
                        'character' => ['Fokus auf nachhaltige Ergebnisse'],
                    ],
                ],
            ],
        ]);
    }

    public function test_solutions_page_outputs_breadcrumb_list(): void
    {
        $content = $this->get('/loesungen')->assertStatus(200)->getContent();

        $this->assertStringContainsString('"@type":"BreadcrumbList"', $content);
    }

    public function test_seo_page_outputs_breadcrumb_list(): void
    {
        $content = $this->get('/suchmaschinenoptimierung')->assertStatus(200)->getContent();

        $this->assertStringContainsString('"@type":"BreadcrumbList"', $content);
    }

    public function test_webpage_name_is_page_specific(): void
    {
        // Give the seeded solutions page an explicit German meta title so the
        // German request renders a page-specific WebPage name.
        $solutions = Page::findByType(Page::TYPE_SOLUTIONS);
        $solutions->setTranslation('meta_title', 'de', 'Digitale Lösungen Test');
        $solutions->save();

        $solutionsName = 'Digitale Lösungen Test';
        $seoName = 'SEO mit technischer Substanz';

        $this->assertNotSame($solutionsName, $seoName);

        $solutionsContent = $this->get('/loesungen')->getContent();
        $seoContent = $this->get('/suchmaschinenoptimierung')->getContent();

        $encode = fn (string $value): string => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->assertStringContainsString('"@type":"WebPage","name":'.$encode($solutionsName), $solutionsContent);
        $this->assertStringContainsString('"@type":"WebPage","name":'.$encode($seoName), $seoContent);

        $this->assertStringNotContainsString($encode($seoName), $solutionsContent);
    }
}
