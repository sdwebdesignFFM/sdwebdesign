<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the portfolio vermögensmanagement reference as the last portfolio project.
 *
 * portfolio vermögensmanagement (pvm, portfolio Verlagsgesellschaft mbH) is an
 * editorial finance medium for wealthy investors — Family Offices, foundations
 * and private wealth. For pvm we built a custom WordPress theme (Tailwind) that
 * implements the client's design template pixel-perfectly, plus a bespoke,
 * accessible (WCAG) and DSGVO-compliant conference platform: three custom post
 * types (Konferenz, Speaker, Sponsoren) with custom fields, a tabbed conference
 * view (Programm/agenda, Anmelden, Informationen, Sponsorship), registration
 * forms (WS Form) and a Mailchimp newsletter integration.
 *
 * The live site already publicly credits "Webentwicklung durch sdWebdesign",
 * so naming the client is warranted. No confidential figures are used.
 *
 * Same mechanics as the previous WordPress reference migrations: creates a
 * TYPE_REFERENCE_DETAIL page and appends the matching entry to the /referenzen
 * overview as the last project. Idempotent, no-op when the overview page is
 * missing, defensive against non-array content.
 */
return new class extends Migration
{
    private const SLUG_DE = 'portfolio-vermoegensmanagement-wordpress-theme';

    private const SLUG_EN = 'portfolio-vermoegensmanagement-wordpress-theme';

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
        $page->setTranslation('title', 'de', 'portfolio vermögensmanagement — Custom-WordPress-Theme & Konferenzplattform');
        $page->setTranslation('title', 'en', 'portfolio vermögensmanagement — Custom WordPress Theme & Conference Platform');
        $page->setTranslation('meta_title', 'de', 'portfolio vermögensmanagement — Custom-Theme & Konferenz');
        $page->setTranslation('meta_title', 'en', 'portfolio vermögensmanagement — Custom Theme & Conference');
        $page->setTranslation('meta_description', 'de', 'Custom-WordPress-Theme nach Designvorlage für das Finanzmedium portfolio vermögensmanagement: barrierefreie Konferenzplattform mit Tabs, Agenda, Registrierung und Mailchimp-Newsletter.');
        $page->setTranslation('meta_description', 'en', 'Custom WordPress theme built to the client\'s design template for finance medium portfolio vermögensmanagement: an accessible conference platform with tabs, agenda, registration and a Mailchimp newsletter.');

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
                'category' => 'WordPress · Custom-Theme · Konferenzplattform',
                'tagline' => 'Design-Umsetzung und Entwicklung eines individuellen WordPress-Themes für portfolio vermögensmanagement — das Finanzmedium für Family Offices, Stiftungen und vermögende Privatpersonen. Ein pixelgenau nach Designvorlage gebautes Theme mit barrierefreier Konferenzplattform (Tabs, Agenda, Speaker & Sponsoren), Registrierung und Mailchimp-Newsletter.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'portfolio vermögensmanagement'],
                ['label' => 'Branche', 'value' => 'Finanzmedium · Vermögensmanagement'],
                ['label' => 'Website', 'value' => 'portfolio-vm.com', 'link' => 'https://portfolio-vm.com/'],
                ['label' => 'Leistung', 'value' => 'Custom-Theme, Konferenzplattform & Integrationen'],
                ['label' => 'Zielgruppe', 'value' => 'Family Offices · Stiftungen · Private Wealth'],
                ['label' => 'Stack', 'value' => 'WordPress · Custom-Theme · Tailwind'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'portfolio vermögensmanagement (pvm) ist ein redaktionelles Finanzmedium für die Geldanlage vermögender Anleger — Family Offices, Stiftungen und Private Wealth im deutschsprachigen Raum. Für pvm haben wir ein individuelles WordPress-Theme entwickelt, das die Designvorlage des Kunden pixelgenau umsetzt — auf einem modernen, schlanken Fundament (Tailwind CSS). Kern des Projekts ist eine eigens entwickelte, barrierefreie Konferenzplattform: die pvm konferenz mit Tabs für Programm, Anmeldung, Informationen und Sponsorship, einer Agenda aus individuellen Feldern sowie Speaker- und Sponsoren-Verwaltung. Registrierung und Newsletter laufen über formularbasierte Integrationen — inklusive Mailchimp-Anbindung.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Finanzmedium für eine anspruchsvolle, vermögende Zielgruppe braucht einen Auftritt, der Seriosität und Klarheit ausstrahlt — exakt nach der Designvorlage des Kunden. Gleichzeitig sollte die Seite mehr können als Artikel: eine vollwertige Konferenz mit Programm, Anmeldung, Speakern und Sponsoren, redaktionell selbst pflegbar, barrierefrei und DSGVO-konform.',
                'items' => [
                    'Designvorlage des Kunden pixelgenau in ein Theme übersetzen',
                    'Redaktionelle Plattform für Fachartikel (Anleger, Geldanlage)',
                    'Eine komplette Konferenz abbilden: Programm, Anmeldung, Speaker, Sponsoren',
                    'Barrierefreiheit (WCAG) und DSGVO-Konformität von Anfang an',
                    'Newsletter-Gewinnung über Mailchimp',
                    'Alle Inhalte durch die Redaktion selbst pflegbar',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein von Grund auf entwickeltes WordPress-Theme auf Tailwind-Basis, das die Designvorlage pixelgenau umsetzt. Herzstück ist eine maßgeschneiderte Konferenzplattform mit drei Custom Post Types (Konferenz, Speaker, Sponsoren), individuellen Feldern und einer barrierefreien Tab-Navigation. Programm/Agenda, Anmeldung, Informationen und Sponsorship liegen in eigenen Tabs; Registrierung und Newsletter laufen über WS Form mit Mailchimp-Anbindung.',
                'items' => [
                    'Individuelles Theme, pixelgenau nach Designvorlage (Tailwind CSS)',
                    'Konferenz-Darstellung mit barrierefreien Tabs (Programm, Anmelden, Info, Sponsorship)',
                    'Agenda aus individuellen Feldern (Zeit, Session, Speaker)',
                    'Custom Post Types für Konferenz, Speaker & Sponsoren mit Relationships',
                    'Registrierung und Sponsorship-Anfrage über WS Form',
                    'Mailchimp-Integration für den Newsletter',
                    'WCAG-barrierefrei und DSGVO-konform umgesetzt',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Fundament',
                'Custom-Theme · pixelgenau nach Designvorlage',
                'Tailwind CSS · Utility-Framework',
                'Custom Post Types · Konferenz, Speaker, Sponsoren',
                'Individuelle Felder (Meta Boxes) · Agenda & Details',
                'WS Form Pro · Registrierung & Formulare',
                'WS Form Mailchimp · Newsletter-Integration',
                'Slim SEO · Suchmaschinenoptimierung',
                'WCAG-barrierefrei · DSGVO-konform',
            ],

            'features' => [
                [
                    'title' => 'Custom-Theme nach Designvorlage',
                    'image' => '/images/references/portfolio-vm/design-home.png',
                    'description' => 'Die Designvorlage des Kunden haben wir pixelgenau in ein individuelles WordPress-Theme übersetzt — schlank und wartbar auf Tailwind-Basis. Ein klarer, seriöser Editorial-Auftritt, der zur anspruchsvollen Finanz-Zielgruppe passt.',
                    'items' => [
                        'Designvorlage pixelgenau umgesetzt',
                        'Individuelles Theme auf Tailwind-Basis (kein Baukasten)',
                        'Redaktionelle Artikel-Struktur (Anleger, Geldanlage)',
                        'Klares, seriöses Editorial-Design',
                    ],
                ],
                [
                    'title' => 'Konferenzplattform mit Tabs & Agenda',
                    'image' => '/images/references/portfolio-vm/konferenz.png',
                    'description' => 'Das Herzstück: eine eigens entwickelte Konferenz-Darstellung. Programm, Anmeldung, Informationen und Sponsorship liegen in barrierefreien Tabs; die Agenda entsteht aus individuellen Feldern mit Uhrzeit, Session und zugeordneten Speakern.',
                    'items' => [
                        'Barrierefreie Tab-Navigation (Programm, Anmelden, Info, Sponsorship)',
                        'Agenda aus individuellen Feldern (Zeit, Session, Speaker)',
                        'Speaker mit Kurzprofil im Modal',
                        'Sponsoren mit Logo, Level und Verlinkung',
                    ],
                ],
                [
                    'title' => 'Registrierung & Formulare',
                    'image' => '/images/references/portfolio-vm/registrierung.png',
                    'description' => 'Anmeldung zur Konferenz und Sponsorship-Anfragen laufen direkt über die Plattform — mit WS-Form-Formularen, DSGVO-Hinweisen und sauberer Datenverarbeitung, ohne Medienbruch.',
                    'items' => [
                        'Konferenz-Registrierung über WS Form',
                        'Separates Sponsorship-Anfrage-Formular',
                        'DSGVO-konforme Datenverarbeitung',
                        'Direkt im Tab integriert',
                    ],
                ],
                [
                    'title' => 'Mailchimp-Newsletter',
                    'image' => '/images/references/portfolio-vm/mailchimp.png',
                    'description' => 'Die Newsletter-Gewinnung („Abonnieren") ist über WS Form direkt an Mailchimp angebunden — neue Abonnenten werden automatisch übergeben, inklusive Anrede, Titel und Firmendaten für die Segmentierung.',
                    'items' => [
                        'Newsletter-Anmeldung mit Mailchimp-Integration',
                        'Automatische Übergabe neuer Abonnenten',
                        'Anrede, Titel & Firmendaten für die Segmentierung',
                        'DSGVO-konformes Anmeldeformular',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Custom-Theme-Entwicklung',
                    'description' => 'Ein von Grund auf entwickeltes Theme auf Tailwind-Basis — die Designvorlage pixelgenau umgesetzt, sauber strukturiert und wartbar, mit modernem Build-Prozess.',
                    'items' => [
                        'Tailwind CSS · Utility-First',
                        'Moderner Build-Prozess (npm)',
                        'Pixelgenaue Design-Umsetzung',
                        'Sauber strukturiert und wartbar',
                    ],
                ],
                [
                    'icon' => 'calendar',
                    'title' => 'Konferenz-Datenmodell',
                    'description' => 'Drei Custom Post Types (Konferenz, Speaker, Sponsoren) mit individuellen Feldern und Relationships bilden die Konferenz vollständig ab — redaktionell selbst pflegbar.',
                    'items' => [
                        'CPTs: Konferenz, Speaker, Sponsoren',
                        'Individuelle Felder für Agenda & Details',
                        'Speaker- und Sponsoren-Relationships',
                        'Alles über das WP-Backend pflegbar',
                    ],
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Barrierefreiheit & DSGVO',
                    'description' => 'Die Konferenzplattform ist von Anfang an barrierefrei (WCAG) und DSGVO-konform gebaut — semantische Tabs mit ARIA, Tastaturbedienung und datensparsame Formulare.',
                    'items' => [
                        'WCAG: ARIA-Rollen und Tastatur-Navigation',
                        'Semantische Tab- und Dialog-Struktur',
                        'DSGVO-konforme Formulare und Hinweise',
                        'Slim SEO für saubere Meta-Daten',
                    ],
                ],
            ],

            'impact_results' => [
                'Designvorlage des Kunden pixelgenau als individuelles Theme umgesetzt',
                'Vollwertige Konferenz mit Programm, Anmeldung, Speakern & Sponsoren in WordPress',
                'Agenda und Konferenzinhalte redaktionell selbst pflegbar',
                'Newsletter-Gewinnung direkt an Mailchimp angebunden',
                'Barrierefrei (WCAG) und DSGVO-konform von Anfang an',
            ],

            'results' => [
                ['value' => '3', 'label' => 'Custom Post Types (Konferenz, Speaker, Sponsoren)'],
                ['value' => '4', 'label' => 'Tabs je Konferenz'],
                ['value' => 'WCAG', 'label' => 'Barrierefrei umgesetzt'],
                ['value' => 'DSGVO', 'label' => 'konform gebaut'],
            ],

            'technologies' => [
                'WordPress',
                'Custom-Theme',
                'Tailwind CSS',
                'Custom Post Types',
                'WS Form',
                'Mailchimp',
                'Slim SEO',
                'WCAG',
                'DSGVO',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Designvorlage & Setup',
                    'description' => 'Übernahme der Designvorlage des Kunden und Aufsetzen eines individuellen Themes auf Tailwind-Basis mit modernem Build-Prozess.',
                ],
                [
                    'title' => 'Theme & Redaktion',
                    'description' => 'Pixelgenaue Umsetzung der Artikel- und Übersichtsseiten (Anleger, Geldanlage) für das redaktionelle Finanzmedium.',
                ],
                [
                    'title' => 'Konferenz-Datenmodell',
                    'description' => 'Entwicklung der Custom Post Types für Konferenz, Speaker und Sponsoren inklusive individueller Felder und Relationships.',
                ],
                [
                    'title' => 'Konferenz-Darstellung',
                    'description' => 'Aufbau der barrierefreien Tab-Ansicht mit Programm/Agenda, Anmeldung, Informationen und Sponsorship.',
                ],
                [
                    'title' => 'Integrationen',
                    'description' => 'Anbindung von Registrierung und Sponsorship über WS Form sowie des Newsletters an Mailchimp.',
                ],
            ],

            'cta' => [
                'title' => 'Sie planen eine Konferenz-, Event- oder Publishing-Website?',
                'subtitle' => 'Ob individuelles Theme nach Ihrer Designvorlage, eine barrierefreie Konferenzplattform mit Agenda und Registrierung oder Newsletter- und CRM-Integrationen — wir setzen anspruchsvolle WordPress-Projekte pixelgenau und DSGVO-konform um. Lassen Sie uns unverbindlich darüber sprechen.',
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
            'icon' => 'calendar',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'portfolio vermögensmanagement — Custom-WordPress-Theme & Konferenzplattform',
            'client' => 'portfolio vermögensmanagement',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Individuelles WordPress-Theme pixelgenau nach Designvorlage für ein Finanzmedium — mit barrierefreier Konferenzplattform (Tabs, Agenda, Speaker & Sponsoren), Registrierung und Mailchimp-Newsletter.',
            'categories' => [
                'WordPress',
                'Custom-Theme',
                'Konferenzplattform',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Finanzmedium für vermögende Anleger braucht einen Auftritt exakt nach Designvorlage — und mehr als Artikel: eine vollwertige, barrierefreie Konferenz mit Programm, Anmeldung, Speakern und Sponsoren.',
                'items' => [
                    'Designvorlage pixelgenau als Theme umsetzen',
                    'Komplette Konferenz mit Agenda & Registrierung abbilden',
                    'Barrierefrei (WCAG) und DSGVO-konform',
                    'Redaktionell selbst pflegbar',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein individuelles WordPress-Theme auf Tailwind-Basis mit barrierefreier Konferenzplattform: Custom Post Types für Konferenz, Speaker und Sponsoren, Tab-Ansicht mit Agenda sowie Registrierung und Mailchimp-Newsletter.',
                'items' => [
                    'Individuelles Theme pixelgenau nach Designvorlage',
                    'Konferenz mit barrierefreien Tabs & Agenda',
                    'CPTs für Konferenz, Speaker & Sponsoren',
                    'Registrierung & Sponsorship über WS Form',
                    'Mailchimp-Newsletter-Integration',
                ],
            ],
            'features' => [
                [
                    'title' => 'Konferenzplattform',
                    'items' => [
                        'Barrierefreie Tabs: Programm, Anmelden, Info, Sponsorship',
                        'Agenda aus individuellen Feldern, Speaker & Sponsoren',
                    ],
                ],
                [
                    'title' => 'Integrationen',
                    'items' => [
                        'Registrierung & Sponsorship über WS Form',
                        'Mailchimp-Newsletter („Abonnieren")',
                    ],
                ],
            ],
            'results' => [
                'Designvorlage pixelgenau als individuelles Theme umgesetzt',
                'Vollwertige Konferenz mit Agenda, Registrierung & Sponsoren',
                'Barrierefrei (WCAG) und DSGVO-konform gebaut',
                'Newsletter direkt an Mailchimp angebunden',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'Design: Custom-Theme (Tailwind CSS)',
                'Konferenz: Custom Post Types + individuelle Felder',
                'Formulare: WS Form Pro',
                'Newsletter: Mailchimp-Integration',
            ],
        ];
    }
};
