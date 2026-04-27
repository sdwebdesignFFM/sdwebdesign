<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GuideOverviewMetaTitleFixTest extends TestCase
{
    use RefreshDatabase;

    private function seedGuideOverviewWithBrokenMeta(): Page
    {
        $page = Page::factory()->create([
            'type' => Page::TYPE_GUIDE_OVERVIEW,
            'slug' => ['de' => 'ratgeber', 'en' => 'guides'],
            'title' => ['de' => 'Ratgeber', 'en' => 'Guides'],
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        // Simulate the live bug: the column literally contains a JSON
        // string instead of being JSON-encoded by the column type.
        DB::table('pages')
            ->where('id', $page->id)
            ->update([
                'meta_title' => '{"de":"Ratgeber | Entscheidungshilfen für digitale Projekte | sdWebdesign","en":"Guides | Decision Aids for Digital Projects | sdWebdesign"}',
            ]);

        return $page->fresh();
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_27_080324_fix_guide_overview_double_encoded_meta.php');
        $migration->up();
    }

    public function test_migration_unwraps_double_encoded_meta_title(): void
    {
        $page = $this->seedGuideOverviewWithBrokenMeta();

        $this->runMigration();

        $page->refresh();
        $de = $page->getTranslation('meta_title', 'de');
        $en = $page->getTranslation('meta_title', 'en');

        $this->assertSame('Ratgeber | Entscheidungshilfen für digitale Projekte', $de);
        $this->assertSame('Guides | Decision Aids for Digital Projects', $en);
    }

    public function test_migration_strips_manual_brand_suffix(): void
    {
        $this->seedGuideOverviewWithBrokenMeta();

        $this->runMigration();

        $page = Page::where('type', Page::TYPE_GUIDE_OVERVIEW)->first();
        $this->assertStringNotContainsString('sdWebdesign', $page->getTranslation('meta_title', 'de'));
    }

    public function test_migration_is_noop_when_meta_already_clean(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_GUIDE_OVERVIEW,
            'slug' => ['de' => 'ratgeber', 'en' => 'guides'],
            'title' => ['de' => 'Ratgeber', 'en' => 'Guides'],
            'is_active' => true,
            'content' => ['de' => []],
            'meta_title' => ['de' => 'Sauber', 'en' => 'Clean'],
        ]);

        $this->runMigration();

        $page = Page::where('type', Page::TYPE_GUIDE_OVERVIEW)->first();
        $this->assertSame('Sauber', $page->getTranslation('meta_title', 'de'));
    }

    public function test_resolve_translated_string_handles_raw_json_string_in_controller(): void
    {
        // End-to-end: page with broken meta survives the controller defensive layer.
        $this->seedGuideOverviewWithBrokenMeta();

        $body = $this->get('/ratgeber')->getContent();

        // Title tag must NOT contain the literal JSON brace.
        preg_match('#<title[^>]*>([^<]*)</title>#', $body, $m);
        $this->assertArrayHasKey(1, $m);
        $this->assertStringNotContainsString('{"de":', $m[1]);
        $this->assertStringNotContainsString('{', $m[1]);
        $this->assertStringContainsString('Ratgeber', $m[1]);
    }
}
