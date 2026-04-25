<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\GruenderpaketContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GruenderpaketContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_pillar_with_correct_type_and_slug(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);

        $pillar = Page::where('slug->de', 'gruenderpaket-frankfurt')->first();

        $this->assertNotNull($pillar);
        $this->assertSame(Page::TYPE_SOLUTION_HUB, $pillar->type);
        $this->assertNull($pillar->parent_id);
        $this->assertTrue($pillar->is_active);
    }

    public function test_seeder_creates_all_four_solution_detail_spokes_under_pillar(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);

        $pillar = Page::where('slug->de', 'gruenderpaket-frankfurt')->first();
        $spokeSlugs = [
            'website-fuer-existenzgruender',
            'logo-corporate-identity-gruender',
            'digitale-geschaeftsausstattung',
            'social-media-setup-gruender',
        ];

        foreach ($spokeSlugs as $slug) {
            $spoke = Page::where('slug->de', $slug)->first();
            $this->assertNotNull($spoke, "Missing spoke {$slug}");
            $this->assertSame(Page::TYPE_SOLUTION_DETAIL, $spoke->type, "{$slug} has wrong type");
            $this->assertSame($pillar->id, $spoke->parent_id, "{$slug} not parented to pillar");
            $this->assertTrue($spoke->is_active);
        }
    }

    public function test_seeder_creates_three_guide_articles(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);

        $guideSlugs = [
            'geschaeftsausstattung-gruendung-checkliste',
            'website-kosten-existenzgruender',
            'impressum-pflicht-selbststaendige',
        ];

        foreach ($guideSlugs as $slug) {
            $guide = Page::where('slug->de', $slug)->first();
            $this->assertNotNull($guide, "Missing guide {$slug}");
            $this->assertSame(Page::TYPE_GUIDE, $guide->type, "{$slug} has wrong type");
            $this->assertTrue($guide->is_active);
        }
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);
        $countAfterFirst = Page::count();

        $this->seed(GruenderpaketContentSeeder::class);
        $countAfterSecond = Page::count();

        $this->assertSame($countAfterFirst, $countAfterSecond);
    }

    public function test_pillar_page_renders_with_pricing_timeline_and_package_blocks(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);

        $response = $this->get('/loesungen/gruenderpaket-frankfurt');

        $response->assertStatus(200);
        $response->assertSee('Gründerpaket Frankfurt');
        $response->assertSee('Gründerpaket ab 4.500 €');
        $response->assertSee('4–6 Wochen bis Launch');
        $response->assertSee('Was Sie im Gründerpaket bekommen');
        $response->assertSee('Individuelle Website');
        $response->assertSee('Kostenloses Kennenlern-Gespräch');
    }

    public function test_solution_detail_spoke_renders(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);

        $response = $this->get('/loesungen/gruenderpaket-frankfurt/website-fuer-existenzgruender');

        $response->assertStatus(200);
        $response->assertSee('Website für Existenzgründer');
    }

    public function test_guide_article_renders(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);

        $response = $this->get('/ratgeber/geschaeftsausstattung-gruendung-checkliste');

        $response->assertStatus(200);
        $response->assertSee('Geschäftsausstattung für Existenzgründer');
        $response->assertSee('Schritt 0: Bevor der digitale Teil losgeht');
    }

    public function test_seeder_updates_existing_page_instead_of_duplicating(): void
    {
        // Pre-create a page with the pillar slug but different content
        Page::factory()->create([
            'slug' => ['de' => 'gruenderpaket-frankfurt', 'en' => 'gruenderpaket-frankfurt'],
            'title' => ['de' => 'Old Title', 'en' => 'Old Title'],
            'type' => Page::TYPE_SOLUTION_HUB,
            'is_active' => false,
            'content' => ['de' => []],
        ]);

        $this->seed(GruenderpaketContentSeeder::class);

        // Should still be one row, but now with the seeder's data
        $count = Page::where('slug->de', 'gruenderpaket-frankfurt')->count();
        $this->assertSame(1, $count);

        $page = Page::where('slug->de', 'gruenderpaket-frankfurt')->first();
        $this->assertSame('Gründerpaket Frankfurt', $page->getTranslation('title', 'de'));
        $this->assertTrue($page->is_active);
    }
}
