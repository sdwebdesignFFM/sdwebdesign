<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\B2BPlatformRatgeberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2BPlatformRatgeberSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_both_guide_pages(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);

        $cost = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'was-kostet-b2b-plattform')
            ->first();
        $agency = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'software-agentur-frankfurt-mittelstand')
            ->first();

        $this->assertNotNull($cost);
        $this->assertNotNull($agency);
        $this->assertTrue($cost->is_active);
        $this->assertTrue($agency->is_active);
    }

    public function test_seeder_sets_low_sort_order_so_articles_surface_at_top(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);

        $cost = Page::where('slug->de', 'was-kostet-b2b-plattform')->first();
        $agency = Page::where('slug->de', 'software-agentur-frankfurt-mittelstand')->first();

        $this->assertLessThan($agency->sort_order, $cost->sort_order);
        $this->assertSame(1, $cost->sort_order);
        $this->assertSame(2, $agency->sort_order);
    }

    public function test_cost_guide_carries_b2b_keyword_and_html_content(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);

        $page = Page::firstWhere('slug->de', 'was-kostet-b2b-plattform');
        $content = $page->getTranslation('content', 'de');

        $this->assertStringContainsString('B2B-Plattform', $page->title);
        $this->assertStringContainsString('Mittelstand', $content['hero']['subtitle']);
        // HTML strong tags must survive — sections render via {!! !!} on the guide page
        $allSectionsHtml = collect($content['sections'])->pluck('content')->implode("\n");
        $this->assertStringContainsString('<strong>', $allSectionsHtml);
        $this->assertStringContainsString('Discovery', $content['cta']['title'].' '.$content['cta']['subtitle']);
        $this->assertSame('/loesungen/plattformen/plattform-discovery', $content['cta']['button_link']);
    }

    public function test_agency_guide_carries_local_seo_signals(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);

        $page = Page::firstWhere('slug->de', 'software-agentur-frankfurt-mittelstand');
        $content = $page->getTranslation('content', 'de');
        $allText = $page->title.' '.collect($content['sections'])->pluck('content')->implode(' ');

        $this->assertStringContainsString('Frankfurt', $allText);
        $this->assertStringContainsString('Bad Homburg', $allText);
        $this->assertStringContainsString('Rhein-Main', $allText);
        $this->assertStringContainsString('IHK Frankfurt', $allText);
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(B2BPlatformRatgeberSeeder::class);
        $this->seed(B2BPlatformRatgeberSeeder::class);

        $this->assertSame(
            1,
            Page::where('slug->de', 'was-kostet-b2b-plattform')->count()
        );
        $this->assertSame(
            1,
            Page::where('slug->de', 'software-agentur-frankfurt-mittelstand')->count()
        );
    }

    public function test_pages_render_at_ratgeber_routes(): void
    {
        // The /ratgeber/{slug} route requires a TYPE_GUIDE_OVERVIEW page
        // for the breadcrumb plus the guide page itself.
        Page::factory()->create([
            'type' => Page::TYPE_GUIDE_OVERVIEW,
            'slug' => ['de' => 'ratgeber', 'en' => 'guides'],
            'title' => ['de' => 'Ratgeber', 'en' => 'Guides'],
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $this->seed(B2BPlatformRatgeberSeeder::class);

        $this->get('/ratgeber/was-kostet-b2b-plattform')->assertStatus(200);
        $this->get('/ratgeber/software-agentur-frankfurt-mittelstand')->assertStatus(200);
    }
}
