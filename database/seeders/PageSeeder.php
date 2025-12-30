<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Homepage
        Page::create([
            'slug' => 'home',
            'title' => 'Startseite',
            'type' => Page::TYPE_HOME,
            'meta_title' => 'sdWebdesign – Digitale Lösungen & Webanwendungen',
            'meta_description' => 'Individuelle digitale Lösungen für Unternehmen. Webanwendungen, Prozessautomatisierung, API-Integrationen und E-Commerce aus Frankfurt.',
            'content' => [
                'hero' => [
                    'badge' => 'Digitale Lösungen für Unternehmen',
                    'title' => 'Wir bauen digitale Systeme, die Ihr Geschäft voranbringen',
                    'subtitle' => 'Von komplexen Webanwendungen über Prozessautomatisierung bis zur Systemintegration – wir entwickeln maßgeschneiderte Lösungen, die funktionieren.',
                    'cta_primary_text' => 'Projekt besprechen',
                    'cta_primary_link' => '/kontakt',
                    'cta_secondary_text' => 'Lösungen ansehen',
                    'cta_secondary_link' => '/loesungen',
                ],
                'problem' => [
                    'title' => 'Kommt Ihnen das bekannt vor?',
                    'items' => [
                        ['title' => 'Manuelle Prozesse', 'description' => 'Ihre Mitarbeiter verbringen Stunden mit repetitiven Aufgaben, die automatisiert werden könnten.'],
                        ['title' => 'Datensilos', 'description' => 'Informationen sind über verschiedene Systeme verstreut und müssen manuell zusammengeführt werden.'],
                        ['title' => 'Veraltete Systeme', 'description' => 'Ihre aktuelle Software-Lösung ist langsam, unflexibel oder wird nicht mehr weiterentwickelt.'],
                        ['title' => 'Fehlende Übersicht', 'description' => 'Sie haben keinen zentralen Überblick über wichtige Geschäftskennzahlen und Prozesse.'],
                    ],
                ],
                'services' => [
                    'badge' => 'Was wir machen',
                    'title' => 'Digitale Lösungen, die zu Ihrem Unternehmen passen',
                    'subtitle' => 'Wir entwickeln keine Standard-Lösungen von der Stange, sondern digitale Systeme, die exakt auf Ihre Anforderungen zugeschnitten sind.',
                    'items' => [
                        ['icon' => 'globe', 'title' => 'Digitale Plattformen', 'description' => 'Maßgeschneiderte Webanwendungen für interne Prozesse, Kundenportale oder B2B-Plattformen.', 'link' => '/loesungen/digitale-plattformen'],
                        ['icon' => 'settings', 'title' => 'Prozessautomatisierung', 'description' => 'Automatisierung wiederkehrender Aufgaben und Workflows für mehr Effizienz.', 'link' => '/loesungen/prozessautomatisierung'],
                        ['icon' => 'git-branch', 'title' => 'API-Integration', 'description' => 'Nahtlose Verbindung Ihrer bestehenden Systeme und Datenquellen.', 'link' => '/loesungen/api-integration'],
                        ['icon' => 'shopping-cart', 'title' => 'E-Commerce', 'description' => 'Online-Shops und digitale Verkaufsplattformen mit WooCommerce oder Shopify.', 'link' => '/loesungen/e-commerce'],
                        ['icon' => 'smartphone', 'title' => 'iOS Apps', 'description' => 'Native iPhone und iPad Apps für Ihre Geschäftsanforderungen.', 'link' => '/loesungen/ios-apps'],
                        ['icon' => 'code', 'title' => 'WordPress', 'description' => 'Professionelle WordPress-Entwicklung für Websites und Content-Management.', 'link' => '/loesungen/wordpress'],
                    ],
                ],
                'principles' => [
                    'badge' => 'Unsere Prinzipien',
                    'title' => 'Technische Exzellenz als Grundlage',
                    'items' => [
                        ['number' => '01', 'title' => 'Bewährte Technologien', 'description' => 'Wir setzen auf etablierte Frameworks und Tools mit langfristigem Support und aktiver Community.'],
                        ['number' => '02', 'title' => 'Skalierbare Architekturen', 'description' => 'Systeme, die mit Ihrem Unternehmen wachsen – von hundert bis hunderttausend Nutzern.'],
                        ['number' => '03', 'title' => 'Sauberer Code', 'description' => 'Wartbarer, dokumentierter Code nach aktuellen Standards für langfristige Pflege.'],
                        ['number' => '04', 'title' => 'Sicherheit', 'description' => 'Security by Design – Sicherheit ist kein Nachgedanke, sondern Teil jeder Entscheidung.'],
                    ],
                ],
                'why_us' => [
                    'title' => 'Warum sdWebdesign?',
                    'items' => [
                        ['title' => 'Direkte Kommunikation', 'description' => 'Kein Projektmanager-Ping-Pong. Sie sprechen direkt mit dem Entwickler, der Ihr Projekt umsetzt.'],
                        ['title' => 'Pragmatische Lösungen', 'description' => 'Wir empfehlen, was funktioniert – nicht was am meisten Umsatz bringt oder gerade trendig ist.'],
                        ['title' => 'Langfristige Partnerschaft', 'description' => 'Wir betreuen unsere Projekte über Jahre hinweg und sind auch nach dem Launch für Sie da.'],
                        ['title' => 'Transparente Prozesse', 'description' => 'Regelmäßige Updates, nachvollziehbare Entscheidungen und offene Kommunikation.'],
                    ],
                ],
                'process' => [
                    'badge' => 'So arbeiten wir',
                    'title' => 'Von der Idee zur Lösung',
                    'steps' => [
                        ['number' => '01', 'title' => 'Verstehen', 'description' => 'Wir analysieren Ihre Situation, verstehen Ihre Ziele und identifizieren die beste Lösung.'],
                        ['number' => '02', 'title' => 'Konzipieren', 'description' => 'Gemeinsam entwickeln wir ein Konzept mit klarem Scope, Zeitplan und Budget.'],
                        ['number' => '03', 'title' => 'Entwickeln', 'description' => 'In iterativen Zyklen entsteht Ihre Lösung – mit regelmäßigem Feedback.'],
                        ['number' => '04', 'title' => 'Betreiben', 'description' => 'Nach dem Launch kümmern wir uns um Wartung, Updates und Weiterentwicklung.'],
                    ],
                ],
                'cta' => [
                    'title' => 'Bereit, Ihr Projekt zu besprechen?',
                    'subtitle' => 'Erzählen Sie uns von Ihrer Herausforderung. In einem kostenlosen Erstgespräch analysieren wir Ihre Anforderungen und geben eine ehrliche Einschätzung.',
                    'button_text' => 'Projekt besprechen',
                    'button_link' => '/kontakt',
                ],
            ],
            'is_active' => true,
        ]);

        // Solutions Overview
        Page::create([
            'slug' => 'loesungen',
            'title' => 'Lösungen',
            'type' => Page::TYPE_SOLUTIONS,
            'meta_title' => 'Digitale Lösungen – sdWebdesign',
            'meta_description' => 'Unsere digitalen Lösungen: Webanwendungen, Prozessautomatisierung, API-Integration, E-Commerce und iOS Apps.',
            'content' => [
                'hero' => [
                    'badge' => 'Lösungen',
                    'title' => 'Digitale Lösungen für komplexe Anforderungen',
                    'subtitle' => 'Von der ersten Idee bis zum fertigen System – wir entwickeln maßgeschneiderte digitale Lösungen, die Ihr Unternehmen voranbringen.',
                ],
                'cta' => [
                    'title' => 'Passt keine Lösung?',
                    'subtitle' => 'Wir entwickeln auch individuelle Lösungen, die nicht in eine Kategorie passen.',
                    'button_text' => 'Projekt besprechen',
                    'button_link' => '/kontakt',
                ],
            ],
            'is_active' => true,
        ]);

        // Solution Detail Pages
        $this->createSolutionDetailPages();

        // References
        Page::create([
            'slug' => 'referenzen',
            'title' => 'Referenzen',
            'type' => Page::TYPE_REFERENCES,
            'meta_title' => 'Referenzen – sdWebdesign',
            'meta_description' => 'Ausgewählte Projekte und Referenzen aus den Bereichen Webanwendungen, Prozessautomatisierung und Systemintegration.',
            'content' => [
                'hero' => [
                    'badge' => 'Referenzen',
                    'title' => 'Ausgewählte Projekte',
                    'subtitle' => 'Ein Einblick in erfolgreiche Projekte aus verschiedenen Branchen.',
                ],
                'projects' => [
                    [
                        'icon' => 'folder',
                        'number' => '01',
                        'client' => 'Mittelständischer Maschinenbauer',
                        'title' => 'Digitales Kundenportal',
                        'tagline' => 'Entwicklung eines umfassenden Kundenportals für Service-Anfragen und Ersatzteilbestellung.',
                        'categories' => ['Webanwendung', 'B2B-Portal'],
                        'challenge' => [
                            'title' => 'Herausforderung',
                            'description' => 'Fragmentierte Kommunikation und fehlende zentrale Dokumentenverwaltung.',
                            'items' => ['Fragmentierte Kommunikation', 'Keine zentrale Dokumentenverwaltung'],
                        ],
                        'solution' => [
                            'title' => 'Lösung',
                            'description' => 'Ein zentrales Kundenportal mit Self-Service-Funktionen.',
                            'items' => ['Zentrales Ticket-System', 'Digitale Dokumentenbibliothek'],
                        ],
                        'features' => [
                            ['title' => 'Ticket-System', 'items' => ['Status-Tracking', 'E-Mail-Benachrichtigungen']],
                            ['title' => 'Dokumentenverwaltung', 'items' => ['Versionierung', 'Zugriffsrechte']],
                        ],
                        'tech_stack' => ['Laravel', 'Vue.js', 'PostgreSQL'],
                        'results' => ['60% weniger E-Mail-Anfragen', '40% schnellere Bearbeitung'],
                    ],
                ],
                'cta' => [
                    'title' => 'Ähnliches Projekt geplant?',
                    'button_text' => 'Projekt besprechen',
                    'button_link' => '/kontakt',
                ],
            ],
            'is_active' => true,
        ]);

        // About Page
        Page::create([
            'slug' => 'ueber-uns',
            'title' => 'Über uns',
            'type' => Page::TYPE_ABOUT,
            'meta_title' => 'Über uns – sdWebdesign',
            'meta_description' => 'Erfahren Sie mehr über sdWebdesign und unseren Ansatz für digitale Lösungen.',
            'content' => [
                'hero' => [
                    'badge' => 'Über uns',
                    'title' => 'Digitale Lösungen mit Substanz',
                    'subtitle' => 'Wir sind ein kleines Team mit Fokus auf Qualität und langfristige Partnerschaften.',
                ],
                'team' => [
                    'title' => 'Ihr Ansprechpartner',
                    'members' => [
                        [
                            'name' => 'Steffen Fasselt',
                            'role' => 'Gründer & Entwickler',
                            'description' => 'Full-Stack Entwickler mit Fokus auf komplexe Webanwendungen.',
                            'experience' => '10+ Jahre',
                            'expertise' => ['Laravel & PHP', 'JavaScript & Vue.js'],
                            'icon' => 'user',
                        ],
                    ],
                ],
                'principles' => [
                    'title' => 'Unsere Prinzipien',
                    'items' => [
                        ['icon' => 'check-circle', 'title' => 'Qualität vor Quantität', 'description' => 'Wir nehmen nur Projekte an, die wir überzeugt umsetzen können.'],
                        ['icon' => 'message-circle', 'title' => 'Ehrliche Beratung', 'description' => 'Wir empfehlen, was sinnvoll ist.'],
                    ],
                ],
                'cta' => [
                    'title' => 'Interessiert an einer Zusammenarbeit?',
                    'button_text' => 'Kontakt aufnehmen',
                    'button_link' => '/kontakt',
                ],
            ],
            'is_active' => true,
        ]);

        // Contact Page
        Page::create([
            'slug' => 'kontakt',
            'title' => 'Kontakt',
            'type' => Page::TYPE_CONTACT,
            'meta_title' => 'Kontakt – sdWebdesign',
            'meta_description' => 'Nehmen Sie Kontakt auf für ein unverbindliches Erstgespräch.',
            'content' => [
                'hero' => [
                    'badge' => 'Kontakt',
                    'title' => 'Projekt besprechen',
                    'subtitle' => 'In einem unverbindlichen Erstgespräch analysieren wir Ihre Anforderungen.',
                ],
                'form' => [
                    'title' => 'Projekt anfragen',
                    'submit_text' => 'Anfrage senden',
                    'success_message' => 'Vielen Dank! Wir melden uns in der Regel innerhalb von 24 Stunden.',
                    'project_types' => [
                        ['value' => 'webapp', 'label' => 'Digitale Plattform / Webanwendung'],
                        ['value' => 'automation', 'label' => 'Prozessdigitalisierung & Automatisierung'],
                        ['value' => 'integration', 'label' => 'API- & Systemintegration'],
                        ['value' => 'ecommerce', 'label' => 'E-Commerce & Online-Shop'],
                        ['value' => 'app', 'label' => 'iOS App Entwicklung'],
                        ['value' => 'other', 'label' => 'Sonstiges'],
                    ],
                ],
                'contact' => [
                    'title' => 'Kontaktinformationen',
                    'email' => 'info@sdwebdesign.de',
                    'phone' => '+49 69 123 456 789',
                    'phone_hours' => 'Mo–Fr, 9:00–18:00 Uhr',
                    'location_city' => 'Frankfurt am Main',
                    'location_country' => 'Deutschland',
                    'info_title' => 'Direkter Kontakt bevorzugt?',
                    'info_text' => 'Rufen Sie direkt an oder schreiben Sie eine E-Mail.',
                    'response_time' => 'Innerhalb von 24 Stunden',
                ],
            ],
            'is_active' => true,
        ]);

        // Imprint
        Page::create([
            'slug' => 'impressum',
            'title' => 'Impressum',
            'type' => Page::TYPE_IMPRINT,
            'meta_title' => 'Impressum – sdWebdesign',
            'content' => [
                'sections' => [
                    ['heading' => 'Angaben gemäß § 5 TMG', 'content' => '<p>sdWebdesign<br>Steffen Fasselt<br>Musterstraße 123<br>60313 Frankfurt am Main</p>'],
                    ['heading' => 'Kontakt', 'content' => '<p>Telefon: +49 69 123 456 789<br>E-Mail: info@sdwebdesign.de</p>'],
                    ['heading' => 'Umsatzsteuer-ID', 'content' => '<p>Umsatzsteuer-Identifikationsnummer gemäß § 27 a UStG:<br>DE XXX XXX XXX</p>'],
                ],
                'company' => [
                    'name' => 'sdWebdesign',
                    'owner' => 'Steffen Fasselt',
                    'street' => 'Musterstraße 123',
                    'zip' => '60313',
                    'city' => 'Frankfurt am Main',
                    'email' => 'info@sdwebdesign.de',
                    'phone' => '+49 69 123 456 789',
                ],
            ],
            'is_active' => true,
        ]);

        // Privacy
        Page::create([
            'slug' => 'datenschutz',
            'title' => 'Datenschutzerklärung',
            'type' => Page::TYPE_PRIVACY,
            'meta_title' => 'Datenschutzerklärung – sdWebdesign',
            'content' => [
                'sections' => [
                    ['heading' => '1. Datenschutz auf einen Blick', 'content' => '<p>Die folgenden Hinweise geben einen Überblick darüber, was mit Ihren personenbezogenen Daten passiert.</p>'],
                    ['heading' => '2. Datenerfassung auf dieser Website', 'content' => '<p>Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber.</p>'],
                ],
                'company' => [
                    'name' => 'sdWebdesign',
                    'owner' => 'Steffen Fasselt',
                    'email' => 'info@sdwebdesign.de',
                ],
            ],
            'is_active' => true,
        ]);
    }

    private function createSolutionDetailPages(): void
    {
        $solutions = [
            [
                'slug' => 'digitale-plattformen',
                'title' => 'Digitale Plattformen & Webanwendungen',
                'icon' => 'globe',
                'subtitle' => 'Individuelle Plattformen für Kundenportale, interne Tools oder B2B-Systeme.',
            ],
            [
                'slug' => 'prozessautomatisierung',
                'title' => 'Prozessdigitalisierung & Automatisierung',
                'icon' => 'settings',
                'subtitle' => 'Wiederkehrende Aufgaben automatisieren und Effizienz steigern.',
            ],
            [
                'slug' => 'api-integration',
                'title' => 'API- & Systemintegration',
                'icon' => 'git-branch',
                'subtitle' => 'Nahtlose Integration Ihrer bestehenden Systeme.',
            ],
            [
                'slug' => 'e-commerce',
                'title' => 'E-Commerce & Online-Shops',
                'icon' => 'shopping-cart',
                'subtitle' => 'Professionelle Online-Shops mit WooCommerce oder Shopify.',
            ],
            [
                'slug' => 'ios-apps',
                'title' => 'iOS App Entwicklung',
                'icon' => 'smartphone',
                'subtitle' => 'Native iPhone und iPad Apps für Ihre Anforderungen.',
            ],
        ];

        foreach ($solutions as $solution) {
            Page::create([
                'slug' => $solution['slug'],
                'title' => $solution['title'],
                'type' => Page::TYPE_SOLUTION_DETAIL,
                'meta_title' => $solution['title'] . ' – sdWebdesign',
                'meta_description' => $solution['subtitle'],
                'content' => [
                    'hero' => [
                        'badge' => $solution['title'],
                        'title' => $solution['title'],
                        'subtitle' => $solution['subtitle'],
                        'icon' => $solution['icon'],
                    ],
                    'features' => [
                        'title' => 'Leistungen im Überblick',
                        'items' => [],
                    ],
                    'cta' => [
                        'title' => 'Interesse?',
                        'button_text' => 'Projekt besprechen',
                        'button_link' => '/kontakt',
                    ],
                ],
                'is_active' => true,
            ]);
        }
    }
}
