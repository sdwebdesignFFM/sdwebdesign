<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * The /referenzen overview page renders projects from its own
 * content.projects list — not from the reference-detail pages — so updating
 * sort_order on the detail pages alone doesn't change the overview ordering.
 *
 * The previously stored Normatec project entry on this overview page was:
 * - titled "Zeiterfassungs- & Einsatzplanungs-Webapp" (does not name Normatec)
 * - had a wrong tech stack ("React, Node.js, PostgreSQL" — the platform is
 *   Laravel + Filament + Inertia)
 * - contained confidential numbers ("15 Stunden/Woche", "Fehlerquote 0%")
 *   which the owner explicitly marked as not-for-publication
 *
 * This migration replaces that entry with a corrected one that:
 * - names Normatec as the customer
 * - lists the real tech stack
 * - keeps only qualitative result statements (no concrete numbers)
 * - reorders the projects so Normatec is the lead reference
 *
 * Other entries (Gewapur, Kosmetikerin) keep their existing content but get
 * renumbered.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('type', Page::TYPE_REFERENCES)->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];
        $existingProjects = $content['projects'] ?? [];

        $normatec = $this->normatecEntry();

        // Find Gewapur and Kosmetikerin in the existing list, keep their data
        $gewapur = $this->findProjectByTitleSubstring($existingProjects, 'Gewapur');
        $kosmetikerin = $this->findProjectByTitleSubstring($existingProjects, 'Kosmetikerin');

        $newProjects = [$normatec];
        if ($gewapur) {
            $gewapur['number'] = '02';
            $newProjects[] = $gewapur;
        }
        if ($kosmetikerin) {
            $kosmetikerin['number'] = '03';
            $newProjects[] = $kosmetikerin;
        }

        $content['projects'] = $newProjects;

        $page->setTranslation('content', 'de', $content);
        $page->save();
    }

    public function down(): void
    {
        // No structural revert; this only changes content.projects order/contents.
    }

    /**
     * @param  array<int, array<string, mixed>>  $projects
     * @return array<string, mixed>|null
     */
    private function findProjectByTitleSubstring(array $projects, string $needle): ?array
    {
        foreach ($projects as $project) {
            if (stripos($project['title'] ?? '', $needle) !== false) {
                return $project;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function normatecEntry(): array
    {
        return [
            'icon' => 'cpu',
            'number' => '01',
            'title' => 'Normatec — Workforce-Management-Plattform',
            'client' => 'Normatec',
            'detail_slug' => 'zeiterfassung-einsatzplanung',
            'tagline' => 'Maßgeschneiderte Workforce-Management-Plattform für die Personalvermittlung im Automotive-Sektor — Mitarbeiter-Lebenszyklus, Schulung, konfliktfreie Einsatzplanung, Zeiterfassung und CarPool-Logistik in einem System. Seit 2023 in laufender Entwicklung.',
            'categories' => [
                'B2B-Plattform',
                'Workforce-Management',
                'Personalvermittlung Automotive',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Personalvermittlung in der Automobilindustrie hat operative Anforderungen, die weder klassische HR-Software wie Personio noch Enterprise-Suiten wie SAP SuccessFactors abdecken. Vor der Plattform liefen die Prozesse auf Excel-Listen, isolierten Tools und vielen E-Mails.',
                'items' => [
                    'Hochfrequente Mitarbeiter-Disposition mit Schicht- und Verfügbarkeits-Konflikten',
                    'Werks- und qualifikations-spezifische Einsatzplanung',
                    'Onboarding und Schulung individuell pro Einsatz und Werk',
                    'CarPool-Logistik für Mitarbeiter ohne eigenes Fahrzeug',
                    'Compliance: AÜG, DSGVO, EU Cyber Resilience Act',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine maßgeschneiderte Workforce-Management-Plattform auf modernem Laravel-Stack mit Filament-Admin und Inertia-Frontend. Über 24+ Monate iterativ ausgebaut — vom ersten Discovery-Workshop zu einer integrierten Plattform mit über 40 Domain-Modulen. Microsoft Azure SSO für Mitarbeiter, Dropbox Sign für digitale Vertragsunterschrift, Geo-Routing für CarPool-Logistik.',
                'items' => [
                    'Smart Availability Checking — konfliktfreie Echtzeit-Disposition',
                    'Integriertes E-Learning mit Quiz und Schulungs-Modulen',
                    'CarPool-Disposition mit Geo-Routing',
                    'Microsoft Azure SSO für Mitarbeiter-Login',
                    'Dropbox Sign · digitale Vertragsunterschrift',
                    'CRA-Compliance dokumentiert und in der Entwicklung verankert',
                ],
            ],
            'features' => [
                [
                    'title' => 'Mitarbeiter-Lebenszyklus & Onboarding',
                    'items' => [
                        'Stammdaten mit Skills, Qualifikationen und Dokumenten',
                        'Strukturierter Onboarding-Pfad mit Quiz und Schulungs-Modulen',
                        'Einsatz-spezifische Berechtigungen',
                        'Mehrsprachig (DE/EN)',
                    ],
                ],
                [
                    'title' => 'Smart Availability Checking & Einsatzplanung',
                    'items' => [
                        'Echtzeit-Verfügbarkeitsabgleich über alle Mitarbeiter',
                        'Werks- und qualifikations-spezifische Filter',
                        'Konflikt-Detection bei Schicht- und Doppel-Einsätzen',
                        'Multi-Werk- und Multi-Branch-Strukturen',
                    ],
                ],
                [
                    'title' => 'CarPool & Fahrgemeinschafts-Logistik',
                    'items' => [
                        'Geo-Routing zwischen Wohnort und Werk',
                        'Fahrer-/Beifahrer-Matching mit Kapazität',
                        'Schicht-synchrone Fahrgemeinschaften',
                    ],
                ],
                [
                    'title' => 'Compliance & Sicherheit',
                    'items' => [
                        'CRA-Roadmap dokumentiert',
                        'AÜG- und DSGVO-konforme Workflows',
                        'Microsoft Azure SSO',
                        'Sentry-Monitoring für Audit-Trails',
                    ],
                ],
            ],
            'results' => [
                'Übergang von Excel-basierter Disposition zu Echtzeit-Plattform',
                'Plattform skaliert mit dem Geschäft — von ersten Workflows zu 40+ Modulen',
                'Eingebetteter Product Owner sorgt für kontinuierliche Roadmap-Pflege',
                'Lebende Plattform statt fertiges Projekt — laufende Weiterentwicklung',
                'Normatec besitzt das Produkt vollständig — kein Vendor-Lock-in',
            ],
            'tech_stack' => [
                'Backend: Laravel · PHP 8.2+',
                'Admin: Filament 4 (40+ Resources)',
                'Frontend: Inertia.js · TailwindCSS',
                'Auth: Microsoft Azure SSO',
                'E-Sign: Dropbox Sign',
                'Jobs: Laravel Horizon',
                'Geo: Geokit (CarPool-Routing)',
                'Hosting: Hetzner Cloud (DSGVO-Standort)',
                'Monitoring: Sentry',
                'CI/CD: Azure Pipelines',
            ],
        ];
    }
};
