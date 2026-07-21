<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the Dino Hair reference as the last portfolio project.
 *
 * Dino Hair is a concept-store hair salon in Frankfurt-Sachsenhausen. For Dino
 * Hair we provide the digital presence end-to-end: a high-quality WordPress
 * website (services, prices, impressions, online booking) plus ongoing local
 * SEO and actively managed Google Ads (SEA) so the salon is found where
 * customers search and clicks turn into appointments.
 *
 * Same mechanics as the previous reference migrations: creates a
 * TYPE_REFERENCE_DETAIL page and appends the matching entry to the /referenzen
 * overview as the last project. Idempotent, no-op when the overview page is
 * missing, defensive against non-array content.
 */
return new class extends Migration
{
    private const SLUG_DE = 'dino-hair-friseursalon-frankfurt';

    private const SLUG_EN = 'dino-hair-friseursalon-frankfurt';

    public function up(): void
    {
        $overview = Page::where('type', Page::TYPE_REFERENCES)->first();

        if (! $overview) {
            return;
        }

        $page = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', self::SLUG_DE)
            ->first() ?? new Page(['type' => Page::TYPE_REFERENCE_DETAIL]);

        $page->parent_id = $overview->id;
        $page->is_active = true;
        $page->sort_order = (int) Page::where('type', Page::TYPE_REFERENCE_DETAIL)->max('sort_order') + 1;

        $page->setTranslation('slug', 'de', self::SLUG_DE);
        $page->setTranslation('slug', 'en', self::SLUG_EN);
        $page->setTranslation('title', 'de', 'Dino Hair — Friseursalon-Website mit SEO- & Google-Ads-Betreuung');
        $page->setTranslation('title', 'en', 'Dino Hair — Hair Salon Website with SEO & Google Ads Management');
        $page->setTranslation('meta_title', 'de', 'Dino Hair — Website, SEO & Google-Ads-Betreuung');
        $page->setTranslation('meta_title', 'en', 'Dino Hair — Website, SEO & Google Ads Management');
        $page->setTranslation('meta_description', 'de', 'Website, laufende lokale SEO und Google-Ads-Betreuung für den Friseursalon Dino Hair in Frankfurt-Sachsenhausen — mit Online-Terminbuchung und Fokus auf mehr Termine.');
        $page->setTranslation('meta_description', 'en', 'Website, ongoing local SEO and Google Ads management for the Dino Hair salon in Frankfurt-Sachsenhausen — with online booking and a focus on more appointments.');

        $page->setTranslation('content', 'de', $this->contentDe());
        $page->save();

        $this->appendToOverview($overview);
    }

    public function down(): void
    {
        Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', self::SLUG_DE)
            ->delete();

        $overview = Page::where('type', Page::TYPE_REFERENCES)->first();
        if (! $overview) {
            return;
        }

        $content = $overview->getTranslation('content', 'de');
        if (! is_array($content)) {
            return;
        }

        $projects = array_values(array_filter(
            $content['projects'] ?? [],
            fn (array $project): bool => ($project['detail_slug'] ?? null) !== self::SLUG_DE
        ));

        foreach ($projects as $index => &$project) {
            $project['number'] = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
        }
        unset($project);

        $content['projects'] = $projects;
        $overview->setTranslation('content', 'de', $content);
        $overview->save();
    }

    private function appendToOverview(Page $overview): void
    {
        $content = $overview->getTranslation('content', 'de');

        if (! is_array($content)) {
            return;
        }

        $projects = $content['projects'] ?? [];

        foreach ($projects as $project) {
            if (($project['detail_slug'] ?? null) === self::SLUG_DE) {
                return;
            }
        }

        $projects[] = $this->overviewEntry(count($projects) + 1);

        $content['projects'] = $projects;
        $overview->setTranslation('content', 'de', $content);
        $overview->save();
    }

    /**
     * @return array<string, mixed>
     */
    private function contentDe(): array
    {
        return [
            'hero' => [
                'category' => 'WordPress · SEO & Google Ads · Friseursalon',
                'tagline' => 'Website, Suchmaschinenoptimierung und laufende Google-Ads-Betreuung für Dino Hair — den Concept-Store-Friseursalon in Frankfurt-Sachsenhausen. Ein hochwertiger WordPress-Auftritt mit Leistungen und Preisen, Online-Terminbuchung und einer durchgängigen SEO- und SEA-Betreuung, die neue Kundinnen und Kunden in den Salon bringt.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'Dino Hair'],
                ['label' => 'Branche', 'value' => 'Friseursalon · Frankfurt'],
                ['label' => 'Website', 'value' => 'dino-hair.de', 'link' => 'https://dino-hair.de/'],
                ['label' => 'Leistung', 'value' => 'Website, SEO & Google-Ads-Betreuung'],
                ['label' => 'Standort', 'value' => 'Frankfurt-Sachsenhausen'],
                ['label' => 'Stack', 'value' => 'WordPress · SEO · Google Ads'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'Dino Hair ist ein Concept-Store-Friseursalon in Frankfurt-Sachsenhausen mit dem Anspruch „Styling, das zu Dir passt". Für Dino Hair betreuen wir den digitalen Auftritt umfassend: einen hochwertigen WordPress-Webauftritt mit Leistungen und Preisen, Impressionen und Online-Terminbuchung — und vor allem die laufende Sichtbarkeit. Mit kontinuierlicher Suchmaschinenoptimierung (lokale SEO für Frankfurt) und einer aktiv gemanagten Google-Ads-Betreuung sorgen wir dafür, dass der Salon dort gefunden wird, wo Kundinnen und Kunden suchen — und aus Klicks echte Termine werden.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Premium-Friseursalon braucht einen Auftritt, der zur Positionierung passt — und vor allem verlässlich neue Kundschaft. Der Friseurmarkt in Frankfurt ist stark lokal umkämpft; ohne laufende Sichtbarkeit in Suche und Anzeigen bleibt Potenzial ungenutzt.',
                'items' => [
                    'Hochwertiger, zum Premium-Anspruch passender Web-Auftritt',
                    'Lokale Sichtbarkeit in Frankfurt (stark umkämpfter Markt)',
                    'Neue Kundinnen und Kunden planbar gewinnen (Google Ads)',
                    'Einfache Online-Terminvereinbarung',
                    'Laufende Betreuung statt einmaliger Website',
                    'Fokus auf Conversion — aus Besuch wird Termin',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein hochwertiger WordPress-Auftritt, kombiniert mit einer durchgängigen Marketing-Betreuung. Die Website präsentiert Leistungen, Preise und Impressionen und führt klar zur Terminbuchung. Parallel läuft eine kontinuierliche lokale SEO-Betreuung sowie aktiv gemanagte Google-Ads-Kampagnen — mit dem Ziel, planbar Terminanfragen zu erzeugen.',
                'items' => [
                    'Hochwertige WordPress-Website (Leistungen, Preise, Impressionen)',
                    'Online-Terminbuchung direkt aus der Website',
                    'Laufende lokale SEO-Betreuung für Frankfurt',
                    'Aktiv gemanagte Google-Ads-Kampagnen (SEA)',
                    'Fokus auf Conversion und Terminvereinbarung',
                    'Kontinuierliche Optimierung statt Einmal-Projekt',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Website',
                'Google Ads · laufende SEA-Betreuung',
                'Lokale SEO · Sichtbarkeit in Frankfurt',
                'Online-Terminbuchung · direkt aus der Website',
                'Responsive Design · für alle Geräte',
                'Laufende Betreuung · Inhalte & Kampagnen',
            ],

            'features' => [
                [
                    'title' => 'Website & Premium-Design',
                    'image' => '/images/references/dino-hair/website.png',
                    'description' => 'Ein hochwertiger, zum Concept-Store passender WordPress-Auftritt — mit editorialer Bildsprache und klarer Nutzerführung, der die Premium-Positionierung von Dino Hair widerspiegelt.',
                    'items' => [
                        'Hochwertiges WordPress-Design',
                        'Editorial-Bildsprache & Premium-Anmutung',
                        'Klare Nutzerführung zur Terminbuchung',
                        'Voll responsiv für alle Geräte',
                    ],
                ],
                [
                    'title' => 'Leistungen & Preise',
                    'image' => '/images/references/dino-hair/leistungen.png',
                    'description' => 'Übersichtliche Darstellung aller Leistungen und Preise — von Schnitt und Coloration bis zu Augenbrauen und Wimpern — als Grundlage für die Terminvereinbarung und als SEO-relevante Inhalte.',
                    'items' => [
                        'Alle Leistungen & Preise übersichtlich',
                        'Von Schnitt & Coloration bis Wimpern',
                        'Klare Struktur für schnelle Orientierung',
                        'SEO-relevante Leistungsseiten',
                    ],
                ],
                [
                    'title' => 'Kontakt & Online-Terminbuchung',
                    'image' => '/images/references/dino-hair/kontakt.png',
                    'description' => 'Terminvereinbarung leicht gemacht: Standort in Sachsenhausen, Kontaktformular und Online-Terminbuchung — damit aus Interesse ein fester Termin wird.',
                    'items' => [
                        'Online-Terminbuchung',
                        'Kontaktformular & Standort Sachsenhausen',
                        'Klarer Call-to-Action zur Buchung',
                        'Lokale Auffindbarkeit',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'magnifying-glass',
                    'title' => 'Suchmaschinenoptimierung (SEO)',
                    'description' => 'Laufende lokale SEO-Betreuung für Frankfurt — technische und inhaltliche Optimierung sowie lokale Signale, damit Dino Hair in der organischen Suche sichtbar ist.',
                    'items' => [
                        'Lokale SEO für Frankfurt',
                        'Technische & inhaltliche Optimierung',
                        'Google-Business-Profil-Signale',
                        'Laufende Betreuung statt Einmal-Maßnahme',
                    ],
                ],
                [
                    'icon' => 'trending-up',
                    'title' => 'Google-Ads-Betreuung (SEA)',
                    'description' => 'Aktiv gemanagte Google-Ads-Kampagnen: Keyword-Auswahl, Anzeigen, Gebote und laufende Optimierung — mit Fokus auf Terminanfragen statt reiner Klicks.',
                    'items' => [
                        'Kampagnen-Setup & laufende Optimierung',
                        'Keywords, Anzeigen & Gebote',
                        'Fokus auf Conversions (Termine)',
                        'Regelmäßiges Reporting',
                    ],
                ],
                [
                    'icon' => 'calendar',
                    'title' => 'Terminbuchung & Conversion',
                    'description' => 'Die Website ist auf ein Ziel ausgerichtet — die Terminvereinbarung. Online-Buchung und klare Call-to-Actions verwandeln Besuche in Termine.',
                    'items' => [
                        'Online-Terminbuchung eingebunden',
                        'Conversion-orientierte Nutzerführung',
                        'Klare Call-to-Actions',
                        'Kurzer Weg vom Klick zum Termin',
                    ],
                ],
            ],

            'impact_results' => [
                'Hochwertiger Web-Auftritt passend zur Premium-Positionierung',
                'Laufende lokale SEO-Betreuung für Sichtbarkeit in Frankfurt',
                'Aktiv gemanagte Google-Ads-Kampagnen für planbare Neukunden',
                'Online-Terminbuchung verwandelt Besuche in Termine',
                'Kontinuierliche Betreuung statt Einmal-Projekt',
            ],

            'results' => [
                ['value' => 'SEO', 'label' => 'Laufende lokale Betreuung'],
                ['value' => 'SEA', 'label' => 'Google-Ads-Kampagnen'],
                ['value' => '24/7', 'label' => 'Online-Terminbuchung'],
                ['value' => '1', 'label' => 'Salon in Frankfurt-Sachsenhausen'],
            ],

            'technologies' => [
                'WordPress',
                'SEO',
                'Google Ads',
                'Lokale SEO',
                'Online-Terminbuchung',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Website & Design',
                    'description' => 'Hochwertiger WordPress-Auftritt mit Leistungen, Preisen und Impressionen.',
                ],
                [
                    'title' => 'Online-Terminbuchung',
                    'description' => 'Einbindung der Online-Terminbuchung für eine reibungslose Terminvereinbarung.',
                ],
                [
                    'title' => 'Lokale SEO',
                    'description' => 'Laufende Suchmaschinenoptimierung für die lokale Sichtbarkeit in Frankfurt.',
                ],
                [
                    'title' => 'Google Ads',
                    'description' => 'Setup und laufende Betreuung der Google-Ads-Kampagnen mit Fokus auf Terminanfragen.',
                ],
                [
                    'title' => 'Laufende Optimierung',
                    'description' => 'Kontinuierliche Optimierung von SEO, Kampagnen und Website.',
                ],
            ],

            'cta' => [
                'title' => 'Sie möchten lokal gefunden werden und mehr Termine gewinnen?',
                'subtitle' => 'Ob Website, laufende SEO oder Google-Ads-Betreuung — wir bringen lokale Dienstleister dorthin, wo ihre Kundschaft sucht, und machen aus Klicks echte Termine. Lassen Sie uns unverbindlich darüber sprechen.',
                'button_text' => 'Projekt besprechen',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function overviewEntry(int $position): array
    {
        return [
            'icon' => 'trending-up',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'Dino Hair — Friseursalon-Website mit SEO- & Google-Ads-Betreuung',
            'client' => 'Dino Hair',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Hochwertige WordPress-Website plus laufende lokale SEO- und Google-Ads-Betreuung für den Concept-Store-Friseursalon Dino Hair in Frankfurt-Sachsenhausen — mit Online-Terminbuchung und Fokus auf mehr Termine.',
            'categories' => [
                'WordPress',
                'SEO & Google Ads',
                'Friseursalon',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Premium-Friseursalon in einem stark umkämpften lokalen Markt braucht einen hochwertigen Auftritt und vor allem verlässliche Sichtbarkeit in Suche und Anzeigen.',
                'items' => [
                    'Premium-Web-Auftritt passend zur Positionierung',
                    'Lokale Sichtbarkeit in Frankfurt',
                    'Planbare Neukundengewinnung',
                    'Einfache Online-Terminbuchung',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Hochwertige WordPress-Website mit Online-Terminbuchung plus durchgängige lokale SEO- und Google-Ads-Betreuung — mit Fokus auf Terminanfragen.',
                'items' => [
                    'WordPress-Website (Leistungen, Preise, Impressionen)',
                    'Online-Terminbuchung',
                    'Laufende lokale SEO für Frankfurt',
                    'Aktiv gemanagte Google-Ads-Kampagnen',
                    'Fokus auf Conversion & Termine',
                ],
            ],
            'features' => [
                [
                    'title' => 'Website & Terminbuchung',
                    'items' => [
                        'Hochwertige WordPress-Website',
                        'Online-Terminbuchung & klare CTAs',
                    ],
                ],
                [
                    'title' => 'SEO & Google Ads',
                    'items' => [
                        'Laufende lokale SEO für Frankfurt',
                        'Aktiv gemanagte Google-Ads-Kampagnen',
                    ],
                ],
            ],
            'results' => [
                'Premium-Web-Auftritt passend zur Positionierung',
                'Laufende lokale SEO für Sichtbarkeit in Frankfurt',
                'Google-Ads-Kampagnen für planbare Neukunden',
                'Online-Terminbuchung verwandelt Besuche in Termine',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'SEO: laufende lokale Betreuung',
                'SEA: Google-Ads-Kampagnen',
                'Buchung: Online-Terminbuchung',
                'Betrieb: laufende Betreuung',
            ],
        ];
    }
};
