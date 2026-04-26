<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * The homepage's solutions section uses a fallback array in
 * home.blade.php — but production has a manually curated
 * `solutions.accordions` list inside `pages.content` (added through
 * Filament), which overrides the template default. Editing the
 * template alone therefore does not change live order.
 *
 * This migration overwrites the live accordion list with:
 *   - the new B2B-platform ordering (Plattformen 01, E-Commerce 02,
 *     Mobile 03, Websites 04), matching Phase A.1 hub sort_order
 *   - sharpened copy on the Plattformen card (positions it as
 *     "maßgeschneiderte B2B-Plattformen für etablierte Mittelständler",
 *     references Personio/SAP/Workforce-Management as concrete
 *     anchors, and surfaces the embedded-PO differentiator)
 *
 * Other `solutions` keys (badge, title, subtitle, growth_*, microcopy)
 * are not touched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if (! $home) {
            return;
        }

        $content = $home->getTranslation('content', 'de') ?? [];
        $solutions = $content['solutions'] ?? [];

        $solutions['accordions'] = [
            [
                'number' => '01',
                'icon' => 'layout-dashboard',
                'title' => 'Digitale Plattformen & Webanwendungen',
                'subtitle' => 'Maßgeschneiderte B2B-Plattformen für etablierte Mittelständler',
                'description' => 'Wenn Personio, SAP oder andere Standard-Software Ihre Workflows nicht mehr abdecken, entsteht Bedarf an einer eigenen Plattform. Wir entwickeln zentrale Systeme für Disposition, Prozesse und Zusammenarbeit — als maßgeschneiderte Werkzeuge, die mit Ihrem Geschäft mitwachsen.',
                'suitable_for' => [
                    'Workforce-Management & Disposition',
                    'Kunden- & Partnerportale mit Geschäftslogik',
                    'Interne Tools, die Standard-Software ergänzen oder ersetzen',
                ],
                'character' => [
                    'Eingebetteter Product Owner statt Ticket-System',
                    'Begleitung über Monate, nicht nur einzelne Projekte',
                    'Skalierbar und langfristig wartbar',
                ],
                'link' => '/loesungen/plattformen',
            ],
            [
                'number' => '02',
                'icon' => 'shopping-cart',
                'title' => 'E-Commerce & Online-Shops',
                'subtitle' => 'Verkaufen – integriert, performant und erweiterbar',
                'description' => 'Ein Shop ist mehr als ein Produktkatalog. Wir entwickeln E-Commerce-Lösungen, die zuverlässig funktionieren, Prozesse vereinfachen und sich sauber in bestehende Systeme integrieren lassen.',
                'suitable_for' => [
                    'B2C-Online-Shops',
                    'B2B-Bestellplattformen',
                    'Integrierte Shop- & Warenwirtschaftslösungen',
                ],
                'character' => [
                    'Technische Substanz statt Feature-Overload',
                    'Skalierbar bei Wachstum',
                    'Fokus auf Performance & Wartbarkeit',
                ],
                'link' => '/loesungen/e-commerce',
            ],
            [
                'number' => '03',
                'icon' => 'device-phone-mobile',
                'title' => 'Mobile Anwendungen (iOS / Android / PWA)',
                'subtitle' => 'Mobile Erweiterungen bestehender Systeme',
                'description' => 'Mobile Anwendungen entfalten ihren Wert, wenn sie Teil eines bestehenden Systems sind. Wir entwickeln mobile Lösungen, die Webanwendungen, Plattformen oder Shops sinnvoll ergänzen – nicht ersetzen.',
                'suitable_for' => [
                    'Native iOS- oder Android-Apps',
                    'Progressive Web Apps (PWA)',
                    'Mobile Companion- & Service-Apps',
                ],
                'character' => [
                    'Integration statt Insellösung',
                    'Gemeinsame Datenbasis & Logik',
                    'Schrittweise ausbaubar',
                ],
                'link' => '/loesungen/mobile-anwendungen',
            ],
            [
                'number' => '04',
                'icon' => 'globe',
                'title' => 'Unternehmenswebsites mit Substanz',
                'subtitle' => 'Professionelle Webauftritte, die heute passen – und morgen mitwachsen',
                'description' => 'Eine Unternehmenswebsite ist oft der erste Kontaktpunkt mit Ihrem Unternehmen. Sie soll Vertrauen schaffen, Inhalte klar vermitteln und technisch zuverlässig funktionieren – ohne unnötige Komplexität, aber mit einer Basis, die spätere Erweiterungen ermöglicht.',
                'suitable_for' => [
                    'Unternehmenswebsites & Leistungsseiten',
                    'Relaunch bestehender Websites',
                    'SEO-orientierte Content-Strukturen',
                ],
                'character' => [
                    'Klarer Einstieg mit überschaubarem Budget',
                    'Sauber umgesetzt, performant & wartbar',
                    'Erweiterbar Richtung Shop, Portal oder Plattform',
                ],
                'link' => '/loesungen/websites',
            ],
        ];

        $content['solutions'] = $solutions;
        $home->setTranslation('content', 'de', $content);
        $home->save();
    }

    public function down(): void
    {
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if (! $home) {
            return;
        }

        $content = $home->getTranslation('content', 'de') ?? [];
        if (isset($content['solutions']['accordions'])) {
            unset($content['solutions']['accordions']);
            $home->setTranslation('content', 'de', $content);
            $home->save();
        }
    }
};
