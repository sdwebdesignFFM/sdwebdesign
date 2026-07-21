<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the PureHeat-Online reference as the last portfolio project.
 *
 * PureHeat-Online is an exclusive B2B online shop for SHK professionals
 * (plumbing, heating, air conditioning) selling heating-water and drinking-water
 * treatment products. For this shop we implemented a designer's template as a
 * custom WordPress theme (Bricks Builder), built a full WooCommerce shop, and
 * added a B2B access layer where prices and ordering are only visible after
 * registration and manual activation. It also includes marketing campaigns:
 * a video raffle (Gewinnspiel) and a newsletter.
 *
 * Same mechanics as the previous WordPress reference migrations: creates a
 * TYPE_REFERENCE_DETAIL page and appends the matching entry to the /referenzen
 * overview as the last project. Idempotent, no-op when the overview page is
 * missing, defensive against non-array content.
 */
return new class extends Migration
{
    private const SLUG_DE = 'pureheat-online-woocommerce-b2b-shop';

    private const SLUG_EN = 'pureheat-online-woocommerce-b2b-shop';

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
        $page->setTranslation('title', 'de', 'PureHeat-Online — WooCommerce-B2B-Shop für Wasseraufbereitung');
        $page->setTranslation('title', 'en', 'PureHeat-Online — WooCommerce B2B Shop for Water Treatment');
        $page->setTranslation('meta_title', 'de', 'PureHeat-Online — WooCommerce-B2B-Shop (Custom-Theme)');
        $page->setTranslation('meta_title', 'en', 'PureHeat-Online — WooCommerce B2B Shop (Custom Theme)');
        $page->setTranslation('meta_description', 'de', 'Custom-WooCommerce-B2B-Shop nach Designvorlage für PureHeat-Online: Preise nur für freigeschaltete SHK-Profis, Registrierung mit Freischaltung, Gewinnspiel und Newsletter.');
        $page->setTranslation('meta_description', 'en', 'Custom WooCommerce B2B shop built to a design template for PureHeat-Online: prices only for activated trade customers, a registration & activation flow, plus a raffle and newsletter.');

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
                'category' => 'WordPress · WooCommerce · B2B-Shop',
                'tagline' => 'Design-Umsetzung und Entwicklung des B2B-Onlineshops von PureHeat-Online — dem exklusiven Shop für SHK-Profis rund um Heizungswasser- und Trinkwasseraufbereitung. Ein individuelles WordPress-Theme nach Designvorlage mit WooCommerce, geschütztem Kundenbereich (Preise nur für freigeschaltete Kunden) und umgesetzten Marketingaktionen — von Gewinnspiel bis Newsletter.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'PureHeat-Online'],
                ['label' => 'Branche', 'value' => 'B2B-Handel · Wasseraufbereitung (SHK)'],
                ['label' => 'Website', 'value' => 'pureheat-online.de', 'link' => 'https://pureheat-online.de/'],
                ['label' => 'Leistung', 'value' => 'Custom-Theme, WooCommerce & B2B-Zugang'],
                ['label' => 'Zielgruppe', 'value' => 'SHK-Profis (B2B)'],
                ['label' => 'Stack', 'value' => 'WordPress · Bricks · WooCommerce'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'PureHeat-Online ist der exklusive B2B-Onlineshop für SHK-Profis (Sanitär, Heizung, Klima) rund um Heizungswasser- und Trinkwasseraufbereitung — von Befüllstationen über Korrosionsschutz und Messgeräte bis zu Enthärtungs- und Osmoseanlagen. Für diesen Shop haben wir ein individuelles WordPress-Theme nach Designvorlage umgesetzt (Bricks Builder) und einen vollwertigen WooCommerce-Shop aufgebaut. Das Besondere: ein B2B-Zugang, bei dem Preise und Bestellfunktion erst nach Registrierung und Freischaltung sichtbar sind — passend zum reinen Fachhandels-Modell. Ergänzt um umgesetzte Marketingaktionen wie ein Video-Gewinnspiel und den Newsletterversand.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein reiner Fachhandel für SHK-Profis braucht mehr als einen Standard-Shop: Preise dürfen nur registrierten, geprüften Gewerbekunden angezeigt werden, der Auftritt muss die Designvorlage exakt umsetzen, und Marketingaktionen sollen Reichweite und Kundenbindung schaffen.',
                'items' => [
                    'Designvorlage pixelgenau als individuelles Theme umsetzen',
                    'Vollwertiger WooCommerce-Shop mit vielen Produktkategorien',
                    'Preise nur für registrierte & freigeschaltete Fachkunden sichtbar',
                    'Geführter Registrierungs- und Freischaltungsprozess (B2B)',
                    'Marketingaktionen umsetzen: Gewinnspiel, Newsletter, Katalog',
                    'Zahlungsarten (PayPal, Kreditkarte) und DSGVO-Konformität',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein individuelles WordPress-Theme auf Basis des Bricks Builders, das die Designvorlage pixelgenau umsetzt, kombiniert mit WooCommerce als Shop-Fundament. Der B2B-Zugang blendet Preise und Bestellfunktion für nicht eingeloggte Besucher aus — erst nach Registrierung und Freischaltung (Prüfung des Gewerbes) sehen Kunden alle Preise und können bestellen. Dazu kommen die umgesetzten Marketingaktionen: ein Video-Gewinnspiel, der Newsletterversand und die Katalog-Anforderung.',
                'items' => [
                    'Individuelles Theme nach Designvorlage (Bricks Builder)',
                    'WooCommerce-Shop mit Heizungs- & Trinkwasser-Kategorien',
                    'B2B-Zugang: Preise nur für freigeschaltete Kunden',
                    'Geführter 3-Schritt-Prozess: Registrieren → Freischaltung → Einkaufen',
                    'Marketing: Video-Gewinnspiel & Newsletterversand',
                    'Zahlung über PayPal und Kreditkarte (Stripe)',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Fundament',
                'Bricks Builder · individuelles Theme nach Designvorlage',
                'WooCommerce · B2B-Shop',
                'Geschützter B2B-Zugang · Registrierung & Freischaltung',
                'Zahlungen · PayPal & Kreditkarte (Stripe)',
                'Newsletter · Versand an Kunden',
                'Borlabs Cookie · DSGVO-konforme Einwilligung',
                'Responsive Design · für alle Geräte',
            ],

            'features' => [
                [
                    'title' => 'Custom-Theme nach Designvorlage',
                    'image' => '/images/references/pureheat/design-home.png',
                    'description' => 'Die Designvorlage haben wir pixelgenau in ein individuelles WordPress-Theme übersetzt — umgesetzt mit dem Bricks Builder. Ein klarer, technischer Auftritt mit hochwertiger Produktfotografie, passend zur Fachhandels-Zielgruppe.',
                    'items' => [
                        'Designvorlage pixelgenau umgesetzt (Bricks Builder)',
                        'Technischer, hochwertiger Shop-Auftritt',
                        'Hero-Slider & Produktwelten',
                        'Voll responsiv für Desktop und mobil',
                    ],
                ],
                [
                    'title' => 'WooCommerce B2B-Shop',
                    'image' => '/images/references/pureheat/shop-kategorie.png',
                    'description' => 'Ein vollwertiger WooCommerce-Shop mit den Produktwelten Heizungswasser- und Trinkwasseraufbereitung — von Befüllstationen über Messgeräte bis zu Enthärtungs- und Osmoseanlagen, klar kategorisiert.',
                    'items' => [
                        'WooCommerce mit vielen Produktkategorien',
                        'Heizungswasser- & Trinkwasseraufbereitung',
                        'Produktfotografie und klare Kategorien',
                        'Zahlung über PayPal & Kreditkarte',
                    ],
                ],
                [
                    'title' => 'B2B-Zugang: Preise nur für freigeschaltete Kunden',
                    'image' => '/images/references/pureheat/b2b-registrierung.png',
                    'description' => 'Das Herzstück: ein geschützter Kundenbereich für den Fachhandel. Preise und Bestellfunktion sind ausgeblendet, bis sich ein Kunde registriert und wir sein Gewerbe geprüft und freigeschaltet haben — ein geführter 3-Schritt-Prozess.',
                    'items' => [
                        'Preise nur für registrierte & freigeschaltete Kunden',
                        'Geführter Prozess: Registrieren → Freischaltung → Einkaufen',
                        'Prüfung der Gewerbekunden vor der Freischaltung',
                        'Voller Zugriff auf Sortiment und Preise nach Freischaltung',
                    ],
                ],
                [
                    'title' => 'Marketing: Gewinnspiel & Newsletter',
                    'image' => '/images/references/pureheat/gewinnspiel.png',
                    'description' => 'Verschiedene Marketingaktionen wurden umgesetzt: ein Video-Gewinnspiel (Lösungswort in YouTube- und Instagram-Videos, Teilnahme über Formular), der Newsletterversand sowie die kostenlose Katalog-Anforderung.',
                    'items' => [
                        'Video-Gewinnspiel mit Teilnahme-Formular',
                        'Newsletter-Anmeldung & Versand',
                        'Kostenlose Katalog-Anforderung',
                        'Reichweite & Kundenbindung',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Theme nach Designvorlage',
                    'description' => 'Individuelles Theme auf Basis des Bricks Builders (mit BricksUltimate und Automatic.css) — die Designvorlage pixelgenau umgesetzt, sauber und wartbar.',
                    'items' => [
                        'Bricks Builder · individuelles Theme',
                        'BricksUltimate & Automatic.css',
                        'Pixelgenaue Design-Umsetzung',
                        'Redaktionell pflegbar',
                    ],
                ],
                [
                    'icon' => 'shopping-cart',
                    'title' => 'WooCommerce & B2B-Zugang',
                    'description' => 'WooCommerce als Shop-Fundament, erweitert um einen geschützten B2B-Zugang: Preise und Bestellung erst nach Registrierung und Freischaltung.',
                    'items' => [
                        'WooCommerce · Produkte & Bestellung',
                        'Preise nur für freigeschaltete Kunden',
                        'Registrierungs- & Freischaltungsprozess',
                        'PayPal & Kreditkarte (Stripe)',
                    ],
                ],
                [
                    'icon' => 'megaphone',
                    'title' => 'Marketing & DSGVO',
                    'description' => 'Umgesetzte Marketingaktionen (Gewinnspiel, Newsletter, Katalog) und eine DSGVO-konforme Einwilligung über Borlabs Cookie.',
                    'items' => [
                        'Video-Gewinnspiel mit Formular',
                        'Newsletterversand',
                        'Katalog-Anforderung',
                        'DSGVO-konforme Cookie-Einwilligung (Borlabs)',
                    ],
                ],
            ],

            'impact_results' => [
                'Designvorlage pixelgenau als individueller WooCommerce-Shop umgesetzt',
                'Reiner Fachhandel: Preise nur für registrierte & freigeschaltete SHK-Profis',
                'Geführter B2B-Onboarding-Prozess (Registrieren → Freischaltung → Einkaufen)',
                'Marketingaktionen umgesetzt: Video-Gewinnspiel & Newsletterversand',
                'Zahlung über PayPal und Kreditkarte, DSGVO-konform',
            ],

            'results' => [
                ['value' => 'B2B', 'label' => 'Preise nur für freigeschaltete Kunden'],
                ['value' => '16', 'label' => 'Produktkategorien'],
                ['value' => '3', 'label' => 'Schritte bis zum Einkauf'],
                ['value' => '2', 'label' => 'Produktwelten (Heizungs- & Trinkwasser)'],
            ],

            'technologies' => [
                'WordPress',
                'Bricks Builder',
                'WooCommerce',
                'B2B-Zugang',
                'PayPal',
                'Stripe',
                'Newsletter',
                'Borlabs Cookie',
                'Responsive Design',
            ],

            'timeline' => [
                [
                    'title' => 'Designvorlage & Setup',
                    'description' => 'Übernahme der Designvorlage und Aufsetzen des individuellen Themes mit dem Bricks Builder.',
                ],
                [
                    'title' => 'WooCommerce-Shop',
                    'description' => 'Aufbau des Shops mit allen Produktkategorien der Heizungs- und Trinkwasseraufbereitung.',
                ],
                [
                    'title' => 'B2B-Zugang',
                    'description' => 'Umsetzung des geschützten Kundenbereichs: Preise nur nach Registrierung und Freischaltung, plus Zahlungsarten.',
                ],
                [
                    'title' => 'Marketingaktionen',
                    'description' => 'Umsetzung von Video-Gewinnspiel, Newsletterversand und kostenloser Katalog-Anforderung.',
                ],
                [
                    'title' => 'Feinschliff & DSGVO',
                    'description' => 'Responsives Feintuning, Borlabs-Cookie-Einwilligung und Übergabe an das Team.',
                ],
            ],

            'cta' => [
                'title' => 'Sie planen einen B2B-Shop oder Fachhandel mit geschützten Preisen?',
                'subtitle' => 'Ob individuelles Theme nach Ihrer Designvorlage, ein WooCommerce-Shop mit Preisen nur für registrierte Kunden oder umgesetzte Marketingaktionen — wir bauen B2B-Shops, die zu Ihrem Vertriebsmodell passen. Lassen Sie uns unverbindlich darüber sprechen.',
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
            'icon' => 'shopping-cart',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'PureHeat-Online — WooCommerce-B2B-Shop für Wasseraufbereitung',
            'client' => 'PureHeat-Online',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Individueller WooCommerce-B2B-Shop nach Designvorlage für den exklusiven SHK-Fachhandel PureHeat-Online — Preise nur für freigeschaltete Kunden, plus umgesetzte Marketingaktionen (Gewinnspiel, Newsletter).',
            'categories' => [
                'WordPress',
                'WooCommerce',
                'B2B-Shop',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein reiner Fachhandel für SHK-Profis braucht einen Shop, der Preise nur registrierten Gewerbekunden zeigt, die Designvorlage exakt umsetzt und Marketingaktionen ermöglicht.',
                'items' => [
                    'Designvorlage pixelgenau als Theme umsetzen',
                    'WooCommerce-Shop mit vielen Kategorien',
                    'Preise nur für freigeschaltete Fachkunden',
                    'Marketingaktionen: Gewinnspiel, Newsletter',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Ein individuelles WordPress-Theme (Bricks) mit WooCommerce und geschütztem B2B-Zugang — Preise erst nach Registrierung und Freischaltung, plus Video-Gewinnspiel und Newsletterversand.',
                'items' => [
                    'Individuelles Theme nach Designvorlage (Bricks)',
                    'WooCommerce mit Heizungs- & Trinkwasser-Kategorien',
                    'B2B-Zugang: Preise nur für freigeschaltete Kunden',
                    'Marketing: Video-Gewinnspiel & Newsletter',
                    'Zahlung über PayPal & Kreditkarte',
                ],
            ],
            'features' => [
                [
                    'title' => 'B2B-Shop',
                    'items' => [
                        'WooCommerce mit geschütztem B2B-Zugang',
                        'Preise nur für freigeschaltete Kunden',
                    ],
                ],
                [
                    'title' => 'Marketing',
                    'items' => [
                        'Video-Gewinnspiel mit Teilnahme-Formular',
                        'Newsletterversand & Katalog-Anforderung',
                    ],
                ],
            ],
            'results' => [
                'Designvorlage pixelgenau als WooCommerce-Shop umgesetzt',
                'Preise nur für registrierte & freigeschaltete SHK-Profis',
                'Geführter B2B-Prozess: Registrieren → Freischaltung → Einkaufen',
                'Marketingaktionen umgesetzt: Gewinnspiel & Newsletter',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'Design: Custom-Theme (Bricks Builder)',
                'Shop: WooCommerce mit B2B-Zugang',
                'Zahlung: PayPal & Kreditkarte (Stripe)',
                'Marketing: Gewinnspiel & Newsletter',
            ],
        ];
    }
};
