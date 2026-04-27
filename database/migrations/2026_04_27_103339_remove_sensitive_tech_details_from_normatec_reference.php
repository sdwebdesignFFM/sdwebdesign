<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Strip the technically over-detailed and security-sensitive
 * sections from the Normatec reference page:
 *
 *   - tech_stack: enumerated stack items including Azure SSO,
 *     Dropbox Sign, the specific monitoring stack — too revealing
 *     about an active customer's deployment surface.
 *   - technologies: tag-cloud listing every framework, library
 *     and infrastructure component (Laravel/Vue/Hetzner/Azure …).
 *     Marketing-irrelevant and leaks attack surface.
 *   - technical_details: architecture cards naming the exact
 *     migration count, AÜG-/DSGVO-Compliance setup, CI/CD
 *     pipeline composition.
 *
 * The narrative sections (challenge, solution, features,
 * impact_results, testimonial, timeline) stay — they make the
 * reference work without revealing implementation specifics.
 *
 * The reference-detail template hides each block when its source
 * is an empty array, so emptying these three keys is enough to
 * make them disappear without any template change.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('slug->de', 'zeiterfassung-einsatzplanung')->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];

        $content['tech_stack'] = [];
        $content['technologies'] = [];
        $content['technical_details'] = [];

        // Sharpen the impact_results so the page still feels rich
        // after the three sections are gone — kept intentionally
        // narrative, no implementation specifics.
        $content['impact_results'] = [
            'Übergang von einer Excel-basierten Disposition zu einer Echtzeit-Plattform — ein operativer Sprung in Effizienz und Übersicht.',
            'Plattform skaliert mit dem Geschäft mit, ohne dass der Workflow wieder zerlegt werden muss — neue Anforderungen werden iterativ ergänzt.',
            'Eingebetteter Product Owner sorgt für kontinuierliche Roadmap-Pflege und enge Abstimmung mit dem operativen Geschäft.',
            'Lebende Plattform statt fertiges Projekt — laufende Weiterentwicklung über mehrere Jahre hinweg.',
            'Normatec besitzt das Produkt vollständig — kein Lizenz-Lock-in, kein Vendor-Wechsel-Risiko.',
            'Compliance-Anforderungen werden von Anfang an mitgedacht und sind in der Roadmap fest verankert.',
        ];

        $page->setTranslation('content', 'de', $content);
        $page->save();
    }

    public function down(): void
    {
        // Re-introducing the sensitive sections is not desirable; no-op.
    }
};
