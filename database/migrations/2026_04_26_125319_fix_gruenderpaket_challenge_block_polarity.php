<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * The Gründerpaket pillar page renders two compare blocks: `challenge`
 * (red box with X-icon — meant for the pain anchor) and `approach`
 * (green box with check-icon — meant for the positive promise).
 *
 * The original seeder put a positive statement ("Was wir tatsächlich
 * für Sie bauen") into `challenge`, which then rendered red/X — a
 * confusing visual/semantic mismatch.
 *
 * This migration fixes the live page:
 *   - challenge → real pain anchor: what visitors typically get from
 *     other providers (templates, freelancer juggling, generator-
 *     impressum, hidden costs, no single point of contact)
 *   - approach → keeps "Was wir tatsächlich für Sie bauen" as the
 *     positive answer, with the existing positive copy slightly
 *     sharpened to mirror the new pain anchor.
 *
 * The seeder source has been corrected so fresh seeds match.
 * Migration is idempotent and only touches challenge + approach.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('slug->de', 'gruenderpaket-frankfurt')->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];

        $content['challenge'] = [
            'title' => 'Was Sie woanders typisch bekommen',
            'text' => 'Template-Baukasten statt Beratung. Fünf Freelancer parallel koordinieren — eine Person für Logo, eine für Website, eine für Cookie-Banner, eine für Hosting. Generator-Impressum mit Abmahnungs-Risiko. Versprochene Festpreise, die im Detail nachverhandelt werden. Kein fester Ansprechpartner, der Verantwortung über das ganze Setup übernimmt.',
        ];

        $content['approach'] = [
            'title' => 'Was wir tatsächlich für Sie bauen',
            'text' => 'Aus Ihrem Briefing entsteht keine Template-Anwendung, sondern ein zusammenhängender Auftritt: Logo, Farben, Typografie, Website-Struktur, Tonalität — alles aus einer Idee abgeleitet, alles konsistent über Touchpoints hinweg. Ein Festpreis, ein Ansprechpartner, ein verbindlicher Starttermin. Sie identifizieren sich am Ende mit dem Ergebnis, weil es Ihre Geschichte erzählt — nicht eine generische, sondern Ihre eigene.',
        ];

        $page->setTranslation('content', 'de', $content);
        $page->save();
    }

    public function down(): void
    {
        // Restoring the broken polarity would re-introduce the visual
        // mismatch — intentionally a no-op.
    }
};
