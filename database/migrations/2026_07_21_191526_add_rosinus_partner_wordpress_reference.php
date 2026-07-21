<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the Rosinus | Partner reference as the last portfolio project.
 *
 * Rosinus | Partner Rechtsanwälte is an international law firm for business
 * criminal law (Wirtschaftsstrafrecht), tax criminal law and compliance. For
 * their web presence we implemented a designer's artistic template (watercolor
 * imagery) pixel-perfectly as a custom WordPress theme (rosinus-starter) built
 * with Elementor Pro, fully bilingual (DE/EN) via WPML, covering all firm areas
 * — Expertise, Team, Auszeichnungen, Netzwerk, Ombudsstelle, Karriere, Podcast
 * and News.
 *
 * Same mechanics as the previous WordPress reference migrations: creates a
 * TYPE_REFERENCE_DETAIL page and appends the matching entry to the /referenzen
 * overview as the last project. Idempotent, no-op when the overview page is
 * missing, defensive against non-array content.
 */
return new class extends Migration
{
    private const SLUG_DE = 'rosinus-partner-anwaltskanzlei-website';

    private const SLUG_EN = 'rosinus-partner-anwaltskanzlei-website';

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
        $page->setTranslation('title', 'de', 'Rosinus | Partner — Custom-WordPress-Theme für eine Anwaltskanzlei');
        $page->setTranslation('title', 'en', 'Rosinus | Partner — Custom WordPress Theme for a Law Firm');
        $page->setTranslation('meta_title', 'de', 'Rosinus | Partner — Custom-Theme für eine Anwaltskanzlei');
        $page->setTranslation('meta_title', 'en', 'Rosinus | Partner — Custom Theme for a Law Firm');
        $page->setTranslation('meta_description', 'de', 'Custom-WordPress-Theme nach Designer-Vorlage für die Anwaltskanzlei Rosinus | Partner: künstlerisches Aquarell-Design mit Elementor Pro, zweisprachig (DE/EN) über WPML und mit Ombudsstelle.');
        $page->setTranslation('meta_description', 'en', 'Custom WordPress theme built to a designer\'s template for the law firm Rosinus | Partner: an artistic watercolor design with Elementor Pro, bilingual (DE/EN) via WPML, including an ombudsman channel.');

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
                'category' => 'WordPress · Custom-Theme · Anwaltskanzlei',
                'tagline' => 'Design-Umsetzung und Entwicklung des Webauftritts der internationalen Anwaltskanzlei Rosinus | Partner — spezialisiert auf Wirtschaftsstrafrecht, Steuerstrafrecht und Compliance. Ein individuelles WordPress-Theme, das die künstlerische Vorlage des Designers pixelgenau umsetzt: vom Aquarell-Hero über Expertise und Team bis zur Ombudsstelle — durchgängig zweisprachig (Deutsch/Englisch).',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'Rosinus | Partner Rechtsanwälte'],
                ['label' => 'Branche', 'value' => 'Anwaltskanzlei · Wirtschaftsstrafrecht'],
                ['label' => 'Website', 'value' => 'rosinus-partner.com', 'link' => 'https://rosinus-partner.com/'],
                ['label' => 'Leistung', 'value' => 'Custom-Theme nach Designer-Vorlage'],
                ['label' => 'Sprachen', 'value' => 'Deutsch & Englisch (WPML)'],
                ['label' => 'Stack', 'value' => 'WordPress · Custom-Theme · Elementor Pro'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'Rosinus | Partner ist eine international ausgerichtete Anwaltskanzlei für Wirtschaftsstrafrecht, Steuerstrafrecht und Compliance. Für den Webauftritt lag ein individueller Entwurf eines Designers vor — mit einer eigenständigen, künstlerischen Bildsprache (Aquarell-Illustrationen) und einem klaren, seriösen Aufbau. Unsere Aufgabe: diesen Entwurf pixelgenau in ein wartbares WordPress-Theme zu übersetzen. Herausgekommen ist ein individuelles Theme auf Basis von Elementor Pro, das alle Bereiche der Kanzlei abbildet — Über uns, Expertise, Team, Auszeichnungen, Netzwerk, Ombudsstelle, Karriere, Podcast und News — durchgängig zweisprachig (Deutsch/Englisch) und vom Team redaktionell pflegbar.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Eine renommierte Kanzlei mit anspruchsvoller, künstlerischer Designvorlage braucht eine Umsetzung, die dem Entwurf gerecht wird — ohne Kompromisse bei Seriosität, Ladezeit und Pflegbarkeit. Gleichzeitig sind viele Bereiche abzubilden, und die Inhalte müssen konsequent zweisprachig verfügbar sein.',
                'items' => [
                    'Künstlerische Designvorlage des Designers pixelgenau umsetzen',
                    'Viele Bereiche abbilden (Expertise, Team, Ombudsstelle, Podcast, News …)',
                    'Durchgängige Zweisprachigkeit (Deutsch/Englisch)',
                    'Seriöser, vertrauensbildender Auftritt für sensible Mandate',
                    'Redaktionelle Pflege durch das Kanzlei-Team',
                    'Solide Ladezeit und Wartbarkeit trotz reichhaltigem Design',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein individuelles WordPress-Theme (rosinus-starter) auf Basis von Elementor Pro, das die Designvorlage pixelgenau umsetzt — inklusive der Aquarell-Bildsprache und feiner Detailtypografie. Die Mehrsprachigkeit läuft über WPML, sodass alle Inhalte konsistent in Deutsch und Englisch verfügbar sind. Alle Kanzlei-Bereiche sind strukturiert angelegt und über das WordPress-Backend pflegbar.',
                'items' => [
                    'Individuelles Theme, pixelgenau nach Designer-Vorlage (Elementor Pro)',
                    'Künstlerische Bildsprache (Aquarell-Hero) originalgetreu umgesetzt',
                    'Zweisprachigkeit Deutsch/Englisch über WPML',
                    'Strukturierte Bereiche: Expertise, Team, Auszeichnungen, Netzwerk',
                    'Ombudsstelle als vertraulicher Meldekanal',
                    'Podcast „Rosinus-on-Air" und News-Bereich',
                    'Redaktionell über das WordPress-Backend pflegbar',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Fundament',
                'Custom-Theme „rosinus-starter" · nach Designer-Vorlage',
                'Elementor Pro · pixelgenaue Umsetzung',
                'WPML · Zweisprachigkeit (DE/EN)',
                'Slider Revolution · Hero- & Slider-Elemente',
                'Yoast SEO · Suchmaschinenoptimierung',
                'Responsive Design · für alle Geräte',
            ],

            'features' => [
                [
                    'title' => 'Custom-Theme nach Designer-Vorlage',
                    'image' => '/images/references/rosinus-partner/design-home.png',
                    'description' => 'Der Designer lieferte einen eigenständigen, künstlerischen Entwurf — mit Aquarell-Illustrationen und feiner Typografie. Wir haben ihn pixelgenau in ein individuelles WordPress-Theme übersetzt, umgesetzt mit Elementor Pro.',
                    'items' => [
                        'Designer-Vorlage pixelgenau umgesetzt',
                        'Künstlerische Aquarell-Bildsprache originalgetreu',
                        'Individuelles Theme mit Elementor Pro',
                        'Seriös, hochwertig und markengerecht',
                    ],
                ],
                [
                    'title' => 'Expertise & Rechtsgebiete',
                    'image' => '/images/references/rosinus-partner/expertise.png',
                    'description' => 'Die Leistungen der Kanzlei sind klar strukturiert dargestellt: Tätigkeitsbereiche und Rechtsgebiete rund um Wirtschaftsstrafrecht, Steuerstrafrecht und Compliance — übersichtlich und suchmaschinenfreundlich.',
                    'items' => [
                        'Tätigkeitsbereiche & Rechtsgebiete strukturiert',
                        'Kernthemen: Wirtschafts- & Steuerstrafrecht, Compliance',
                        'Klare Nutzerführung zu den Leistungen',
                        'SEO-optimiert (Yoast)',
                    ],
                ],
                [
                    'title' => 'Team & Auszeichnungen',
                    'image' => '/images/references/rosinus-partner/team.png',
                    'description' => 'Das Kanzlei-Team wird in einem eleganten Portrait-Grid präsentiert — mit Einzelprofilen der Anwältinnen und Anwälte, ergänzt um Auszeichnungen und das internationale Netzwerk.',
                    'items' => [
                        'Team-Grid mit Einzelprofilen (10 Anwältinnen & Anwälte)',
                        'Auszeichnungen & Referenzen',
                        'Internationales Netzwerk',
                        'Hochwertige Portrait-Darstellung',
                    ],
                ],
                [
                    'title' => 'Ombudsstelle & Mehrsprachigkeit',
                    'image' => '/images/references/rosinus-partner/ombudsstelle.png',
                    'description' => 'Zwei Besonderheiten: eine Ombudsstelle als vertraulicher Meldekanal (Vertrauensanwalt für Unternehmen) sowie die durchgängige Zweisprachigkeit über WPML — der gesamte Auftritt ist in Deutsch und Englisch verfügbar.',
                    'items' => [
                        'Ombudsstelle als vertraulicher Meldekanal',
                        'Zweisprachig Deutsch/Englisch über WPML',
                        'Podcast „Rosinus-on-Air" & News-Bereich',
                        'Karriere-Bereich für das Recruiting',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Theme nach Designer-Vorlage',
                    'description' => 'Ein individuelles Theme (rosinus-starter) mit Elementor Pro — die künstlerische Vorlage pixelgenau umgesetzt, sauber strukturiert und redaktionell pflegbar.',
                    'items' => [
                        'Custom-Theme „rosinus-starter"',
                        'Elementor Pro · pixelgenaue Umsetzung',
                        'Aquarell-Bildsprache originalgetreu',
                        'Redaktionell pflegbar',
                    ],
                ],
                [
                    'icon' => 'globe',
                    'title' => 'Mehrsprachigkeit (WPML)',
                    'description' => 'Der gesamte Auftritt ist über WPML zweisprachig aufgebaut — alle Bereiche und Inhalte konsistent in Deutsch und Englisch.',
                    'items' => [
                        'WPML · Deutsch & Englisch',
                        'Konsistente Übersetzung aller Bereiche',
                        'Sprachumschalter in der Navigation',
                        'SEO-freundliche Sprach-URLs',
                    ],
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Bereiche & Vertraulichkeit',
                    'description' => 'Von Expertise über Team und Auszeichnungen bis zur Ombudsstelle — alle Kanzlei-Bereiche strukturiert abgebildet, inklusive vertraulichem Meldekanal.',
                    'items' => [
                        'Alle Kanzlei-Bereiche strukturiert',
                        'Ombudsstelle · vertraulicher Meldekanal',
                        'Podcast & News integriert',
                        'Karriere-Bereich',
                    ],
                ],
            ],

            'impact_results' => [
                'Künstlerische Designer-Vorlage pixelgenau als WordPress-Theme umgesetzt',
                'Durchgängig zweisprachiger Auftritt (Deutsch/Englisch) über WPML',
                'Alle Kanzlei-Bereiche strukturiert und redaktionell pflegbar',
                'Ombudsstelle als vertraulicher Meldekanal integriert',
                'Seriöser, hochwertiger Auftritt für sensible Mandate',
            ],

            'results' => [
                ['value' => '10', 'label' => 'Anwältinnen & Anwälte im Team'],
                ['value' => '11', 'label' => 'Individuell gestaltete Bereiche'],
                ['value' => '3', 'label' => 'Kernbereiche der Kanzlei'],
                ['value' => 'DE/EN', 'label' => 'Zweisprachig (WPML)'],
            ],

            'technologies' => [
                'WordPress',
                'Custom-Theme',
                'Elementor Pro',
                'WPML',
                'Slider Revolution',
                'Yoast SEO',
                'Zweisprachig',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Designer-Vorlage & Setup',
                    'description' => 'Übernahme des künstlerischen Designer-Entwurfs und Aufsetzen des individuellen Themes (rosinus-starter) mit Elementor Pro.',
                ],
                [
                    'title' => 'Pixelgenaue Umsetzung',
                    'description' => 'Umsetzung der Vorlage bis ins Detail — Aquarell-Hero, Typografie und die einzelnen Kanzlei-Bereiche.',
                ],
                [
                    'title' => 'Bereiche & Inhalte',
                    'description' => 'Aufbau von Expertise, Team, Auszeichnungen, Netzwerk, Ombudsstelle, Karriere, Podcast und News.',
                ],
                [
                    'title' => 'Mehrsprachigkeit',
                    'description' => 'Einrichtung der Zweisprachigkeit (Deutsch/Englisch) über WPML für alle Inhalte.',
                ],
                [
                    'title' => 'Feinschliff & SEO',
                    'description' => 'Responsives Feintuning, Yoast-SEO und Übergabe an die redaktionelle Pflege durch das Kanzlei-Team.',
                ],
            ],

            'cta' => [
                'title' => 'Sie haben eine Designvorlage, die pixelgenau umgesetzt werden soll?',
                'subtitle' => 'Ob Kanzlei, Beratung oder Marke mit hohem Designanspruch — wir setzen den Entwurf Ihres Designers pixelgenau in ein wartbares, mehrsprachiges WordPress-Theme um. Lassen Sie uns unverbindlich darüber sprechen.',
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
            'icon' => 'shield',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'Rosinus | Partner — Custom-WordPress-Theme für eine Anwaltskanzlei',
            'client' => 'Rosinus | Partner Rechtsanwälte',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Individuelles WordPress-Theme pixelgenau nach der Vorlage eines Designers für eine internationale Anwaltskanzlei (Wirtschaftsstrafrecht, Compliance) — künstlerisches Aquarell-Design mit Elementor Pro, zweisprachig (DE/EN) und mit Ombudsstelle.',
            'categories' => [
                'WordPress',
                'Custom-Theme',
                'Anwaltskanzlei',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Eine renommierte Kanzlei mit anspruchsvoller, künstlerischer Designvorlage braucht eine pixelgenaue, wartbare Umsetzung — seriös, zweisprachig und mit vielen Bereichen.',
                'items' => [
                    'Künstlerische Designer-Vorlage pixelgenau umsetzen',
                    'Viele Kanzlei-Bereiche abbilden',
                    'Durchgängig zweisprachig (DE/EN)',
                    'Seriöser Auftritt für sensible Mandate',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein individuelles WordPress-Theme (rosinus-starter) mit Elementor Pro, das die Vorlage pixelgenau umsetzt — zweisprachig über WPML und mit allen Kanzlei-Bereichen inklusive Ombudsstelle.',
                'items' => [
                    'Individuelles Theme pixelgenau nach Designer-Vorlage',
                    'Umsetzung mit Elementor Pro',
                    'Zweisprachigkeit (DE/EN) über WPML',
                    'Alle Kanzlei-Bereiche strukturiert',
                    'Ombudsstelle als vertraulicher Meldekanal',
                ],
            ],
            'features' => [
                [
                    'title' => 'Design & Umsetzung',
                    'items' => [
                        'Aquarell-Design pixelgenau als Theme umgesetzt',
                        'Elementor Pro, redaktionell pflegbar',
                    ],
                ],
                [
                    'title' => 'Bereiche & Sprachen',
                    'items' => [
                        'Expertise, Team, Ombudsstelle, Podcast, News',
                        'Zweisprachig Deutsch/Englisch (WPML)',
                    ],
                ],
            ],
            'results' => [
                'Künstlerische Designer-Vorlage pixelgenau umgesetzt',
                'Durchgängig zweisprachiger Auftritt (DE/EN)',
                'Alle Kanzlei-Bereiche redaktionell pflegbar',
                'Ombudsstelle als vertraulicher Meldekanal',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'Design: Custom-Theme (rosinus-starter) mit Elementor Pro',
                'Mehrsprachigkeit: WPML (DE/EN)',
                'Slider: Slider Revolution',
                'SEO: Yoast',
            ],
        ];
    }
};
