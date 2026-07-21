<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the Willem Fasselt reference as the last portfolio project.
 *
 * Willem Fasselt is a Heilpraktiker für Psychotherapie / Gestalttherapeut in
 * Köln-Mülheim (Gestalttherapie & NARM, trauma-sensitive and body-oriented).
 * For his practice we designed and built the website: a warm, trust-building
 * WordPress presence on the Genesis framework (custom child theme + Beaver
 * Builder), presenting the person, the approach and the therapy offers, with
 * SEO-optimized pages and DSGVO compliance.
 *
 * Same mechanics as the previous reference migrations: creates a
 * TYPE_REFERENCE_DETAIL page and appends the matching entry to the /referenzen
 * overview as the last project. Idempotent, no-op when the overview page is
 * missing, defensive against non-array content.
 */
return new class extends Migration
{
    private const SLUG_DE = 'willem-fasselt-gestalttherapeut-koeln';

    private const SLUG_EN = 'willem-fasselt-gestalttherapeut-koeln';

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
        $page->setTranslation('title', 'de', 'Willem Fasselt — Website für einen Gestalttherapeuten in Köln');
        $page->setTranslation('title', 'en', 'Willem Fasselt — Website for a Gestalt Therapist in Cologne');
        $page->setTranslation('meta_title', 'de', 'Willem Fasselt — Website für einen Gestalttherapeuten');
        $page->setTranslation('meta_title', 'en', 'Willem Fasselt — Website for a Gestalt Therapist');
        $page->setTranslation('meta_description', 'de', 'Warme, vertrauensbildende WordPress-Website (Genesis & Beaver Builder) für den Gestalttherapeuten Willem Fasselt in Köln-Mülheim — mit Person, Arbeitsweise, Therapie-Angeboten und Kontakt.');
        $page->setTranslation('meta_description', 'en', 'A warm, trust-building WordPress website (Genesis & Beaver Builder) for Gestalt therapist Willem Fasselt in Cologne — with the person, approach, therapy offers and contact.');

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
                'category' => 'WordPress · Genesis · Psychotherapie-Praxis',
                'tagline' => 'Design und Entwicklung der Website für Willem Fasselt — Heilpraktiker für Psychotherapie und Gestalttherapeut in Köln-Mülheim (Gestalttherapie & NARM). Ein warmer, vertrauensbildender WordPress-Auftritt auf Basis des Genesis-Frameworks, der die Person, die Arbeitsweise und die Therapie-Angebote klar und einfühlsam präsentiert — und Suchende sensibel zum ersten Kontakt führt.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'Willem Fasselt'],
                ['label' => 'Branche', 'value' => 'Psychotherapie · Gestalttherapie'],
                ['label' => 'Website', 'value' => 'gestalttherapeut-fasselt.de', 'link' => 'https://gestalttherapeut-fasselt.de/'],
                ['label' => 'Leistung', 'value' => 'Design & Website-Entwicklung'],
                ['label' => 'Standort', 'value' => 'Köln-Mülheim'],
                ['label' => 'Stack', 'value' => 'WordPress · Genesis · Beaver Builder'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'Willem Fasselt ist Heilpraktiker für Psychotherapie und Gestalttherapeut (DVG) mit eigener Praxis in Köln-Mülheim seit 2013 — mit Schwerpunkt Gestalttherapie und NARM (traumasensibel und körperorientiert). Für seine Praxis haben wir die Website gestaltet und entwickelt: einen warmen, vertrauensbildenden WordPress-Auftritt auf Basis des Genesis-Frameworks (individuelles Child-Theme mit Beaver Builder). Die Seite stellt die Person und ihre Arbeitsweise vor, erklärt die Therapie-Angebote (Gestalttherapie, NARM-Traumatherapie, Einzel-, Paar- und Gruppentherapie sowie Beratung) verständlich und führt Suchende einfühlsam zur Kontaktaufnahme — SEO-optimiert und DSGVO-konform.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Psychotherapie ist ein sensibles, sehr persönliches Thema. Der Web-Auftritt muss von der ersten Sekunde an Vertrauen und Ruhe ausstrahlen, die Person nahbar machen und die verschiedenen Therapie-Angebote verständlich erklären — lokal auffindbar und datenschutzkonform.',
                'items' => [
                    'Vertrauensbildender, einfühlsamer Auftritt für ein sensibles Thema',
                    'Person und Arbeitsweise nahbar präsentieren',
                    'Verschiedene Therapie-Angebote verständlich erklären',
                    'Lokale Auffindbarkeit in Köln',
                    'DSGVO-Konformität in einem sensiblen Bereich',
                    'Einfache, klare Kontaktaufnahme',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein warmer, vertrauensbildender WordPress-Auftritt auf Basis des Genesis-Frameworks mit individuellem Child-Theme und Beaver Builder. „Zur Person" und „Arbeitsweise" machen Willem Fasselt nahbar, eigene Seiten je Therapie-Angebot erklären die Verfahren verständlich, und eine klare Kontaktseite führt zum ersten Gespräch. SEO-optimiert (Yoast) und DSGVO-konform.',
                'items' => [
                    'Warmer, vertrauensbildender Auftritt (Genesis + Beaver Builder)',
                    '„Zur Person" und „Arbeitsweise" nahbar aufbereitet',
                    'Eigene Seiten je Angebot (Gestalttherapie, NARM, Einzel/Paar/Gruppe, Beratung)',
                    'SEO-optimiert (Yoast) für lokale Sichtbarkeit in Köln',
                    'DSGVO-konform (Cookie-Consent, lokale Webfonts)',
                    'Klare Kontaktseite mit Adresse und Telefon',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Website',
                'Genesis-Framework · robustes Fundament',
                'Individuelles Child-Theme · eigenes Design',
                'Beaver Builder · flexible Seitengestaltung',
                'Meta Box · individuelle Felder',
                'Yoast SEO · Suchmaschinenoptimierung',
                'Cookie-Consent & lokale Webfonts · DSGVO',
                'Responsive Design · für alle Geräte',
            ],

            'features' => [
                [
                    'title' => 'Website & Design',
                    'image' => '/images/references/willem-fasselt/website.png',
                    'description' => 'Ein warmer, ruhiger Auftritt mit ausdrucksstarker Aquarell-Bildsprache — vertrauensbildend und einfühlsam, passend zu einem sensiblen Thema. Umgesetzt als individuelles Child-Theme auf dem Genesis-Framework.',
                    'items' => [
                        'Individuelles Design auf Genesis-Basis',
                        'Warme, ausdrucksstarke Aquarell-Bildsprache',
                        'Ruhig, vertrauensbildend und nahbar',
                        'Voll responsiv für alle Geräte',
                    ],
                ],
                [
                    'title' => 'Zur Person & Arbeitsweise',
                    'image' => '/images/references/willem-fasselt/person.png',
                    'description' => 'Vertrauen entsteht über die Person: Vita, Qualifikationen und Arbeitsweise von Willem Fasselt werden nahbar aufbereitet — vom Gestalttherapeuten (DVG) über den NARM-Therapeuten bis zum Heilpraktiker für Psychotherapie.',
                    'items' => [
                        'Vorstellung der Person (Vita & Qualifikationen)',
                        'Arbeitsweise verständlich und nahbar erklärt',
                        'Gestalttherapeut (DVG) & NARM-Therapeut',
                        'Nähe und Vertrauen von der ersten Seite an',
                    ],
                ],
                [
                    'title' => 'Therapie-Angebote',
                    'image' => '/images/references/willem-fasselt/therapie.png',
                    'description' => 'Jedes Verfahren bekommt eine eigene, verständlich geschriebene Seite — Gestalttherapie, NARM-Traumatherapie, Einzel-, Paar- und Gruppentherapie sowie Beratung. Das schafft Klarheit für Suchende und Sichtbarkeit in der lokalen Suche.',
                    'items' => [
                        'Eigene Seite je Angebot (Gestalttherapie, NARM …)',
                        'Einzel-, Paar- & Gruppentherapie sowie Beratung',
                        'Verständlich und einfühlsam erklärt',
                        'SEO-optimiert für die lokale Suche in Köln',
                    ],
                ],
                [
                    'title' => 'Kontakt & DSGVO',
                    'image' => '/images/references/willem-fasselt/kontakt.png',
                    'description' => 'Eine klare Kontaktseite mit Adresse und Telefon führt zum ersten Gespräch. In einem sensiblen Bereich ist Datenschutz zentral: DSGVO-konforme Einwilligung, lokal ausgelieferte Webfonts und eine gehärtete, gesicherte Website.',
                    'items' => [
                        'Klare Kontaktseite (Adresse & Telefon)',
                        'Kontaktformular für unkomplizierte Anfragen',
                        'DSGVO-konform (Consent, lokale Webfonts)',
                        'Sicher gehärtet und regelmäßig gesichert',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Genesis & Beaver Builder',
                    'description' => 'Ein individuelles Child-Theme auf Basis des robusten Genesis-Frameworks, kombiniert mit dem Beaver Builder für flexible, pflegbare Seiten und Meta Box für individuelle Felder.',
                    'items' => [
                        'Genesis-Framework als Fundament',
                        'Individuelles Child-Theme',
                        'Beaver Builder für flexible Seiten',
                        'Meta Box für individuelle Felder',
                    ],
                ],
                [
                    'icon' => 'magnifying-glass',
                    'title' => 'SEO & lokale Sichtbarkeit',
                    'description' => 'Eigene, gut strukturierte Seiten je Therapie-Angebot und eine saubere Optimierung (Yoast) sorgen für Sichtbarkeit rund um Psychotherapie und Gestalttherapie in Köln.',
                    'items' => [
                        'Yoast SEO · saubere Meta-Daten',
                        'Eigene Seiten je Verfahren',
                        'Lokale Optimierung für Köln',
                        'Klare, suchfreundliche Struktur',
                    ],
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Sicherheit & DSGVO',
                    'description' => 'Ein sensibler Bereich verlangt Sorgfalt: DSGVO-konforme Einwilligung, lokal ausgelieferte Webfonts, Security-Härtung und regelmäßige Backups.',
                    'items' => [
                        'Cookie-Consent (DSGVO)',
                        'Lokal ausgelieferte Webfonts',
                        'Security-Härtung',
                        'Regelmäßige Backups',
                    ],
                ],
            ],

            'impact_results' => [
                'Warmer, vertrauensbildender Auftritt für ein sensibles Thema',
                'Person und Arbeitsweise nahbar präsentiert',
                'Therapie-Angebote verständlich und SEO-optimiert dargestellt',
                'Lokale Sichtbarkeit rund um Psychotherapie in Köln',
                'DSGVO-konform, sicher und zuverlässig',
            ],

            'results' => [
                ['value' => 'Genesis', 'label' => 'Robustes WordPress-Fundament'],
                ['value' => '7', 'label' => 'Therapie-Angebote als eigene Seiten'],
                ['value' => 'SEO', 'label' => 'SEO-optimiert (Yoast)'],
                ['value' => 'DSGVO', 'label' => 'konform gebaut'],
            ],

            'technologies' => [
                'WordPress',
                'Genesis-Framework',
                'Beaver Builder',
                'Meta Box',
                'Yoast SEO',
                'DSGVO',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Konzept & Design',
                    'description' => 'Gestaltung eines warmen, vertrauensbildenden Auftritts für ein sensibles Thema.',
                ],
                [
                    'title' => 'Genesis-Umsetzung',
                    'description' => 'Umsetzung als individuelles Child-Theme auf dem Genesis-Framework mit Beaver Builder.',
                ],
                [
                    'title' => 'Inhalte & Angebote',
                    'description' => 'Aufbau der Seiten zu Person, Arbeitsweise und den einzelnen Therapie-Angeboten.',
                ],
                [
                    'title' => 'SEO & DSGVO',
                    'description' => 'SEO-Optimierung (Yoast), DSGVO-Konformität und Security-Härtung.',
                ],
            ],

            'cta' => [
                'title' => 'Sie brauchen einen vertrauensbildenden Web-Auftritt für Ihre Praxis?',
                'subtitle' => 'Ob Therapie, Coaching oder Heilpraxis — wir gestalten und entwickeln Websites, die Vertrauen schaffen, Ihre Arbeit verständlich machen und Suchende einfühlsam zum Kontakt führen. Lassen Sie uns unverbindlich darüber sprechen.',
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
            'icon' => 'heart',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'Willem Fasselt — Website für einen Gestalttherapeuten in Köln',
            'client' => 'Willem Fasselt',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Warme, vertrauensbildende WordPress-Website (Genesis & Beaver Builder) für den Gestalttherapeuten und Heilpraktiker für Psychotherapie Willem Fasselt in Köln-Mülheim — mit Person, Arbeitsweise, Therapie-Angeboten und Kontakt.',
            'categories' => [
                'WordPress',
                'Genesis',
                'Psychotherapie',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Psychotherapie ist ein sensibles Thema — der Auftritt muss Vertrauen und Ruhe ausstrahlen, die Person nahbar machen und die Angebote verständlich erklären.',
                'items' => [
                    'Vertrauensbildender, einfühlsamer Auftritt',
                    'Person und Arbeitsweise nahbar präsentieren',
                    'Therapie-Angebote verständlich erklären',
                    'Lokale Auffindbarkeit & DSGVO',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein warmer WordPress-Auftritt auf Genesis-Basis mit Beaver Builder — mit Person, Arbeitsweise, eigenen Seiten je Therapie-Angebot und klarem Kontakt. SEO-optimiert und DSGVO-konform.',
                'items' => [
                    'Warmer Auftritt (Genesis + Beaver Builder)',
                    'Person, Arbeitsweise & Therapie-Angebote',
                    'SEO-optimiert (Yoast)',
                    'DSGVO-konform & sicher',
                ],
            ],
            'features' => [
                [
                    'title' => 'Website & Inhalte',
                    'items' => [
                        'Warmer, vertrauensbildender Genesis-Auftritt',
                        'Person, Arbeitsweise & Therapie-Angebote',
                    ],
                ],
                [
                    'title' => 'SEO & DSGVO',
                    'items' => [
                        'SEO-optimiert (Yoast) für Köln',
                        'DSGVO-konform, sicher gehärtet',
                    ],
                ],
            ],
            'results' => [
                'Warmer, vertrauensbildender Auftritt für ein sensibles Thema',
                'Person und Angebote nahbar und verständlich',
                'SEO-optimiert für lokale Sichtbarkeit in Köln',
                'DSGVO-konform und sicher',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'Fundament: Genesis-Framework',
                'Design: individuelles Child-Theme + Beaver Builder',
                'SEO: Yoast',
                'Datenschutz: DSGVO-konform',
            ],
        ];
    }
};
