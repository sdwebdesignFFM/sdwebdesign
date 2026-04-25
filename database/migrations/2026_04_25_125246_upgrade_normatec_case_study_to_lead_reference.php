<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Phase B (start) — Upgrade the existing reference page that was anonymized
 * as "Dienstleistungsunternehmen" to a fully fleshed-out Normatec case study,
 * positioned as the lead reference for the new B2B-platform positioning.
 *
 * Owner gave explicit permission to use "Normatec" as the customer name.
 * No confidential business details (employee counts, revenue figures, KPIs)
 * are surfaced — only technical and functional descriptions and the publicly
 * known industry segment (Personalvermittlung Automotive).
 *
 * The existing slug `zeiterfassung-einsatzplanung` stays unchanged to avoid
 * SEO discontinuity. The case is reordered to sort_order = 1 so it shows
 * first on /referenzen and on the homepage reference teaser.
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

        // Title (used in lists, breadcrumbs, headings)
        $page->setTranslation('title', 'de', 'Normatec — Workforce-Management-Plattform');
        $page->setTranslation('meta_title', 'de', 'Normatec Case Study — Workforce-Management-Plattform für Personalvermittlung Automotive');
        $page->setTranslation('meta_description', 'de', 'Maßgeschneiderte Workforce-Management-Plattform für die Automotive-Personalvermittlung Normatec: Mitarbeiter-Disposition, E-Learning, CarPool, Azure SSO, E-Sign — seit 24+ Monaten in laufender Entwicklung.');

        $content = $page->getTranslation('content', 'de') ?? [];

        // Hero
        $content['hero'] = array_merge($content['hero'] ?? [], [
            'category' => 'B2B-Plattform · Workforce-Management',
            'tagline' => 'Maßgeschneiderte Workforce-Management-Plattform für Normatec — die Personalvermittlung im Automotive-Sektor. Mitarbeiter-Lebenszyklus, Schulung, konfliktfreie Einsatzplanung, Zeiterfassung und CarPool-Logistik in einem System. Seit 2023 in laufender Entwicklung.',
        ]);

        // Meta — replace anonymized "Dienstleistungsunternehmen" with real customer
        $content['meta'] = [
            ['label' => 'Kunde', 'value' => 'Normatec'],
            ['label' => 'Branche', 'value' => 'Personalvermittlung · Automotive'],
            ['label' => 'Start', 'value' => '2023'],
            ['label' => 'Engagement', 'value' => 'Laufende Plattform-Begleitung (24+ Monate)'],
            ['label' => 'Typ', 'value' => 'Maßgeschneiderte B2B-Plattform'],
            ['label' => 'Stack', 'value' => 'Laravel · Filament · Inertia'],
        ];

        // Description — overview of who Normatec is and what we do
        $content['description'] = [
            'title' => 'Über das Projekt',
            'text' => 'Normatec ist ein spezialisierter Personaldienstleister für die Automobilindustrie. Wir entwickeln und betreuen seit 2023 die zentrale Plattform, die den gesamten Mitarbeiter-Lebenszyklus abbildet — vom Erstkontakt über das Onboarding und die Qualifizierung bis hin zur Einsatzplanung, Zeiterfassung und Lohnvorbereitung. Statt eines Bauchladens aus Standard-Tools betreibt Normatec eine integrierte Plattform, die exakt die Workflows der Automotive-Personalvermittlung abbildet — und mit dem Geschäft mitwächst.',
        ];

        // Challenge
        $content['challenge'] = [
            'title' => 'Die Ausgangslage',
            'description' => 'Personalvermittlung in der Automobilindustrie hat operative Anforderungen, die weder klassische HR-Software wie Personio noch Enterprise-Suiten wie SAP SuccessFactors abdecken: hochfrequente Einsatzwechsel mit kurzfristigen Schichten, werks- und qualifikations-spezifische Verfügbarkeiten, Fahrgemeinschafts-Logistik zwischen Wohnort und Werk, und regulatorische Anforderungen (AÜG, DSGVO, EU Cyber Resilience Act). Vor der Plattform liefen diese Prozesse auf Excel-Listen, isolierten Tools und vielen E-Mails.',
            'items' => [
                'Hochfrequente Mitarbeiter-Disposition mit Schicht- und Verfügbarkeits-Konflikten',
                'Werks- und qualifikations-spezifische Einsatzplanung',
                'Onboarding und Schulung individuell je Einsatz und Werk',
                'Zeiterfassung mit Schicht-, Nacht- und Sonderzuschlägen',
                'CarPool-Logistik für Mitarbeiter ohne eigenes Fahrzeug',
                'Compliance: AÜG, DSGVO, CRA — sauber dokumentiert und auditierbar',
            ],
        ];

        // Solution
        $content['solution'] = [
            'title' => 'Die Lösung',
            'description' => 'Eine maßgeschneiderte Workforce-Management-Plattform auf modernem Laravel-Stack mit Filament-Admin und Inertia-Frontend. Über 24+ Monate iterativ entwickelt — vom ersten Discovery-Workshop über schrittweise Feature-Releases bis zur heutigen Plattform mit über 40 Domain-Modulen. Microsoft Azure SSO für Mitarbeiter-Login, Dropbox Sign für digitale Vertragsunterschriften, Geo-Routing für CarPool-Logistik. Keine fertige Standard-Software — eine Plattform, die in Architektur und Detail auf die Realität der Automotive-Personalvermittlung zugeschnitten ist.',
            'items' => [
                'Smart Availability Checking — konfliktfreie Einsatzplanung in Echtzeit',
                'Integriertes E-Learning mit Quiz, Schulungs-Modulen und Onboarding-Pfaden',
                'CarPool-Disposition mit Geo-Routing und Fahrer-/Beifahrer-Matching',
                'Microsoft Azure SSO für nahtlose Mitarbeiter-Anmeldung',
                'Digitale Vertragsunterschrift via Dropbox Sign',
                'Schicht-, Nacht- und Sonderzeit-Erfassung mit korrekter Zuschlags-Berechnung',
                'Background-Jobs (Laravel Horizon) für Massenoperationen',
                'Sentry-Monitoring + Azure-Pipelines CI/CD',
                'CRA-Compliance dokumentiert und in der Entwicklung verankert',
            ],
        ];

        // Tech Stack
        $content['tech_stack'] = [
            ['name' => 'Laravel', 'description' => 'Backend-Framework und API'],
            ['name' => 'Filament 4', 'description' => 'Admin-Panel mit 40+ Resources'],
            ['name' => 'Inertia.js', 'description' => 'SPA-Frontend ohne API-Overhead'],
            ['name' => 'Laravel Horizon', 'description' => 'Background-Job-Verarbeitung'],
            ['name' => 'Microsoft Azure SSO', 'description' => 'Single Sign-On für Mitarbeiter'],
            ['name' => 'Dropbox Sign', 'description' => 'Digitale Vertragsunterschrift'],
            ['name' => 'Saloon', 'description' => 'Strukturierte API-Integrationen'],
            ['name' => 'Sentry', 'description' => 'Production-Monitoring'],
            ['name' => 'Geokit', 'description' => 'Geo-Routing für CarPool'],
            ['name' => 'Spatie Translatable', 'description' => 'Mehrsprachigkeit DE/EN'],
        ];

        // Features (was die Plattform funktional kann)
        $content['features'] = [
            'title' => 'Was die Plattform leistet',
            'intro' => 'Die Plattform deckt den gesamten Mitarbeiter-Lebenszyklus eines Personaldienstleisters ab — vertikal integriert, statt aus Einzeltools zusammengeklebt.',
            'items' => [
                'Mitarbeiter-Stammdaten mit Skills, Qualifikationen, Dokumenten und Verfügbarkeit',
                'Einsatz-Planung mit konfliktfreier Schicht-Logik',
                'Onboarding-Pfade mit Schulungs-Modulen, Quiz und Fortschrittsverfolgung',
                'Zeiterfassung inklusive Schicht-, Nacht- und Sonderzuschlägen',
                'Urlaubs- und Abwesenheits-Verwaltung mit Workflow-Genehmigung',
                'CarPool: Fahrgemeinschafts-Disposition mit Geo-Optimierung',
                'Vertrags- und Dokument-Workflows mit digitaler Unterschrift',
                'Mehrsprachigkeit (DE/EN) für internationale Mitarbeiter',
                'Multi-Werk- und Multi-Branch-Strukturen',
            ],
        ];

        // Technical Details
        $content['technical_details'] = [
            'title' => 'Technische Eckpunkte',
            'items' => [
                ['label' => 'Datenbank', 'value' => '127+ Migrationen über 24 Monate'],
                ['label' => 'Domain-Modelle', 'value' => '40+ Entitäten (Employees, Missions, Skills, Quiz, CarPool, …)'],
                ['label' => 'CI/CD', 'value' => 'Azure-Pipelines mit automatisierten Tests'],
                ['label' => 'Hosting', 'value' => 'Hetzner Cloud (DSGVO-konformer Standort)'],
                ['label' => 'Monitoring', 'value' => 'Sentry für Errors + Performance'],
                ['label' => 'Compliance', 'value' => 'CRA-Roadmap dokumentiert und in Umsetzung'],
                ['label' => 'PHP', 'value' => '8.2+'],
            ],
        ];

        // Impact Results — qualitative statements only, no confidential numbers
        $content['impact_results'] = [
            'title' => 'Was das Engagement liefert',
            'items' => [
                ['label' => 'Übergang', 'value' => 'Von Excel-basierter Disposition zu einer Echtzeit-Plattform'],
                ['label' => 'Skalierung', 'value' => 'Plattform wächst mit dem Geschäft — von ersten Workflows zu 40+ Modulen heute'],
                ['label' => 'Methodik', 'value' => 'Eingebetteter Product Owner sorgt für kontinuierliche Roadmap-Pflege'],
                ['label' => 'Modell', 'value' => 'Kein "fertiges Projekt" — sondern lebende Plattform mit laufender Weiterentwicklung'],
                ['label' => 'Unabhängigkeit', 'value' => 'Normatec besitzt das Produkt vollständig, kein Vendor-Lock-in'],
            ],
        ];

        // CTA
        $content['cta'] = array_merge($content['cta'] ?? [], [
            'title' => 'Sie haben ein ähnliches Plattform-Vorhaben?',
            'subtitle' => 'Personalvermittlung, B2B-Großhandel, spezialisierte Dienstleister — überall dort, wo Standard-Software an Grenzen stößt, lohnt sich eine eigene Plattform. Lassen Sie uns 30 Minuten besprechen, ob und wie eine maßgeschneiderte Lösung für Ihr Unternehmen sinnvoll ist.',
            'button_text' => 'Erstgespräch anfragen',
        ]);

        $page->setTranslation('content', 'de', $content);

        // Make this the lead reference
        $page->sort_order = 1;
        $page->save();

        // Push other references back so Normatec is at the top
        $newOrder = [
            'gewapur-ecommerce' => 2,
            'kosmetikerin-ecommerce-app' => 3,
            'digitale-zeiterfassung' => 4,
        ];
        foreach ($newOrder as $slug => $order) {
            $other = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
                ->where('slug->de', $slug)
                ->first();
            if ($other) {
                $other->sort_order = $order;
                $other->save();
            }
        }
    }

    public function down(): void
    {
        $page = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', 'zeiterfassung-einsatzplanung')
            ->first();

        if (! $page) {
            return;
        }

        // Restore previous title and partial content
        $page->setTranslation('title', 'de', 'Zeiterfassung & Einsatzplanung');

        $content = $page->getTranslation('content', 'de') ?? [];
        $content['meta'] = [
            ['label' => 'Kunde', 'value' => 'Dienstleistungsunternehmen'],
            ['label' => 'Jahr', 'value' => '2023-2024'],
            ['label' => 'Dauer', 'value' => '12+ Monate'],
            ['label' => 'Typ', 'value' => 'Eigenentwicklung'],
        ];
        $page->setTranslation('content', 'de', $content);

        $page->sort_order = 2;
        $page->save();

        // Restore previous reference ordering
        $previousOrder = [
            'kosmetikerin-ecommerce-app' => 1,
            'gewapur-ecommerce' => 3,
            'digitale-zeiterfassung' => 4,
        ];
        foreach ($previousOrder as $slug => $order) {
            $other = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
                ->where('slug->de', $slug)
                ->first();
            if ($other) {
                $other->sort_order = $order;
                $other->save();
            }
        }
    }
};
