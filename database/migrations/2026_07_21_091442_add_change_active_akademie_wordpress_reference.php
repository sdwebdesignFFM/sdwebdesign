<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the change active – AKADEMIE reference as the last portfolio project.
 *
 * change active – AKADEMIE is the naturopath school for psychotherapy
 * (Heilpraktiker Psychotherapie) run by Peter Reitz (M.Sc. Psychologe), with
 * 15+ years of training experience, teaching both in-person (Gelnhausen) and
 * online. For this client we designed and built the complete WordPress
 * platform on the Bricks Builder: a marketing site for customer acquisition
 * (training landing pages, consultation booking, ~25+ local SEO pages) and a
 * gated online course area (WP Courseware) behind a paywall (payments via
 * PayPal and credit card).
 *
 * The owner gave permission to name the school and its founder and to use
 * publicly visible facts (15+ years, Gelnhausen). No confidential figures.
 *
 * Same mechanics as the MTH migration: creates a TYPE_REFERENCE_DETAIL page
 * (slug `change-active-akademie-heilpraktikerschule`) and appends the matching
 * entry to the /referenzen overview as the last project. Idempotent, no-op
 * when the overview page is missing, defensive against non-array content.
 */
return new class extends Migration
{
    private const SLUG_DE = 'change-active-akademie-heilpraktikerschule';

    private const SLUG_EN = 'change-active-academy-naturopath-school';

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
        $page->setTranslation('title', 'de', 'change active – AKADEMIE — WordPress-Plattform für eine Heilpraktikerschule');
        $page->setTranslation('title', 'en', 'change active – AKADEMIE — WordPress Platform for a Naturopath School');
        $page->setTranslation('meta_title', 'de', 'change active – AKADEMIE — Heilpraktikerschule (WordPress)');
        $page->setTranslation('meta_title', 'en', 'change active – AKADEMIE — Naturopath School (WordPress)');
        $page->setTranslation('meta_description', 'de', 'WordPress-Plattform für die Heilpraktikerschule change active – AKADEMIE: individuelles Bricks-Design, Marketing-Landingpages zur Akquise und Online-Kursbereich mit Bezahlschranke.');
        $page->setTranslation('meta_description', 'en', 'WordPress platform for the naturopath school change active – AKADEMIE: custom Bricks design, marketing landing pages for lead generation and a gated online course area with a paywall.');

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
                'category' => 'WordPress · Bricks · Online-Ausbildung',
                'tagline' => 'Design, Entwicklung und Betreuung der WordPress-Plattform der change active – AKADEMIE — der Heilpraktikerschule für Psychotherapie von Peter Reitz mit über 15 Jahren Ausbildungserfahrung. Eine Plattform, die zwei Aufgaben verbindet: Marketing-Seite zur Kundenakquise (Präsenz in Gelnhausen und online) und geschützter Online-Kursbereich mit Bezahlschranke für die Ausbildung.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'change active – AKADEMIE'],
                ['label' => 'Branche', 'value' => 'Heilpraktiker-Psychotherapie-Ausbildung'],
                ['label' => 'Website', 'value' => 'heilpraktiker-psychotherapie-ausbildung.com', 'link' => 'https://heilpraktiker-psychotherapie-ausbildung.com/'],
                ['label' => 'Leistung', 'value' => 'Custom Design, Entwicklung & Kursplattform'],
                ['label' => 'Angebot', 'value' => 'Präsenz (Gelnhausen) + Online'],
                ['label' => 'Stack', 'value' => 'WordPress · Bricks · WP Courseware'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'Die change active – AKADEMIE ist die Heilpraktikerschule für Psychotherapie von Peter Reitz (M.Sc. Psychologe) mit über 15 Jahren Erfahrung in Aus- und Weiterbildung. Ausgebildet wird auf zwei Wegen: in festen Präsenz-Lerngruppen in Gelnhausen und flexibel als Online-Ausbildung. Für diesen Anbieter haben wir die komplette WordPress-Plattform gestaltet und entwickelt — mit einem individuellen Design auf Basis des Bricks Builders. Die Seite erfüllt zwei Aufgaben in einem: Sie gewinnt als Marketing-Plattform neue Interessenten (Ausbildungs-Landingpages, Beratungstermin-Buchung, lokale SEO-Seiten) und liefert als geschützter Online-Kursbereich hinter einer Bezahlschranke die eigentliche Ausbildung — mit Lernvideos, Kursen und Klausuren.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Eine Heilpraktikerschule mit zwei Geschäftsmodellen — Präsenz und online — braucht eine Website, die beides bedient: überzeugendes Marketing zur Kundengewinnung und eine verlässliche, geschützte Lernumgebung für zahlende Teilnehmer. Beides in einem stimmigen, vertrauenswürdigen Auftritt, den ein kleines Team pflegen kann.',
                'items' => [
                    'Zwei Ausbildungswege (Präsenz in Gelnhausen & Online) klar vermarkten',
                    'Interessenten gewinnen und zur Beratung bzw. Buchung führen',
                    'Regionale Sichtbarkeit im Rhein-Main-Gebiet und darüber hinaus',
                    'Bezahlte Online-Kurse hinter einer sicheren Bezahlschranke bereitstellen',
                    'Lernvideos, Kurse und Klausuren geschützt und strukturiert ausliefern',
                    'Ein individuelles, seriöses Design statt Baukasten-Optik',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine maßgeschneiderte WordPress-Plattform mit individuellem Design auf Basis des Bricks Builders, die Marketing und Online-Ausbildung vereint. Die öffentliche Seite gewinnt Interessenten über Ausbildungs-Landingpages, Beratungstermin-Buchung und zahlreiche lokale SEO-Seiten. Der geschützte Kursbereich liefert die Ausbildung über WP Courseware, abgesichert durch eine Bezahlschranke mit Zahlung per PayPal und Kreditkarte.',
                'items' => [
                    'Individuelles Design mit dem Bricks Builder — kein Baukasten',
                    'Marketing-Landingpages für Präsenz- und Online-Ausbildung',
                    'Beratungstermin-Buchung und Lead-Formulare (WS Form)',
                    '25+ lokale SEO-Landingpages für regionale Kundenakquise',
                    'Online-Kursbereich mit WP Courseware (Lernvideos, Kurse, Klausuren)',
                    'Bezahlschranke und Zahlungen mit PayPal und Kreditkarte',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Fundament',
                'Bricks Builder · individuelles Design',
                'BricksExtras & Automatic.css · Design-System',
                'WP Courseware · Online-Kurse (LMS)',
                'Bezahlschranke & Zahlungen · PayPal & Kreditkarte',
                'WS Form Pro · Lead- & Buchungsformulare',
                'Lokale SEO-Landingpages · Kundenakquise',
                'Responsive Design · für Präsenz & Online',
            ],

            'features' => [
                [
                    'title' => 'Individuelles Design mit Bricks',
                    'image' => '/images/references/change-active/design-home.png',
                    'description' => 'Statt Baukasten-Optik ein maßgeschneidertes Design auf Basis des Bricks Builders — warm, seriös und vertrauensbildend, passend zu einer Heilpraktikerschule. Ein Auftritt, der Präsenz- und Online-Ausbildung gleichermaßen repräsentiert.',
                    'items' => [
                        'Maßgeschneidertes Design mit dem Bricks Builder',
                        'Konsistentes Design-System (BricksExtras, Automatic.css)',
                        'Warme, vertrauensbildende Bildsprache',
                        'Voll responsiv für alle Geräte',
                    ],
                ],
                [
                    'title' => 'Marketing & Kundenakquise',
                    'image' => '/images/references/change-active/marketing.png',
                    'description' => 'Die öffentliche Seite ist auf Kundengewinnung ausgelegt: klare Ausbildungs-Landingpages für Präsenz und Online, Beratungstermin-Buchung und Lead-Formulare führen Interessenten gezielt zum ersten Kontakt.',
                    'items' => [
                        'Landingpages für Präsenz- und Online-Ausbildung',
                        'Beratungstermin-Buchung und Lead-Formulare (WS Form)',
                        'Klare Call-to-Actions zur Kontaktaufnahme',
                        'Blog und Hilfe-Themen als Vertrauens- und SEO-Anker',
                    ],
                ],
                [
                    'title' => 'Online-AKADEMIE & Online-Kurse',
                    'image' => '/images/references/change-active/online-akademie.png',
                    'description' => 'Die Online-AKADEMIE bündelt die zeit- und ortsunabhängige Ausbildung: hochwertige Online-Kurse und Workshops, in denen Teilnehmer in ihrem eigenen Tempo lernen — technisch getragen von WP Courseware.',
                    'items' => [
                        'Online-Kurse und Workshops mit WP Courseware',
                        'Lernen im eigenen Tempo, zeit- und ortsunabhängig',
                        'Strukturierte Kurse, Lektionen und Materialien',
                        'Nahtlos ins individuelle Design integriert',
                    ],
                ],
                [
                    'title' => 'Bezahlschranke & geschützter Kursbereich',
                    'image' => '/images/references/change-active/bezahlschranke.png',
                    'description' => 'Das Herzstück der Ausbildung: ein geschützter Kursbereich hinter einer Bezahlschranke. Zugänge und Zahlungen werden mit PayPal und Kreditkarte abgewickelt — nur zahlende Teilnehmer erhalten Zugriff auf Lernvideos, Kurse und Klausuren.',
                    'items' => [
                        'Geschützter Login- und Mitgliederbereich („Kurs Startseite")',
                        'Bezahlschranke und Zahlungen mit PayPal und Kreditkarte',
                        'Zugriff auf Lernvideos, Kurse und Klausuren nur für Teilnehmer',
                        'Sichere Auslieferung der geschützten Inhalte',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Custom Design mit Bricks',
                    'description' => 'Ein individuelles Theme auf Basis des Bricks Builders mit BricksExtras und Automatic.css — ein sauberes, wartbares Design-System statt Baukasten-Optik.',
                    'items' => [
                        'Bricks Builder · individuelles Theme',
                        'BricksExtras · erweiterte Komponenten',
                        'Automatic.css · konsistentes Design-System',
                        'Voll responsiv und performant',
                    ],
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Kursbereich & Bezahlschranke',
                    'description' => 'Der geschützte Lernbereich läuft über WP Courseware; Zugänge und Zahlungen werden mit PayPal und Kreditkarte abgewickelt — nur zahlende Teilnehmer sehen die Inhalte.',
                    'items' => [
                        'WP Courseware · Kurse, Lektionen, Klausuren',
                        'Bezahlschranke & Zahlungen mit PayPal und Kreditkarte',
                        'Geschützter Login- und Mitgliederbereich',
                        'Lernvideos und Materialien nur für Teilnehmer',
                    ],
                ],
                [
                    'icon' => 'megaphone',
                    'title' => 'Marketing & lokale SEO',
                    'description' => 'Zahlreiche lokale Landingpages und Hilfe-Themen-Inhalte machen die Schule regional und thematisch sichtbar und bringen kontinuierlich Interessenten.',
                    'items' => [
                        '25+ lokale SEO-Landingpages',
                        'Hilfe-Themen: Lexikon, Prüfungsfragen-Archiv, Gesundheitsämter',
                        'Beratungstermin-Buchung und Lead-Formulare',
                        'Blog für Reichweite und Vertrauen',
                    ],
                ],
            ],

            'impact_results' => [
                'Eine Plattform vereint Marketing-Akquise und geschützte Online-Ausbildung',
                'Zwei Ausbildungswege (Präsenz & Online) klar vermarktet und buchbar',
                'Bezahlte Online-Kurse sicher hinter einer Bezahlschranke ausgeliefert',
                'Regionale Sichtbarkeit durch 25+ lokale SEO-Landingpages',
                'Individuelles, vertrauensbildendes Design statt Baukasten-Optik',
            ],

            'results' => [
                ['value' => '15+', 'label' => 'Jahre Ausbildungserfahrung'],
                ['value' => '2', 'label' => 'Ausbildungswege (Präsenz & Online)'],
                ['value' => '25+', 'label' => 'Lokale SEO-Landingpages'],
                ['value' => '5', 'label' => 'Wochenenden je Präsenz-Ausbildung'],
            ],

            'technologies' => [
                'WordPress',
                'Bricks Builder',
                'WP Courseware',
                'PayPal & Kreditkarte',
                'WS Form',
                'BricksExtras',
                'Automatic.css',
                'Lokale SEO',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Konzept & Design',
                    'description' => 'Analyse der zwei Geschäftsmodelle (Präsenz & Online) und Gestaltung eines individuellen, vertrauensbildenden Designs mit dem Bricks Builder.',
                ],
                [
                    'title' => 'Marketing-Seite',
                    'description' => 'Aufbau der Ausbildungs-Landingpages, der Beratungstermin-Buchung und der Lead-Formulare für die Kundenakquise.',
                ],
                [
                    'title' => 'Lokale SEO',
                    'description' => 'Erstellung zahlreicher lokaler Landingpages und Hilfe-Themen-Inhalte für regionale und thematische Sichtbarkeit.',
                ],
                [
                    'title' => 'Online-Kursbereich',
                    'description' => 'Einrichtung des geschützten Lernbereichs mit WP Courseware und der Bezahlschranke mit Zahlung per PayPal und Kreditkarte.',
                ],
                [
                    'title' => 'Betrieb & Weiterentwicklung',
                    'description' => 'Laufende Betreuung, Updates und kontinuierliche Erweiterung von Kursen und Inhalten.',
                ],
            ],

            'cta' => [
                'title' => 'Sie bilden aus, coachen oder verkaufen Online-Kurse?',
                'subtitle' => 'Ob Marketing-Seite zur Kundengewinnung, lokale SEO-Reichweite oder ein geschützter Online-Kursbereich mit Bezahlschranke — wir bauen WordPress-Plattformen, die Interessenten gewinnen und Ihre Inhalte sicher ausliefern. Lassen Sie uns unverbindlich darüber sprechen.',
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
            'icon' => 'book-open',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'change active – AKADEMIE — WordPress-Plattform für eine Heilpraktikerschule',
            'client' => 'change active – AKADEMIE (Peter Reitz)',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'WordPress-Plattform mit individuellem Bricks-Design für eine Heilpraktikerschule für Psychotherapie — Marketing-Seite zur Kundenakquise (Präsenz in Gelnhausen & online) und geschützter Online-Kursbereich mit Bezahlschranke.',
            'categories' => [
                'WordPress',
                'Bricks',
                'Online-Ausbildung',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Eine Heilpraktikerschule mit zwei Geschäftsmodellen — Präsenz und online — braucht überzeugendes Marketing zur Kundengewinnung und zugleich eine geschützte Lernumgebung für zahlende Teilnehmer.',
                'items' => [
                    'Zwei Ausbildungswege (Präsenz & Online) klar vermarkten',
                    'Interessenten gewinnen und zur Buchung führen',
                    'Bezahlte Online-Kurse hinter einer Bezahlschranke ausliefern',
                    'Individuelles, seriöses Design statt Baukasten-Optik',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine maßgeschneiderte WordPress-Plattform mit Bricks-Design, die Marketing und Online-Ausbildung vereint — inklusive lokaler SEO-Seiten und geschütztem Kursbereich mit Bezahlschranke.',
                'items' => [
                    'Individuelles Design mit dem Bricks Builder',
                    'Marketing-Landingpages & Beratungstermin-Buchung',
                    '25+ lokale SEO-Landingpages zur Kundenakquise',
                    'Online-Kursbereich mit WP Courseware',
                    'Bezahlschranke mit PayPal und Kreditkarte',
                ],
            ],
            'features' => [
                [
                    'title' => 'Marketing & Akquise',
                    'items' => [
                        'Landingpages für Präsenz- und Online-Ausbildung',
                        '25+ lokale SEO-Seiten & Beratungstermin-Buchung',
                    ],
                ],
                [
                    'title' => 'Online-Kurse & Bezahlschranke',
                    'items' => [
                        'Geschützter Kursbereich mit WP Courseware',
                        'Bezahlschranke & Zahlungen mit PayPal und Kreditkarte',
                    ],
                ],
            ],
            'results' => [
                'Marketing-Akquise und geschützte Online-Ausbildung in einer Plattform',
                'Zwei Ausbildungswege klar vermarktet und buchbar',
                'Regionale Reichweite durch 25+ lokale SEO-Landingpages',
                'Bezahlte Kurse sicher hinter einer Bezahlschranke',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'Design: Bricks Builder (BricksExtras, Automatic.css)',
                'Kurse: WP Courseware (LMS)',
                'Bezahlschranke: PayPal & Kreditkarte',
                'Formulare: WS Form Pro',
            ],
        ];
    }
};
