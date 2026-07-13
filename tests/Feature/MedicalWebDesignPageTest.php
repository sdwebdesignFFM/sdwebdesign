<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\MedicalWebDesignPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalWebDesignPageTest extends TestCase
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

        $this->seed(MedicalWebDesignPageSeeder::class);
    }

    public function test_seeder_creates_page_under_websites_hub(): void
    {
        $page = Page::query()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->where('slug->de', 'webdesign-fuer-aerzte')
            ->first();

        $this->assertNotNull($page);
        $this->assertTrue($page->is_active);
        $this->assertSame('websites', $page->parent->getTranslation('slug', 'de'));
        $this->assertSame('/loesungen/websites/webdesign-fuer-aerzte', $page->getUrl());
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(MedicalWebDesignPageSeeder::class);

        $this->assertSame(1, Page::query()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->where('slug->de', 'webdesign-fuer-aerzte')
            ->count());
    }

    public function test_seeder_skips_gracefully_without_websites_hub(): void
    {
        Page::query()->delete();

        $this->seed(MedicalWebDesignPageSeeder::class);

        $this->assertSame(0, Page::count());
    }

    public function test_german_page_renders_practice_content(): void
    {
        $response = $this->get('/loesungen/websites/webdesign-fuer-aerzte');

        $response->assertStatus(200);
        $response->assertSee('Webdesign für Ärzte');
        $response->assertSee('Praxis-Homepage');
        $response->assertSee('Online-Terminbuchung');
        $response->assertSee('Kostenloses Praxis-Gespräch anfragen');
    }

    public function test_english_page_is_accessible_under_english_slug(): void
    {
        $response = $this->get('/en/solutions/websites/web-design-for-medical-practices');

        $response->assertStatus(200);
        $response->assertSee('Web Design for Medical');
    }

    public function test_websites_hub_lists_the_new_offer(): void
    {
        $response = $this->get('/loesungen/websites');

        $response->assertStatus(200);
        $response->assertSee('Webdesign für Ärzte');
        $response->assertSee('/loesungen/websites/webdesign-fuer-aerzte', false);
    }
}
