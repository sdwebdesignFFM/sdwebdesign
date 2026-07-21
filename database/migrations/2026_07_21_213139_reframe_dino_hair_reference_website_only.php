<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Reframe the Dino Hair reference: it is only the WordPress website — the
 * SEO & Google Ads management applies to Embelezar, not Dino Hair.
 *
 * This rewrites the Dino Hair reference-detail content and the /referenzen
 * overview entry to focus purely on the website (design, services & prices,
 * impressions, contact and online booking). Position is preserved.
 *
 * Idempotent, find-or-creates the detail page, no-op without the overview page.
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

        $existing = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', self::SLUG_DE)
            ->first();

        $page = $existing ?? new Page(['type' => Page::TYPE_REFERENCE_DETAIL]);

        $page->parent_id = $overview->id;
        $page->is_active = true;
        if (! $existing) {
            $page->sort_order = (int) Page::where('type', Page::TYPE_REFERENCE_DETAIL)->max('sort_order') + 1;
        }

        $page->setTranslation('slug', 'de', self::SLUG_DE);
        $page->setTranslation('slug', 'en', self::SLUG_EN);
        $page->setTranslation('title', 'de', 'Dino Hair — WordPress-Website für einen Friseursalon in Frankfurt');
        $page->setTranslation('title', 'en', 'Dino Hair — WordPress Website for a Hair Salon in Frankfurt');
        $page->setTranslation('meta_title', 'de', 'Dino Hair — WordPress-Website für einen Friseursalon');
        $page->setTranslation('meta_title', 'en', 'Dino Hair — WordPress Website for a Hair Salon');
        $page->setTranslation('meta_description', 'de', 'Hochwertige WordPress-Website für den Concept-Store-Friseursalon Dino Hair in Frankfurt-Sachsenhausen — mit Leistungen, Preisen, Impressionen und Online-Terminbuchung.');
        $page->setTranslation('meta_description', 'en', 'A high-quality WordPress website for the concept-store hair salon Dino Hair in Frankfurt-Sachsenhausen — with services, prices, impressions and online booking.');

        $page->setTranslation('content', 'de', $this->contentDe());
        $page->save();

        $this->updateOverviewEntry($overview);
    }

    public function down(): void
    {
        // Reframe only — previous content is not restored. Safe no-op.
    }

    private function updateOverviewEntry(Page $overview): void
    {
        $content = $overview->getTranslation('content', 'de');
        if (! is_array($content)) {
            return;
        }

        $projects = $content['projects'] ?? [];
        $found = false;

        foreach ($projects as $index => $project) {
            if (($project['detail_slug'] ?? null) === self::SLUG_DE) {
                $number = $project['number'] ?? str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                $projects[$index] = $this->overviewEntry($number);
                $found = true;
                break;
            }
        }

        if (! $found) {
            $projects[] = $this->overviewEntry(str_pad((string) (count($projects) + 1), 2, '0', STR_PAD_LEFT));
        }

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
                'category' => 'WordPress · Website · Friseursalon',
                'tagline' => 'Design und Entwicklung der Website für Dino Hair — den Concept-Store-Friseursalon in Frankfurt-Sachsenhausen. Ein hochwertiger, zur Premium-Positionierung passender WordPress-Auftritt mit Leistungen und Preisen, Impressionen, Kontakt und Online-Terminbuchung.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'Dino Hair'],
                ['label' => 'Branche', 'value' => 'Friseursalon · Frankfurt'],
                ['label' => 'Website', 'value' => 'dino-hair.de', 'link' => 'https://dino-hair.de/'],
                ['label' => 'Leistung', 'value' => 'Design & Website-Entwicklung'],
                ['label' => 'Standort', 'value' => 'Frankfurt-Sachsenhausen'],
                ['label' => 'Stack', 'value' => 'WordPress · Responsive Design'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'Dino Hair ist ein Concept-Store-Friseursalon in Frankfurt-Sachsenhausen mit dem Anspruch „Styling, das zu Dir passt". Für Dino Hair haben wir die Website gestaltet und entwickelt: einen hochwertigen WordPress-Auftritt, der die Premium-Positionierung des Salons widerspiegelt. Die Seite präsentiert die Leistungen und Preise, gibt mit Impressionen einen Einblick in den Salon und führt Besucher klar zur Kontaktaufnahme und Online-Terminbuchung.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Premium-Friseursalon braucht einen Web-Auftritt, der zur Positionierung passt und Besucher unkompliziert zur Terminbuchung führt — hochwertig, klar strukturiert und auf allen Geräten.',
                'items' => [
                    'Hochwertiger, zum Premium-Anspruch passender Web-Auftritt',
                    'Leistungen und Preise übersichtlich darstellen',
                    'Einblick in den Salon (Impressionen)',
                    'Einfache Kontaktaufnahme und Online-Terminbuchung',
                    'Klare Nutzerführung zum Termin',
                    'Responsiv auf allen Geräten',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein hochwertiger WordPress-Auftritt mit editorialer Bildsprache, der die Premium-Positionierung von Dino Hair widerspiegelt. Leistungen und Preise sind klar strukturiert, Impressionen geben einen Einblick in den Salon, und Kontakt sowie Online-Terminbuchung führen Besucher direkt zum Termin.',
                'items' => [
                    'Hochwertige WordPress-Website mit Premium-Design',
                    'Leistungen und Preise übersichtlich dargestellt',
                    'Impressionen aus dem Salon',
                    'Kontaktformular und Online-Terminbuchung',
                    'Klare Nutzerführung zur Terminvereinbarung',
                    'Responsiv auf allen Geräten',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Website',
                'Individuelles Design · Premium-Anmutung',
                'Online-Terminbuchung · direkt aus der Website',
                'Responsive Design · für alle Geräte',
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
                    'description' => 'Übersichtliche Darstellung aller Leistungen und Preise — von Schnitt und Coloration bis zu Augenbrauen und Wimpern — als klare Grundlage für die Terminvereinbarung.',
                    'items' => [
                        'Alle Leistungen & Preise übersichtlich',
                        'Von Schnitt & Coloration bis Wimpern',
                        'Klare Struktur für schnelle Orientierung',
                        'Ansprechend gestaltete Leistungsseiten',
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
                        'Kurzer Weg vom Besuch zum Termin',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Hochwertige WordPress-Website',
                    'description' => 'Eine individuell gestaltete WordPress-Website, die zur Premium-Positionierung des Salons passt — sauber umgesetzt und einfach pflegbar.',
                    'items' => [
                        'Individuelles Design (kein Baukasten)',
                        'WordPress als pflegbares CMS',
                        'Editoriale Bildsprache',
                        'Sauber umgesetzt & wartbar',
                    ],
                ],
                [
                    'icon' => 'calendar',
                    'title' => 'Kontakt & Online-Terminbuchung',
                    'description' => 'Die Website ist auf ein Ziel ausgerichtet — die Terminvereinbarung. Kontaktformular, klare Call-to-Actions und Online-Terminbuchung führen Besucher direkt zum Termin.',
                    'items' => [
                        'Online-Terminbuchung eingebunden',
                        'Kontaktformular & Standortangaben',
                        'Klare Call-to-Actions',
                        'Conversion-orientierte Nutzerführung',
                    ],
                ],
                [
                    'icon' => 'device-phone-mobile',
                    'title' => 'Responsive & performant',
                    'description' => 'Der Auftritt funktioniert auf allen Geräten — vom Smartphone bis zum Desktop — schnell und mit einer klaren, hochwertigen Darstellung.',
                    'items' => [
                        'Voll responsives Design',
                        'Schnelle Ladezeiten',
                        'Konsistente Darstellung auf allen Geräten',
                        'Hochwertige Bild- und Typo-Umsetzung',
                    ],
                ],
            ],

            'impact_results' => [
                'Hochwertiger Web-Auftritt passend zur Premium-Positionierung',
                'Leistungen und Preise klar und übersichtlich präsentiert',
                'Impressionen geben einen Einblick in den Salon',
                'Online-Terminbuchung führt Besucher direkt zum Termin',
                'Responsiv und schnell auf allen Geräten',
            ],

            'results' => [
                ['value' => '24/7', 'label' => 'Online-Terminbuchung'],
                ['value' => '100%', 'label' => 'Responsive Design'],
                ['value' => '1', 'label' => 'Salon in Frankfurt-Sachsenhausen'],
                ['value' => 'Premium', 'label' => 'Hochwertiges WordPress-Design'],
            ],

            'technologies' => [
                'WordPress',
                'Individuelles Design',
                'Online-Terminbuchung',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Konzept & Design',
                    'description' => 'Gestaltung eines hochwertigen, zur Premium-Positionierung passenden Web-Auftritts.',
                ],
                [
                    'title' => 'WordPress-Umsetzung',
                    'description' => 'Umsetzung der Website mit Leistungen, Preisen und Impressionen.',
                ],
                [
                    'title' => 'Kontakt & Terminbuchung',
                    'description' => 'Einbindung von Kontakt und Online-Terminbuchung für eine reibungslose Terminvereinbarung.',
                ],
                [
                    'title' => 'Feinschliff',
                    'description' => 'Responsives Feintuning und Übergabe.',
                ],
            ],

            'cta' => [
                'title' => 'Sie brauchen einen hochwertigen Web-Auftritt für Ihren Salon oder Ihr Studio?',
                'subtitle' => 'Ob Friseur, Kosmetik oder Beauty — wir gestalten und entwickeln Websites, die zu Ihrer Positionierung passen und Besucher klar zur Terminbuchung führen. Lassen Sie uns unverbindlich darüber sprechen.',
                'button_text' => 'Projekt besprechen',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function overviewEntry(string $number): array
    {
        return [
            'icon' => 'globe',
            'number' => $number,
            'title' => 'Dino Hair — WordPress-Website für einen Friseursalon in Frankfurt',
            'client' => 'Dino Hair',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Hochwertige WordPress-Website für den Concept-Store-Friseursalon Dino Hair in Frankfurt-Sachsenhausen — mit Leistungen, Preisen, Impressionen, Kontakt und Online-Terminbuchung.',
            'categories' => [
                'WordPress',
                'Website',
                'Friseursalon',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Premium-Friseursalon braucht einen hochwertigen Web-Auftritt, der zur Positionierung passt und Besucher unkompliziert zur Terminbuchung führt.',
                'items' => [
                    'Premium-Web-Auftritt passend zur Positionierung',
                    'Leistungen & Preise übersichtlich',
                    'Einblick in den Salon (Impressionen)',
                    'Einfache Online-Terminbuchung',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine hochwertige WordPress-Website mit Premium-Design, Leistungen und Preisen, Impressionen sowie Kontakt und Online-Terminbuchung.',
                'items' => [
                    'Hochwertige WordPress-Website',
                    'Leistungen, Preise & Impressionen',
                    'Kontakt & Online-Terminbuchung',
                    'Voll responsiv',
                ],
            ],
            'features' => [
                [
                    'title' => 'Website & Design',
                    'items' => [
                        'Hochwertiges, individuelles WordPress-Design',
                        'Leistungen, Preise & Impressionen',
                    ],
                ],
                [
                    'title' => 'Kontakt & Terminbuchung',
                    'items' => [
                        'Online-Terminbuchung & Kontaktformular',
                        'Klare Nutzerführung zum Termin',
                    ],
                ],
            ],
            'results' => [
                'Premium-Web-Auftritt passend zur Positionierung',
                'Leistungen und Preise klar präsentiert',
                'Online-Terminbuchung führt direkt zum Termin',
                'Voll responsiv auf allen Geräten',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'Design: individuell (Premium-Anmutung)',
                'Buchung: Online-Terminbuchung',
                'Umsetzung: voll responsiv',
            ],
        ];
    }
};
