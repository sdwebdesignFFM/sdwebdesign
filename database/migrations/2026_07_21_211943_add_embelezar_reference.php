<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the Embelezar Kosmetikinstitut reference as the last portfolio project.
 *
 * Embelezar Kosmetikinstitut (owner Deise Fasselt) in Frankfurt-Niederursel
 * offers facial treatments, anti-aging, microneedling, permanent make-up and
 * waxing. For Embelezar we built a fast Next.js website (on Vercel) and manage
 * it comprehensively: many SEO landing pages (per treatment and per Frankfurt
 * district), a custom online booking tool with deposit, a gift-voucher shop
 * (PDF vouchers, Klarna) and ongoing SEO and Google Ads management.
 *
 * Same mechanics as the previous reference migrations: creates a
 * TYPE_REFERENCE_DETAIL page and appends the matching entry to the /referenzen
 * overview as the last project. Idempotent, no-op when the overview page is
 * missing, defensive against non-array content.
 */
return new class extends Migration
{
    private const SLUG_DE = 'embelezar-kosmetikinstitut-nextjs-seo';

    private const SLUG_EN = 'embelezar-kosmetikinstitut-nextjs-seo';

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
        $page->setTranslation('title', 'de', 'Embelezar Kosmetikinstitut — Next.js-Plattform mit SEO- & Google-Ads-Betreuung');
        $page->setTranslation('title', 'en', 'Embelezar Kosmetikinstitut — Next.js Platform with SEO & Google Ads Management');
        $page->setTranslation('meta_title', 'de', 'Embelezar — Next.js, SEO, Google Ads & Buchung');
        $page->setTranslation('meta_title', 'en', 'Embelezar — Next.js, SEO, Google Ads & Booking');
        $page->setTranslation('meta_description', 'de', 'Next.js-Website mit SEO- und Google-Ads-Betreuung für das Embelezar Kosmetikinstitut Frankfurt: Behandlungs- und Stadtteil-Landingpages, eigene Online-Terminbuchung und Gutschein-Shop.');
        $page->setTranslation('meta_description', 'en', 'Next.js website with SEO and Google Ads management for Embelezar Kosmetikinstitut Frankfurt: treatment and district landing pages, a custom online booking tool and a gift-voucher shop.');

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
                'category' => 'Next.js · SEO & Google Ads · Kosmetikinstitut',
                'tagline' => 'Next.js-Website, umfassende SEO- und Google-Ads-Betreuung für das Embelezar Kosmetikinstitut in Frankfurt (Inhaberin Deise Fasselt, 14+ Jahre Erfahrung). Eine schnelle, suchmaschinenstarke Plattform mit zahlreichen Behandlungs- und Stadtteil-Landingpages, einer eigenen Online-Terminbuchung mit Anzahlung und einem Gutschein-Shop — laufend betreut, damit aus Suchanfragen echte Termine werden.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'Embelezar Kosmetikinstitut'],
                ['label' => 'Branche', 'value' => 'Kosmetikinstitut · Frankfurt'],
                ['label' => 'Website', 'value' => 'embelezar-kosmetikinstitut.de', 'link' => 'https://embelezar-kosmetikinstitut.de/'],
                ['label' => 'Leistung', 'value' => 'Next.js-Website, SEO, Google Ads, Buchung & Gutscheine'],
                ['label' => 'Standort', 'value' => 'Frankfurt-Niederursel'],
                ['label' => 'Stack', 'value' => 'Next.js · Vercel · SEO · Google Ads'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'Das Embelezar Kosmetikinstitut in Frankfurt-Niederursel (Inhaberin Deise Fasselt, zertifizierte Kosmetikerin mit über 14 Jahren Erfahrung) bietet Gesichtsbehandlungen, Anti-Aging, Microneedling, Permanent Make-Up und Waxing. Für Embelezar haben wir eine moderne, schnelle Website auf Next.js (Vercel) gebaut und betreuen sie umfassend: mit zahlreichen SEO-optimierten Landingpages (je Behandlung und je Frankfurter Stadtteil), einer eigenen Online-Terminbuchung inklusive Anzahlung, einem Gutschein-Shop (PDF-Gutscheine, Zahlung u.a. per Klarna) und einer laufenden SEO- und Google-Ads-Betreuung. Das Ergebnis: ein Auftritt mit 4,9★ und starker lokaler Sichtbarkeit, der planbar Termine bringt.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Kosmetikinstitut in Frankfurt steht im lokalen Wettbewerb um sichtbare Suchbegriffe rund um viele Behandlungen und Stadtteile. Gefragt war eine schnelle, suchmaschinenstarke Website mit eigenen Buchungs- und Gutschein-Funktionen — und eine laufende Betreuung, die aus Sichtbarkeit echte Termine macht.',
                'items' => [
                    'Schnelle, suchmaschinenstarke Website (Core Web Vitals)',
                    'Lokale Sichtbarkeit über viele Behandlungen & Stadtteile',
                    'Planbare Neukundengewinnung (SEO + Google Ads)',
                    'Eigene Online-Terminbuchung mit Anzahlung',
                    'Gutscheinverkauf (Geschenkgutscheine) online',
                    'Laufende Betreuung von Inhalten und Kampagnen',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine moderne Next.js-Website auf Vercel — schnell, SEO-stark und mobil optimiert. Für die organische Reichweite gibt es zahlreiche Landingpages je Behandlung und je Frankfurter Stadtteil. Buchung und Gutscheinkauf laufen ohne externe Tools direkt auf der Seite: eine eigene Online-Terminbuchung mit Anzahlung und ein Gutschein-Shop mit PDF-Ausstellung. Dazu kommt die laufende SEO- und Google-Ads-Betreuung mit Fokus auf Terminanfragen.',
                'items' => [
                    'Moderne Next.js-Website auf Vercel (schnell & SEO-stark)',
                    'Zahlreiche Landingpages je Behandlung und Stadtteil',
                    'Eigene Online-Terminbuchung mit Anzahlung',
                    'Gutschein-Shop: PDF-Gutscheine, Zahlung per Karte & Klarna',
                    'Laufende SEO- und Google-Ads-Betreuung',
                    'Fokus auf Conversion: aus Suche werden Termine',
                ],
            ],

            'tech_stack' => [
                'Next.js · React · Frontend & SSR',
                'Vercel · Hosting & Edge',
                'SEO-Landingpages · Behandlungen & Stadtteile',
                'Online-Terminbuchung · mit Anzahlung',
                'Gutschein-Shop · PDF & Klarna',
                'Google Ads · laufende SEA-Betreuung',
                'Lokale SEO · Frankfurt',
                'Responsive Design · optimierte Core Web Vitals',
            ],

            'features' => [
                [
                    'title' => 'Next.js-Website & Design',
                    'image' => '/images/references/embelezar/website.png',
                    'description' => 'Eine schnelle, moderne Website auf Next.js/Vercel mit elegantem Design — SEO-stark, mobil optimiert und auf Vertrauen ausgelegt (4,9★, echte Bewertungen und klare Preise prominent platziert).',
                    'items' => [
                        'Next.js auf Vercel (schnell & SEO-stark)',
                        'Elegantes, vertrauensbildendes Design',
                        'Bewertungen & Preise prominent',
                        'Optimierte Core Web Vitals',
                    ],
                ],
                [
                    'title' => 'SEO-Landingpages',
                    'image' => '/images/references/embelezar/landingpage.png',
                    'description' => 'Für jede Behandlung und jeden Frankfurter Stadtteil eine eigene, SEO-optimierte Landingpage — von der Aknebehandlung bis Microneedling, von Höchst bis Riedberg. So wird Embelezar für viele Suchanfragen gefunden — organisch und als Ziel für Google Ads.',
                    'items' => [
                        'Landingpage je Behandlung (Akne, Anti-Aging, Microneedling …)',
                        'Stadtteil-Landingpages (Höchst, Riedberg, Kalbach …)',
                        'Conversion-optimiert (Before/After, Bewertungen, Preis)',
                        'Auch als Ziel für Google-Ads-Kampagnen',
                    ],
                ],
                [
                    'title' => 'Online-Terminbuchung',
                    'image' => '/images/references/embelezar/terminbuchung.png',
                    'description' => 'Eine eigene Online-Terminbuchung direkt auf der Website: Behandlung wählen, freie Zeiten in Echtzeit sehen und mit Anzahlung verbindlich buchen — in wenigen Minuten bestätigt, ohne externes System.',
                    'items' => [
                        'Eigenes Buchungstool (kein externes System)',
                        'Freie Zeiten in Echtzeit',
                        'Verbindlich mit Anzahlung',
                        'Geführter 4-Schritt-Prozess',
                    ],
                ],
                [
                    'title' => 'Gutschein-Shop',
                    'image' => '/images/references/embelezar/gutscheine.png',
                    'description' => 'Geschenkgutscheine online kaufen: Behandlung oder Wertgutschein wählen, personalisieren und sofort als PDF erhalten — mit sicherer Zahlung (Karte, Klarna) und drei Jahren Gültigkeit.',
                    'items' => [
                        'Gutscheinkauf mit Behandlungs- & Wertgutscheinen',
                        'Personalisiert, sofort als PDF',
                        'Zahlung per Karte & Klarna',
                        'Drei Jahre gültig',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Next.js auf Vercel',
                    'description' => 'Eine moderne, serverseitig gerenderte Next.js-Website auf Vercel — schnell, SEO-freundlich und skalierbar, mit optimierten Core Web Vitals.',
                    'items' => [
                        'Next.js · React · SSR',
                        'Vercel · Edge-Hosting',
                        'Optimierte Ladezeiten (CWV)',
                        'Skalierbare Seitenstruktur',
                    ],
                ],
                [
                    'icon' => 'magnifying-glass',
                    'title' => 'SEO & Google Ads',
                    'description' => 'Umfassende, laufende Betreuung: technische und inhaltliche SEO mit vielen Landingpages sowie aktiv gemanagte Google-Ads-Kampagnen — mit Fokus auf Terminanfragen.',
                    'items' => [
                        'Landingpage-SEO je Behandlung & Stadtteil',
                        'Technische SEO & Core Web Vitals',
                        'Google-Ads-Kampagnen (SEA)',
                        'Laufende Optimierung & Reporting',
                    ],
                ],
                [
                    'icon' => 'calendar',
                    'title' => 'Buchung & Gutscheine',
                    'description' => 'Zwei conversionstarke Funktionen, komplett selbst gebaut: eine Online-Terminbuchung mit Anzahlung und ein Gutschein-Shop mit PDF-Ausstellung und sicherer Bezahlung.',
                    'items' => [
                        'Online-Terminbuchung mit Anzahlung',
                        'Gutschein-Shop mit PDF-Ausstellung',
                        'Zahlung per Karte & Klarna',
                        'Alles ohne externe Tools',
                    ],
                ],
            ],

            'impact_results' => [
                'Schnelle, SEO-starke Next.js-Website (4,9★, starke lokale Sichtbarkeit)',
                'Zahlreiche Landingpages je Behandlung und Stadtteil für organische Reichweite',
                'Eigene Online-Terminbuchung mit Anzahlung statt externem Tool',
                'Gutschein-Shop mit PDF-Ausstellung und Klarna-Zahlung',
                'Laufende SEO- und Google-Ads-Betreuung bringt planbar Termine',
            ],

            'results' => [
                ['value' => '4,9★', 'label' => '62+ Google-Bewertungen'],
                ['value' => 'Next.js', 'label' => 'schnell & SEO-stark (Vercel)'],
                ['value' => 'SEO + Ads', 'label' => 'laufende Betreuung'],
                ['value' => '2', 'label' => 'eigene Tools (Buchung & Gutscheine)'],
            ],

            'technologies' => [
                'Next.js',
                'React',
                'Vercel',
                'SEO',
                'Google Ads',
                'Online-Terminbuchung',
                'Gutschein-Shop',
                'Klarna',
                'Lokale SEO',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Next.js-Website',
                    'description' => 'Aufbau der modernen Website auf Next.js/Vercel mit elegantem, vertrauensbildendem Design.',
                ],
                [
                    'title' => 'SEO-Landingpages',
                    'description' => 'Erstellung zahlreicher Landingpages je Behandlung und je Frankfurter Stadtteil.',
                ],
                [
                    'title' => 'Terminbuchung & Gutscheine',
                    'description' => 'Entwicklung der eigenen Online-Terminbuchung (mit Anzahlung) und des Gutschein-Shops.',
                ],
                [
                    'title' => 'Google Ads',
                    'description' => 'Setup und laufende Betreuung der Google-Ads-Kampagnen mit Fokus auf Terminanfragen.',
                ],
                [
                    'title' => 'Laufende Betreuung',
                    'description' => 'Kontinuierliche SEO-, Content- und Kampagnen-Optimierung.',
                ],
            ],

            'cta' => [
                'title' => 'Sie möchten lokal gefunden werden und mehr Termine gewinnen?',
                'subtitle' => 'Ob schnelle Next.js-Website, SEO-Landingpages, Online-Terminbuchung oder Google-Ads-Betreuung — wir bauen und betreuen Auftritte, die lokal sichtbar sind und planbar Termine bringen. Lassen Sie uns unverbindlich darüber sprechen.',
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
            'icon' => 'sparkles',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'Embelezar Kosmetikinstitut — Next.js-Plattform mit SEO- & Google-Ads-Betreuung',
            'client' => 'Embelezar Kosmetikinstitut',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Schnelle Next.js-Website mit umfassender SEO- und Google-Ads-Betreuung für ein Frankfurter Kosmetikinstitut — inklusive Behandlungs- und Stadtteil-Landingpages, eigener Online-Terminbuchung mit Anzahlung und einem Gutschein-Shop.',
            'categories' => [
                'Next.js',
                'SEO & Google Ads',
                'Kosmetikinstitut',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Kosmetikinstitut in Frankfurt braucht eine schnelle, suchmaschinenstarke Website mit eigenen Buchungs- und Gutschein-Funktionen — und eine laufende Betreuung, die aus Sichtbarkeit Termine macht.',
                'items' => [
                    'Schnelle, SEO-starke Website (Core Web Vitals)',
                    'Sichtbarkeit über viele Behandlungen & Stadtteile',
                    'Eigene Online-Terminbuchung & Gutscheinkauf',
                    'Planbare Neukundengewinnung',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine Next.js-Website auf Vercel mit vielen SEO-Landingpages, eigener Online-Terminbuchung (mit Anzahlung) und Gutschein-Shop — dazu laufende SEO- und Google-Ads-Betreuung.',
                'items' => [
                    'Next.js-Website auf Vercel (schnell & SEO-stark)',
                    'Landingpages je Behandlung & Stadtteil',
                    'Eigene Online-Terminbuchung mit Anzahlung',
                    'Gutschein-Shop (PDF, Klarna)',
                    'Laufende SEO- und Google-Ads-Betreuung',
                ],
            ],
            'features' => [
                [
                    'title' => 'Website & Landingpages',
                    'items' => [
                        'Next.js-Website auf Vercel',
                        'SEO-Landingpages je Behandlung & Stadtteil',
                    ],
                ],
                [
                    'title' => 'Buchung, Gutscheine & Ads',
                    'items' => [
                        'Eigene Online-Terminbuchung & Gutschein-Shop',
                        'Laufende SEO- und Google-Ads-Betreuung',
                    ],
                ],
            ],
            'results' => [
                'Schnelle, SEO-starke Next.js-Website (4,9★)',
                'Zahlreiche Landingpages je Behandlung & Stadtteil',
                'Eigene Online-Terminbuchung mit Anzahlung',
                'Gutschein-Shop und laufende Google-Ads-Betreuung',
            ],
            'tech_stack' => [
                'Frontend: Next.js · React',
                'Hosting: Vercel',
                'SEO: Landingpages & lokale Optimierung',
                'SEA: Google-Ads-Kampagnen',
                'Funktionen: Terminbuchung & Gutschein-Shop',
            ],
        ];
    }
};
