<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\GruenderpaketContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GruenderpaketChallengePolarityFixTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_26_125319_fix_gruenderpaket_challenge_block_polarity.php');
        $migration->up();
    }

    public function test_migration_replaces_challenge_with_pain_anchor(): void
    {
        // Seed the page first so the migration has something to update.
        $this->seed(GruenderpaketContentSeeder::class);

        // Simulate the bug-state by overwriting back to the broken polarity.
        $page = Page::where('slug->de', 'gruenderpaket-frankfurt')->first();
        $content = $page->getTranslation('content', 'de');
        $content['challenge'] = ['title' => 'Was wir tatsächlich für Sie bauen', 'text' => 'old positive text'];
        $page->setTranslation('content', 'de', $content);
        $page->save();

        $this->runMigration();

        $page->refresh();
        $challenge = $page->getTranslation('content', 'de')['challenge'];
        $approach = $page->getTranslation('content', 'de')['approach'];

        $this->assertSame('Was Sie woanders typisch bekommen', $challenge['title']);
        $this->assertStringContainsString('Generator-Impressum', $challenge['text']);
        $this->assertStringContainsString('Abmahnungs-Risiko', $challenge['text']);

        $this->assertSame('Was wir tatsächlich für Sie bauen', $approach['title']);
        $this->assertStringContainsString('Festpreis', $approach['text']);
    }

    public function test_live_page_renders_both_blocks_with_correct_polarity(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);
        $this->runMigration();

        $body = $this->get('/loesungen/gruenderpaket-frankfurt')->assertStatus(200)->getContent();

        // Pain anchor (red box / X-icon block) — should contain the challenge text
        $this->assertStringContainsString('Was Sie woanders typisch bekommen', $body);
        $this->assertStringContainsString('Generator-Impressum', $body);
        // Positive promise (green box / check-icon block)
        $this->assertStringContainsString('Was wir tatsächlich für Sie bauen', $body);

        // Verify they sit inside their respective coloured boxes — order
        // depends on template, but each should be inside a wrapper with
        // the correct polarity class.
        $this->assertMatchesRegularExpression(
            '#border-l-red-500[\s\S]+?Was Sie woanders typisch bekommen#',
            $body,
            'Pain anchor must be inside the red border-l-red-500 box'
        );
        $this->assertMatchesRegularExpression(
            '#border-l-green-500[\s\S]+?Was wir tatsächlich für Sie bauen#',
            $body,
            'Positive promise must be inside the green border-l-green-500 box'
        );
    }

    public function test_migration_skips_when_page_missing(): void
    {
        $this->runMigration();
        $this->assertNull(Page::where('slug->de', 'gruenderpaket-frankfurt')->first());
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seed(GruenderpaketContentSeeder::class);

        $this->runMigration();
        $this->runMigration();

        $page = Page::where('slug->de', 'gruenderpaket-frankfurt')->first();
        $this->assertSame(
            'Was Sie woanders typisch bekommen',
            $page->getTranslation('content', 'de')['challenge']['title']
        );
    }
}
