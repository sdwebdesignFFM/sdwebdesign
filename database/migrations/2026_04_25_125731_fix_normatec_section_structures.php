<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Fix the section structures on the Normatec reference page that the
 * previous migration set in the wrong shape — reference-detail.blade.php
 * expects:
 *   - tech_stack as a flat string list (foreach as $tech, then {{ $tech }})
 *   - impact_results as a flat string list (foreach as $result, then {{ $result }})
 *   - features as a list of objects with {title, description, items}
 *   - technical_details as a list of objects with {title, description, icon, items}
 *
 * The previous migration shipped them as nested objects, which caused
 * htmlspecialchars() to receive an array on /referenzen/zeiterfassung-
 * einsatzplanung — Server Error.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', 'zeiterfassung-einsatzplanung')
            ->first();

        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];

        // tech_stack — flat string list
        $content['tech_stack'] = [
            'Laravel · Backend & API',
            'Filament 4 · Admin Panel (40+ Resources)',
            'Inertia.js · SPA Frontend',
            'Laravel Horizon · Background Jobs',
            'Microsoft Azure SSO',
            'Dropbox Sign · Digital Signatures',
            'Saloon · API Integrations',
            'Sentry · Production Monitoring',
            'Geokit · Geo-Routing für CarPool',
            'Spatie Translatable · DE/EN',
            'Hetzner Cloud · DSGVO-Hosting',
        ];

        // impact_results — flat string list
        $content['impact_results'] = [
            'Übergang von Excel-basierter Disposition zu Echtzeit-Plattform',
            'Plattform skaliert mit dem Geschäft — von ersten Workflows zu 40+ Modulen',
            'Eingebetteter Product Owner sorgt für kontinuierliche Roadmap-Pflege',
            'Lebende Plattform statt fertiges Projekt — laufende Weiterentwicklung',
            'Normatec besitzt das Produkt vollständig — kein Vendor-Lock-in',
            'CRA-Compliance dokumentiert und in der Entwicklung verankert',
        ];

        // features — list of objects with {title, description, items}
        $content['features'] = [
            [
                'title' => 'Mitarbeiter-Lebenszyklus & Onboarding',
                'description' => 'Vom ersten Kontakt über die Bewerbung, das Onboarding und die Schulung bis hin zur laufenden Disposition — der gesamte Mitarbeiter-Lebenszyklus liegt in einer einzigen Plattform statt verteilt auf Excel, E-Mail und Standard-Tools.',
                'items' => [
                    'Mitarbeiter-Stammdaten mit Skills, Qualifikationen und Dokumenten',
                    'Strukturierter Onboarding-Pfad mit Quiz und Schulungs-Modulen',
                    'Verwaltung qualifikations-spezifischer Einsatzberechtigungen',
                    'Mehrsprachiges Interface (DE/EN) für internationale Mitarbeiter',
                ],
            ],
            [
                'title' => 'Smart Availability Checking & Einsatzplanung',
                'description' => 'Konfliktfreie Disposition in Echtzeit. Verfügbarkeiten, Schichtmuster, Werks- und Qualifikations-Konflikte werden automatisch berücksichtigt — der Disponent sieht direkt, wer einsetzbar ist und wer nicht.',
                'items' => [
                    'Echtzeit-Verfügbarkeitsabgleich über alle Mitarbeiter',
                    'Werks- und qualifikations-spezifische Einsatz-Filter',
                    'Konflikt-Detection bei Schicht- und Doppel-Einsätzen',
                    'Multi-Werk- und Multi-Branch-Strukturen',
                ],
            ],
            [
                'title' => 'Zeiterfassung mit Schicht- und Sonderzeit-Logik',
                'description' => 'Korrekte Zeiterfassung inklusive Schicht-, Nacht-, Wochenend- und Sonderzuschlägen — passend zu den tariflichen und vertraglichen Anforderungen der Automotive-Personalvermittlung.',
                'items' => [
                    'Schicht-, Nacht- und Sonderzuschlags-Berechnung',
                    'Urlaubs- und Abwesenheitsverwaltung mit Workflow-Genehmigung',
                    'Lohnvorbereitung mit korrekter Zuordnung pro Einsatz',
                ],
            ],
            [
                'title' => 'CarPool & Fahrgemeinschafts-Logistik',
                'description' => 'Fahrgemeinschafts-Disposition mit Geo-Routing — Mitarbeiter ohne eigenes Fahrzeug werden automatisch passenden Fahrern zugewiesen, basierend auf Wohnort, Werk und Schicht.',
                'items' => [
                    'Geo-Routing zwischen Wohnort und Werk',
                    'Fahrer-/Beifahrer-Matching mit Kapazitätsverwaltung',
                    'Schicht-synchrone Fahrgemeinschaften',
                ],
            ],
            [
                'title' => 'Digitale Vertragsunterschrift & Compliance',
                'description' => 'Verträge, Zusatzvereinbarungen und Datenschutz-Erklärungen werden direkt in der Plattform digital unterschrieben — über Dropbox Sign integriert. CRA-Compliance dokumentiert.',
                'items' => [
                    'Dropbox Sign Integration für rechtsverbindliche E-Signatur',
                    'Microsoft Azure SSO für nahtlose Mitarbeiter-Anmeldung',
                    'CRA-Roadmap dokumentiert und in der Umsetzung',
                    'AÜG- und DSGVO-konforme Dokumentation',
                ],
            ],
        ];

        // technical_details — list of objects with {title, description, icon, items}
        $content['technical_details'] = [
            [
                'icon' => 'database',
                'title' => 'Architektur & Skalierung',
                'description' => 'Die Plattform ist über 24+ Monate iterativ gewachsen — von ersten Disposition-Workflows zu einer integrierten Workforce-Plattform mit über 40 Domain-Modulen.',
                'items' => [
                    '127+ Datenbank-Migrationen',
                    '40+ Filament-Resources',
                    'PHP 8.2+ · Laravel 12',
                    'Inertia.js für SPA-Frontend',
                ],
            ],
            [
                'icon' => 'shield',
                'title' => 'Compliance & Sicherheit',
                'description' => 'Personalvermittlung in regulierten Branchen erfordert sauber dokumentierte Compliance — von DSGVO über AÜG bis zum kommenden EU Cyber Resilience Act (CRA).',
                'items' => [
                    'CRA-Roadmap dokumentiert',
                    'AÜG-konforme Disposition',
                    'DSGVO mit AVV-Verträgen',
                    'Sentry-Monitoring für Audit-Trails',
                ],
            ],
            [
                'icon' => 'git-branch',
                'title' => 'DevOps & Deployment',
                'description' => 'Azure-Pipelines CI/CD mit automatisierten Tests, Hetzner Cloud für DSGVO-konformes Hosting, Sentry für Production-Monitoring.',
                'items' => [
                    'Azure-Pipelines CI/CD',
                    'Hetzner Cloud (DE-Standort)',
                    'Sentry · Errors + Performance',
                    'Laravel Horizon · Background Jobs',
                ],
            ],
        ];

        // results & timeline — leave whatever was there before, don't touch
        // testimonial — none yet
        // technologies — leave existing tag list unless empty
        if (empty($content['technologies'])) {
            $content['technologies'] = [
                'Laravel', 'PHP 8.2', 'Filament', 'Inertia', 'TailwindCSS', 'MySQL',
                'Redis', 'Horizon', 'Sentry', 'Azure SSO', 'Dropbox Sign', 'Saloon',
                'Geokit', 'Spatie Translatable', 'CRA-ready',
            ];
        }

        $page->setTranslation('content', 'de', $content);
        $page->save();
    }

    public function down(): void
    {
        // No structural changes to revert — this only fixes shape inside content JSON
    }
};
