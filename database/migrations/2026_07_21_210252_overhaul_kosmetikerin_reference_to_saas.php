<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Overhaul the Kosmetikerin.org reference and move it to the top (#01).
 *
 * The project pivoted away from the e-commerce shop into a full SaaS platform
 * for cosmetic studios: a public studio directory (Verzeichnis) interlinked
 * with a studio app (calendar, online bookings, customer records, payments)
 * plus a native iOS app (SwiftUI) with a local CRM and treatment
 * documentation. This migration rewrites the reference-detail content, swaps
 * the stylized mockups for real screenshots, and moves the project to the
 * first position on /referenzen.
 *
 * The slug `kosmetikerin-ecommerce-app` is kept to preserve the existing URL
 * (SEO continuity), even though it no longer describes e-commerce.
 *
 * up() is idempotent and find-or-creates the detail page, so it works both on
 * production (updates the existing page) and locally (creates it). It is a
 * no-op when the references overview page is missing.
 */
return new class extends Migration
{
    private const SLUG_DE = 'kosmetikerin-ecommerce-app';

    private const SLUG_EN = 'cosmetician-ecommerce-app';

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
        $page->sort_order = 0; // first in any sort_order-based ordering

        $page->setTranslation('slug', 'de', self::SLUG_DE);
        $page->setTranslation('slug', 'en', self::SLUG_EN);
        $page->setTranslation('title', 'de', 'Kosmetikerin.org – SaaS-Plattform & iOS-App für Kosmetikstudios');
        $page->setTranslation('title', 'en', 'Kosmetikerin.org – SaaS Platform & iOS App for Cosmetic Studios');
        $page->setTranslation('meta_title', 'de', 'Kosmetikerin.org – SaaS & iOS-App für Kosmetikstudios');
        $page->setTranslation('meta_title', 'en', 'Kosmetikerin.org – SaaS & iOS App for Cosmetic Studios');
        $page->setTranslation('meta_description', 'de', 'SaaS-Plattform und native iOS-App für Kosmetikstudios: öffentliches Studio-Verzeichnis verzahnt mit Studio-App für Termine, Buchungen, Kundenakte und Zahlungen.');
        $page->setTranslation('meta_description', 'en', 'SaaS platform and native iOS app for cosmetic studios: a public studio directory interlinked with a studio app for appointments, bookings, customer records and payments.');

        $page->setTranslation('content', 'de', $this->contentDe());
        $page->save();

        $this->moveToTopOfOverview($overview);
    }

    public function down(): void
    {
        // The previous e-commerce content is intentionally not restored — this
        // is a content overhaul, not a structural change. Safe no-op.
    }

    private function moveToTopOfOverview(Page $overview): void
    {
        $content = $overview->getTranslation('content', 'de');

        if (! is_array($content)) {
            return;
        }

        $projects = array_values(array_filter(
            $content['projects'] ?? [],
            fn (array $project): bool => ($project['detail_slug'] ?? null) !== self::SLUG_DE
        ));

        array_unshift($projects, $this->overviewEntry());

        foreach ($projects as $index => &$project) {
            $project['number'] = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
        }
        unset($project);

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
                'category' => 'SaaS-Plattform · iOS-App · Kosmetikstudios',
                'tagline' => 'Von der E-Commerce-Idee zur vollwertigen SaaS-Lösung für Kosmetikstudios: Wir haben den früheren Shop abgelöst und ein umfassendes Produkt entwickelt — ein öffentliches Studio-Verzeichnis, eng verzahnt mit einer Studio-App für Termine, Online-Buchungen, Kundenakte und Zahlungen, plus eine native iOS-App mit lokalem CRM. Eine durchgängige Lösung, mit der Kosmetikstudios gefunden werden und ihren Alltag organisieren.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'Kosmetikerin.org'],
                ['label' => 'Branche', 'value' => 'SaaS · Kosmetik & Beauty'],
                ['label' => 'Website', 'value' => 'kosmetikerin.org', 'link' => 'https://kosmetikerin.org/'],
                ['label' => 'Leistung', 'value' => 'SaaS-Plattform, Studio-App & iOS-App'],
                ['label' => 'Modell', 'value' => 'Verzeichnis + Studio-App (verzahnt)'],
                ['label' => 'Stack', 'value' => 'Laravel · Inertia/Vue · SwiftUI'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'Kosmetikerin.org begann als E-Commerce-Projekt — heute ist es eine vollwertige SaaS-Plattform für Kosmetikstudios. Wir haben den früheren Shop abgelöst und stattdessen ein durchgängiges Produkt aufgebaut, das zwei Welten verzahnt: ein öffentliches Studio-Verzeichnis, in dem Kosmetikstudios gefunden werden und sich mit Profil, Leistungen, Team, Galerie, Bewertungen und Öffnungszeiten präsentieren — und eine Studio-App als operatives Rückgrat mit Kalender, Online-Buchungen, Kundenakte und Zahlungen. Ergänzt wird die Plattform durch eine native iOS-App mit lokalem CRM, Behandlungsdokumentation und Terminverwaltung. Verzeichnis, Web-App und iOS-App greifen ineinander — eine Buchung aus dem Verzeichnis landet direkt im Kalender des Studios.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Das ursprüngliche E-Commerce-Modell passte nicht mehr zum eigentlichen Bedarf: Kosmetikstudios brauchen keinen weiteren Shop, sondern Sichtbarkeit und ein Werkzeug für ihren Alltag. Gefragt war eine Plattform, die beides verbindet — gefunden werden und den Betrieb organisieren — auf Web und iOS, DSGVO-konform.',
                'items' => [
                    'Den bisherigen Shop ablösen und neu ausrichten',
                    'Öffentliches Verzeichnis, in dem Studios gefunden werden',
                    'Operative Studio-App: Kalender, Buchungen, Kundenakte, Zahlungen',
                    'Verzeichnis und Studio-App eng verzahnen (Buchung → Kalender)',
                    'Native iOS-App mit lokalem CRM und Behandlungsdokumentation',
                    'Abo-Modell (Pro) und DSGVO-Konformität',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine mehrschichtige SaaS-Plattform auf modernem Laravel-Stack (Inertia + Vue, Filament-Admin) mit einer Sanctum-API als Bindeglied zur nativen iOS-App (SwiftUI, Core Data). Das öffentliche Verzeichnis und die Studio-App teilen sich dieselben Daten: Studios pflegen ihr Profil, veröffentlichen es im Verzeichnis und wickeln zugleich Termine, Buchungen, Kundinnen und Zahlungen ab. Die iOS-App bringt das Studio mobil — mit lokaler Kundenakte, Behandlungsdoku (inkl. Unterschrift) und Kalender.',
                'items' => [
                    'Öffentliches Studio-Verzeichnis mit SEO-optimierten Profilen',
                    'Studio-App: Kalender, Online-Buchungen, Kundenakte, Zahlungen',
                    'Verzahnung: Buchung aus dem Verzeichnis landet im Studio-Kalender',
                    'Native iOS-App (SwiftUI) mit lokalem CRM & Behandlungsdoku',
                    'Sanctum-API als Bindeglied zwischen Web und iOS',
                    'Pro-Abo (Stripe & StoreKit), DSGVO-konform',
                    'Shop abgelöst — Fokus auf Sichtbarkeit & Studio-Alltag',
                ],
            ],

            'tech_stack' => [
                'Laravel 12 · Backend & API',
                'Inertia.js + Vue 3 · SPA-Frontend',
                'Filament 4 · Admin-Panel',
                'Laravel Sanctum · API für die iOS-App',
                'SwiftUI + Core Data · native iOS-App',
                'Stripe & StoreKit · Pro-Abonnement',
                'Sign in with Apple · Google-Kalender-Sync',
                'Tailwind CSS · Design-System',
                'DSGVO-konform · Verschlüsselung & Face-ID-Lock',
            ],

            'features' => [
                [
                    'title' => 'Öffentliches Studio-Verzeichnis',
                    'image' => '/images/references/kosmetikerin/verzeichnis-profil.png',
                    'description' => 'Im öffentlichen Verzeichnis werden Kosmetikstudios gefunden: ein ansprechendes Profil mit Leistungen und Preisen, Team, Galerie, Bewertungen, Öffnungszeiten und Kontaktmöglichkeit — SEO-optimiert für lokale Sichtbarkeit.',
                    'items' => [
                        'Öffentliches Studio-Profil mit Leistungen & Preisen',
                        'Bewertungen, Galerie, Team & Öffnungszeiten',
                        'Kontaktaufnahme direkt aus dem Profil',
                        'SEO-optimiert für die lokale Suche',
                    ],
                ],
                [
                    'title' => 'Studio-Dashboard & Selbstverwaltung',
                    'image' => '/images/references/kosmetikerin/studio-dashboard.png',
                    'description' => 'Jedes Studio verwaltet seinen Auftritt selbst: Profil und Leistungen pflegen, Potenziale erkennen (etwa inaktive Kundinnen reaktivieren) und das Pro-Abo verwalten — alles im übersichtlichen Studio-Dashboard.',
                    'items' => [
                        'Studio-Dashboard mit Kennzahlen & Potenzialen',
                        'Profil, Leistungen, Team & Galerie selbst pflegen',
                        'Reaktivierungs-Potenzial & Empfehlungsprogramm',
                        'Pro-Abo direkt verwalten',
                    ],
                ],
                [
                    'title' => 'Kalender & Online-Buchungen',
                    'image' => '/images/references/kosmetikerin/kalender.png',
                    'description' => 'Das operative Rückgrat: ein Kalender mit Terminen und Zeitblöcken, Online-Buchungen aus dem Verzeichnis und ein Buchungs-Widget zum Einbinden auf der eigenen Website — mit Ansicht je Mitarbeiter:in.',
                    'items' => [
                        'Wochen- & Monats-Kalender mit Terminen und Zeitblöcken',
                        'Online-Buchungen direkt aus dem Verzeichnis',
                        'Buchungs-Widget zum Einbinden auf der eigenen Website',
                        'Ansicht je Mitarbeiter:in',
                    ],
                ],
                [
                    'title' => 'Kundenakte & CRM',
                    'image' => '/images/references/kosmetikerin/kundenakte.png',
                    'description' => 'Alle Kundinnen an einem Ort: Kundenverwaltung mit Import, Buchungshistorie und Quelle. Kombiniert mit der nativen iOS-App wird daraus eine mobile Kundenakte inklusive Behandlungsdokumentation.',
                    'items' => [
                        'Kundenverwaltung mit Import & Suche',
                        'Buchungshistorie und Herkunft je Kundin',
                        'Mobile Kundenakte über die iOS-App',
                        'Behandlungsdokumentation (inkl. Unterschrift) auf dem iPad',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'server-stack',
                    'title' => 'Mehrschichtige SaaS-Architektur',
                    'description' => 'Laravel-Backend mit Inertia/Vue-Frontend und Filament-Admin, dazu eine Sanctum-API, über die die native iOS-App dieselben Daten nutzt — Verzeichnis und Studio-App teilen sich ein Fundament.',
                    'items' => [
                        'Laravel 12 · Inertia + Vue 3',
                        'Filament 4 · Admin-Panel',
                        'Sanctum-API für die iOS-App',
                        'Gemeinsame Datenbasis Web ↔ App',
                    ],
                ],
                [
                    'icon' => 'device-phone-mobile',
                    'title' => 'Native iOS-App',
                    'description' => 'Eine native iOS-App (SwiftUI, Core Data) bringt das Studio mobil: lokale Kundenakte, Behandlungsdoku mit Unterschrift, Termine und Google-Kalender-Sync — mit Face-ID-Lock und Offline-Fähigkeit.',
                    'items' => [
                        'SwiftUI · Core Data (offline-fähig)',
                        'Kundenakte & Behandlungsdokumentation',
                        'Google-Kalender-Sync',
                        'Face-ID-Lock · Sign in with Apple',
                    ],
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Abo-Modell & DSGVO',
                    'description' => 'Ein Pro-Abo über Stripe und StoreKit schaltet die Studio-Funktionen frei. Die gesamte Lösung ist DSGVO-konform gebaut — mit Verschlüsselung, Datensparsamkeit und vollständiger Löschmöglichkeit.',
                    'items' => [
                        'Pro-Abo · Stripe & StoreKit',
                        'Verschlüsselung & Datensparsamkeit',
                        'Vollständige Datenlöschung (DSGVO)',
                        'Rollen: Verzeichnis, Studio, Admin',
                    ],
                ],
            ],

            'impact_results' => [
                'Vom E-Commerce-Shop zur vollwertigen SaaS-Lösung für Kosmetikstudios',
                'Öffentliches Verzeichnis und Studio-App teilen sich eine Datenbasis',
                'Buchungen aus dem Verzeichnis landen direkt im Studio-Kalender',
                'Native iOS-App bringt Kundenakte & Behandlungsdoku aufs iPad',
                'Pro-Abo (Stripe/StoreKit) und durchgängige DSGVO-Konformität',
            ],

            'results' => [
                ['value' => '3', 'label' => 'Verzahnte Bausteine (Verzeichnis · Studio-App · iOS)'],
                ['value' => 'iOS', 'label' => 'Native App (SwiftUI · Core Data)'],
                ['value' => 'Pro', 'label' => 'SaaS-Abo (Stripe & StoreKit)'],
                ['value' => 'DSGVO', 'label' => 'konform gebaut'],
            ],

            'technologies' => [
                'Laravel',
                'Inertia.js',
                'Vue 3',
                'Filament',
                'Sanctum',
                'SwiftUI',
                'Core Data',
                'Stripe',
                'StoreKit',
                'Tailwind CSS',
                'iOS',
                'DSGVO',
            ],

            'timeline' => [
                [
                    'title' => 'Shop abgelöst',
                    'description' => 'Ablösung des ursprünglichen E-Commerce-Shops und Neuausrichtung auf ein SaaS-Modell für Kosmetikstudios.',
                ],
                [
                    'title' => 'Öffentliches Verzeichnis',
                    'description' => 'Aufbau des Studio-Verzeichnisses mit SEO-optimierten Profilen (Leistungen, Team, Galerie, Bewertungen).',
                ],
                [
                    'title' => 'Studio-App',
                    'description' => 'Entwicklung der operativen Studio-App: Kalender, Online-Buchungen, Kundenakte und Zahlungen.',
                ],
                [
                    'title' => 'iOS-App',
                    'description' => 'Native iOS-App (SwiftUI) mit lokalem CRM, Behandlungsdoku und Sanctum-API-Anbindung.',
                ],
                [
                    'title' => 'Abo & Betrieb',
                    'description' => 'Pro-Abo über Stripe/StoreKit, DSGVO-Feinschliff und laufender Betrieb der Plattform.',
                ],
            ],

            'cta' => [
                'title' => 'Sie haben eine Plattform- oder App-Idee?',
                'subtitle' => 'Ob SaaS-Plattform, Verzeichnis oder native App — wir bauen durchgängige Produkte, die Web und Mobile verzahnen, von der ersten Idee bis zum laufenden Betrieb. Lassen Sie uns unverbindlich darüber sprechen.',
                'button_text' => 'Projekt besprechen',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function overviewEntry(): array
    {
        return [
            'icon' => 'sparkles',
            'number' => '01',
            'title' => 'Kosmetikerin.org – SaaS-Plattform & iOS-App für Kosmetikstudios',
            'client' => 'Kosmetikerin.org',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'Vollwertige SaaS-Lösung für Kosmetikstudios: ein öffentliches Studio-Verzeichnis, verzahnt mit einer Studio-App (Kalender, Buchungen, Kundenakte, Zahlungen) und einer nativen iOS-App mit lokalem CRM. Der frühere Shop wurde abgelöst.',
            'categories' => [
                'SaaS-Plattform',
                'iOS-App',
                'Kosmetik & Beauty',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Der frühere E-Commerce-Shop passte nicht mehr zum Bedarf: Kosmetikstudios brauchen Sichtbarkeit und ein Werkzeug für ihren Alltag — auf Web und iOS.',
                'items' => [
                    'Shop ablösen und neu ausrichten',
                    'Öffentliches Verzeichnis, in dem Studios gefunden werden',
                    'Operative Studio-App: Kalender, Buchungen, Kundenakte',
                    'Native iOS-App mit lokalem CRM',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine mehrschichtige SaaS-Plattform (Laravel · Inertia/Vue · Filament) mit Sanctum-API zur nativen iOS-App (SwiftUI) — Verzeichnis und Studio-App teilen sich eine Datenbasis.',
                'items' => [
                    'Öffentliches Studio-Verzeichnis mit SEO-Profilen',
                    'Studio-App: Kalender, Buchungen, Kundenakte, Zahlungen',
                    'Verzahnung: Buchung landet im Studio-Kalender',
                    'Native iOS-App mit lokalem CRM & Behandlungsdoku',
                    'Pro-Abo (Stripe & StoreKit), DSGVO-konform',
                ],
            ],
            'features' => [
                [
                    'title' => 'Verzeichnis & Studio-App',
                    'items' => [
                        'Öffentliches Verzeichnis mit Studio-Profilen',
                        'Kalender, Online-Buchungen, Kundenakte, Zahlungen',
                    ],
                ],
                [
                    'title' => 'Native iOS-App',
                    'items' => [
                        'Lokales CRM & Behandlungsdokumentation',
                        'Sanctum-API-Anbindung, Pro-Abo (Stripe/StoreKit)',
                    ],
                ],
            ],
            'results' => [
                'Vom Shop zur vollwertigen SaaS-Lösung für Kosmetikstudios',
                'Verzeichnis und Studio-App teilen sich eine Datenbasis',
                'Buchungen landen direkt im Studio-Kalender',
                'Native iOS-App mit Kundenakte & Behandlungsdoku',
            ],
            'tech_stack' => [
                'Backend: Laravel 12',
                'Frontend: Inertia.js + Vue 3',
                'Admin: Filament 4',
                'API: Laravel Sanctum (iOS)',
                'iOS: SwiftUI + Core Data',
            ],
        ];
    }
};
