<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\AccessibleWebDesignPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibleWebDesignPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Page::factory()->create([
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'type' => Page::TYPE_SOLUTION_HUB,
            'is_active' => true,
            'sort_order' => 1,
            'content' => ['de' => ['hero' => ['title' => 'Websites']]],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $this->seed(AccessibleWebDesignPageSeeder::class);
    }

    public function test_seeder_creates_page_under_websites_hub(): void
    {
        $page = Page::query()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->where('slug->de', 'barrierefreies-webdesign')
            ->first();

        $this->assertNotNull($page);
        $this->assertTrue($page->is_active);
        $this->assertSame('websites', $page->parent->getTranslation('slug', 'de'));
        $this->assertSame('/loesungen/websites/barrierefreies-webdesign', $page->getUrl());
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(AccessibleWebDesignPageSeeder::class);

        $this->assertSame(1, Page::query()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->where('slug->de', 'barrierefreies-webdesign')
            ->count());
    }

    public function test_seeder_skips_gracefully_without_websites_hub(): void
    {
        Page::query()->delete();

        $this->seed(AccessibleWebDesignPageSeeder::class);

        $this->assertSame(0, Page::count());
    }

    public function test_german_page_renders_bfsg_content(): void
    {
        $response = $this->get('/loesungen/websites/barrierefreies-webdesign');

        $response->assertStatus(200);
        $response->assertSee('Barrierefreies Webdesign');
        $response->assertSee('Barrierefreiheitsstärkungsgesetz');
        $response->assertSee('WCAG 2.1');
        $response->assertSee('Barriere-Check anfragen');
    }

    public function test_german_page_has_optimized_meta_title(): void
    {
        $response = $this->get('/loesungen/websites/barrierefreies-webdesign');

        $response->assertStatus(200);
        $response->assertSee('<title>Barrierefreies Webdesign nach BFSG & WCAG 2.1', false);
    }

    public function test_english_page_is_accessible_under_english_slug(): void
    {
        $response = $this->get('/en/solutions/websites/accessible-web-design');

        $response->assertStatus(200);
        $response->assertSee('Accessible Web Design');
        $response->assertSee('European Accessibility Act');
    }

    public function test_websites_hub_lists_the_new_offer(): void
    {
        $response = $this->get('/loesungen/websites');

        $response->assertStatus(200);
        $response->assertSee('Barrierefreies Webdesign');
        $response->assertSee('/loesungen/websites/barrierefreies-webdesign', false);
    }
}
