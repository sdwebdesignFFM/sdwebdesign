<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Phase C bug-fix — restructure the Plattform-Discovery page so its content
 * shape matches both the solution-hub card preview and the solution-detail
 * full-page renderer.
 *
 * Original Phase C migration stored the 3 workshop phases under `features`
 * as a list of objects ({title, description, items}). The hub card iterates
 * `features` (or `features.items`) as flat strings and crashed with a 500
 * when it hit the nested objects.
 *
 * This migration:
 *   - Moves the 3 phases to `process.steps` (matching the detail template's
 *     numbered step renderer, which now also renders step.items as bullets).
 *   - Replaces `features` with a small flat-string preview list used only
 *     for the hub card, matching the existing convention.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('slug->de', 'plattform-discovery')->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];

        $content['features'] = [
            'title' => 'Was im Workshop passiert',
            'intro' => 'Drei klar getrennte Phasen — vom Briefing bis zum dokumentierten Ergebnis.',
            'items' => [
                'Briefing-Template vorab — 15-30 Minuten Vorbereitungsaufwand',
                'Strukturierter 2-Stunden-Workshop vor Ort oder remote',
                'Discovery-Dokument als PDF (12-20 Seiten) inklusive Follow-up-Termin',
            ],
        ];

        $content['process'] = [
            'title' => 'So läuft der Discovery-Workshop ab',
            'steps' => [
                [
                    'title' => 'Vor dem Workshop',
                    'description' => 'Sie bekommen ein kurzes Briefing-Template und füllen es aus (15-30 Minuten Aufwand). Bestandstools und -systeme können Sie als Liste oder Screenshots beifügen — mehr ist nicht nötig.',
                    'items' => [
                        'Briefing-Template per E-Mail (Branche, Geschäftsmodell, Stakeholder, Bestandssysteme)',
                        'Optional: Liste der heutigen Tools/Excel-Listen, die Sie ablösen wollen',
                        'Optional: bestehende Angebote anderer Anbieter (für eine fundierte zweite Meinung)',
                    ],
                ],
                [
                    'title' => 'Im Workshop (2 Stunden)',
                    'description' => 'Strukturierter Workshop, bei dem wir gemeinsam Anforderungen aufnehmen, Workflows skizzieren, Stakeholder mappen, Tech-Optionen besprechen und Risiken benennen. Vor Ort in Frankfurt oder remote per Video.',
                    'items' => [
                        'Anforderungs-Workshop mit strukturiertem Discovery-Framework',
                        'Workflow-Skizzen direkt am Board (analog oder digital)',
                        'Stakeholder-Mapping: wer entscheidet, wer setzt um, wer ist betroffen',
                        'Tech-Stack-Optionen mit Trade-Offs (Make, Buy, Build, Hybrid)',
                        'Risiko-Identifikation mit Mitigation-Vorschlägen',
                    ],
                ],
                [
                    'title' => 'Nach dem Workshop (Aufbereitung)',
                    'description' => 'Wir bereiten die Ergebnisse als Discovery-Dokument auf. Lieferung typisch 5-7 Werktage nach dem Workshop. Inklusive einem Follow-up-Termin (30 Min) für Rückfragen.',
                    'items' => [
                        'Discovery-Dokument als PDF (typisch 12-20 Seiten)',
                        'Anforderungs-Liste, priorisiert nach Must-Have / Nice-to-Have',
                        'Tech-Stack-Empfehlung mit Begründung',
                        'Aufwand-Schätzung (Personenmonate, Budget-Range)',
                        'Phasenplan: Pilot, Ausbau, Skalierung',
                        'Follow-up-Termin (30 Min) für Klärung von Rückfragen',
                    ],
                ],
            ],
        ];

        $page->setTranslation('content', 'de', $content);
        $page->save();
    }

    public function down(): void
    {
        // No rollback — restoring the broken structure would re-introduce the
        // 500 on the Plattformen hub. The original Phase C migration's down()
        // removes the page entirely if needed.
    }
};
