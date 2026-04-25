<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Phase A.3 — Add the "approach" section to the homepage that renders
 * directly under the hero. Two columns: senior-PO claim + bio on the left,
 * Normatec lead-case teaser on the right.
 *
 * The home.blade.php template was extended in the same commit to render a
 * new `approach` section if `approach.title` is set; this migration sets
 * the content. Idempotent — re-running just rewrites the same payload.
 */
return new class extends Migration
{
    public function up(): void
    {
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if (! $home) {
            return;
        }

        $content = $home->getTranslation('content', 'de') ?? [];

        $content['approach'] = [
            'badge' => 'Was uns ausmacht',
            'title' => 'Technischer Partner für digitale Systeme und Prozesse',
            'text' => "Hinter sdwebdesign steht Steffen Fasselt — seit 20 Jahren selbstständiger Unternehmer, seit über 10 Jahren als Product Owner für Mittelständler. Daraus ist eine klare Spezialisierung gewachsen: maßgeschneiderte B2B-Plattformen, die mit dem Geschäft mitwachsen.\n\nEingespieltes Entwickler-Netzwerk, kein Agentur-Overhead. Begleitung über Monate und Jahre, nicht über einzelne Projekte.",
            'cta_text' => 'Mehr über Steffen und die Methodik',
            'cta_link' => '/ueber-uns',
            'case_teaser' => [
                'label' => 'Aktuelle Plattform-Begleitung',
                'title' => 'Normatec — Workforce-Management für Personalvermittlung Automotive',
                'description' => 'Seit 2023 entwickeln wir die Plattform, die Disposition, Schulung, Zeiterfassung und CarPool-Logistik für Normatec abbildet. 24+ Monate aktive Entwicklung, 40+ Domain-Module.',
                'link' => '/referenzen/zeiterfassung-einsatzplanung',
                'tags' => ['Laravel', 'Filament', 'Inertia', 'Azure SSO', 'CRA-ready'],
            ],
        ];

        $home->setTranslation('content', 'de', $content);
        $home->save();
    }

    public function down(): void
    {
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if (! $home) {
            return;
        }

        $content = $home->getTranslation('content', 'de') ?? [];
        unset($content['approach']);
        $home->setTranslation('content', 'de', $content);
        $home->save();
    }
};
