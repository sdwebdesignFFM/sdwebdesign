<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds the Gründerpaket Frankfurt content cluster — 1 Pillar + 4 Solution
 * Detail spokes + 3 Guide articles. Idempotent: re-running the seeder updates
 * existing pages by their slug rather than duplicating them.
 *
 * Live by default (is_active = true). Optional content blocks (cases, trust
 * numbers, ratings) are intentionally left empty so the conditional template
 * blocks hide them — the owner fills these in via Filament after launch.
 *
 * All multi-line German texts use Nowdoc syntax (<<<'TXT' ... TXT) so quotes,
 * apostrophes and special characters in the body never need escaping.
 *
 * Manual invocation:
 *   php artisan db:seed --class=GruenderpaketContentSeeder
 */
class GruenderpaketContentSeeder extends Seeder
{
    public function run(): void
    {
        $pillar = $this->upsertPage($this->pillarData());

        foreach ($this->spokesData() as $spoke) {
            $spoke['parent_id'] = $pillar->id;
            $this->upsertPage($spoke);
        }

        foreach ($this->guidesData() as $guide) {
            $this->upsertPage($guide);
        }
    }

    /**
     * Upsert a page based on its German slug.
     *
     * @param  array<string, mixed>  $data
     */
    private function upsertPage(array $data): Page
    {
        $deSlug = $data['slug']['de'];
        $page = Page::where('slug->de', $deSlug)->first();

        if ($page) {
            $page->fill($data);
            $page->save();

            return $page;
        }

        return Page::create($data);
    }

    // -----------------------------------------------------------------
    // PILLAR — /loesungen/gruenderpaket-frankfurt
    // -----------------------------------------------------------------

    /**
     * @return array<string, mixed>
     */
    private function pillarData(): array
    {
        $intro = <<<'TXT'
Sie gründen. Und plötzlich stehen da fünf Baustellen parallel: eine Website muss her, ein Logo, eine Unternehmens-E-Mail, das Impressum muss rechtssicher sein, irgendjemand redet von DSGVO, und der Steuerberater fragt, wo die Domain liegt.

Die übliche Antwort: Sie koordinieren drei bis fünf Freelancer — und verlieren Wochen, weil jeder auf den anderen wartet. Oder Sie klopfen bei einer klassischen Frankfurter Webagentur an und bekommen ein Angebot, das auf Konzern-Projekte zugeschnitten ist, inklusive Konzern-Budget.

Das Gründerpaket ist unsere Antwort auf diesen Zwischenraum. Alle digitalen Basiskomponenten, die Sie für einen professionellen Start in Frankfurt brauchen, kommen aus einer Hand — mit einem Ansprechpartner, einem Festpreis und einem verbindlichen Go-Live-Datum. Die Rechtstexte sind inklusive, das DSGVO-Setup ist fertig, und nach dem Launch wissen Sie, wie Sie die Inhalte selbst pflegen.

Das Paket ist für Einzelunternehmer, Freiberufler, UGs und frisch gegründete GmbHs im Rhein-Main-Gebiet gemacht. Nicht für den nächsten Fintech-Exit. Nicht für den Mittelstandskonzern. Für den Fall dazwischen, den die meisten Agenturen strukturell nicht gut bedienen.
TXT;

        $differentiation = <<<'TXT'
Gegen DIY-Baukästen (Jimdo, Wix, IONOS): Sie sparen scheinbar Geld — zahlen aber mit Ihrer Zeit. Der Grundbausatz ist schnell aufgesetzt, das Feintuning (SEO, Rechtstexte, saubere Struktur) verschlingt leise die Wochenenden. Und am Ende haben Sie eine Seite, die aussieht wie hundert andere. Das Gründerpaket gibt Ihnen ein individuelles Ergebnis, das Sie trotzdem selbst pflegen können.

Gegen Freelancer-Koordination: Ein Logo-Designer, ein Texter, ein Web-Entwickler, ein Rechtsanwalt für die Texte, ein SEO-Freelancer — jeder wartet auf den anderen, niemand übernimmt die Gesamtverantwortung. Beim Gründerpaket ist das eine einzige Projektleitung mit einer einzigen Rechnung.

Gegen klassische Frankfurter Webagenturen: Agenturen, die üblicherweise für den Mittelstand oder Konzerne arbeiten, haben eine Preisstruktur und einen Prozess, der auf 6-stellige Projekte ausgelegt ist. Ein Gründer passt selten rein — entweder kostet es zu viel, oder Sie bekommen das Praktikanten-Projekt. Das Gründerpaket ist von Grund auf für die Einstiegsphase konzipiert.
TXT;

        $growth = <<<'TXT'
Das Gründerpaket ist bewusst schlank. Aber es ist keine Sackgasse. Die Website ist auf einer professionellen Basis (moderne Technik, sauberer Code, erweiterbare Struktur) aufgesetzt — wenn Sie in einem Jahr einen Online-Shop brauchen, mehrsprachig werden, ein Kundenportal aufbauen oder eine mobile App einsetzen, bauen wir oder eine andere Agentur darauf auf. Sie starten nicht auf einer WordPress-Plugin-Wolke, die Sie in zwei Jahren komplett wegwerfen.

Viele unserer ersten Gründerpaket-Kunden arbeiten heute mit erweiterten Projekten weiter — Online-Shops, integrierte Plattformen, spezialisierte Kundenportale. Der Übergang ist nahtlos, weil die Basis stimmt.
TXT;

        return [
            'type' => Page::TYPE_SOLUTION_HUB,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 50,
            'slug' => [
                'de' => 'gruenderpaket-frankfurt',
                'en' => 'gruenderpaket-frankfurt',
            ],
            'title' => [
                'de' => 'Gründerpaket Frankfurt',
                'en' => 'Founder Package Frankfurt',
            ],
            'meta_title' => [
                'de' => 'Gründerpaket Frankfurt: Website + Logo + Start zum Festpreis',
            ],
            'meta_description' => [
                'de' => 'Website, Logo & DSGVO-Setup aus einer Hand für Gründer in Frankfurt. Festpreis, fixer Starttermin, 4–6 Wochen bis Launch — kein Enterprise-Overhead.',
            ],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Gründerpaket · Frankfurt & Rhein-Main',
                        'title' => 'Gründerpaket Frankfurt — Website, Logo & digitale Geschäftsausstattung aus einer Hand',
                        'subtitle' => 'Ein Ansprechpartner, ein Festpreis, ein fester Starttermin. Kein Enterprise-Overhead, kein Startup-Risiko. Wir liefern Gründerinnen und Gründern aus Frankfurt und dem Rhein-Main-Gebiet ein rechtssicheres, komplettes digitales Setup — in 4–6 Wochen live.',
                        'icon' => 'rocket-launch',
                    ],
                    'intro' => ['text' => $intro],
                    'challenge' => [
                        'title' => 'Was wir tatsächlich für Sie bauen',
                        'text' => 'Eine Marke ist mehr als ein Logo, eine Website mehr als Pixel auf einem Bildschirm. Bevor wir konfigurieren, gestalten oder Code schreiben, hören wir zu: Was bieten Sie an, für wen, mit welchem Anspruch? Was unterscheidet Ihr Versprechen vom Wettbewerb? Wer soll sich in Ihrem Auftritt wiedererkennen?',
                    ],
                    'approach' => [
                        'title' => 'Unser kreativer, individueller Prozess',
                        'text' => 'Aus Ihrem Briefing entsteht keine Template-Anwendung, sondern ein zusammenhängender Auftritt: Logo, Farben, Typografie, Website-Struktur, Tonalität — alles aus einer Idee abgeleitet, alles konsistent über Touchpoints hinweg. Sie identifizieren sich am Ende mit dem Ergebnis, weil es Ihre Geschichte erzählt — nicht eine generische, sondern Ihre eigene.',
                    ],
                    'package' => [
                        'headline' => 'Was Sie im Gründerpaket bekommen',
                        'intro' => 'Alles, was ein professioneller digitaler Start in Frankfurt wirklich braucht — aus einer Hand, zum Festpreis, mit einem Ansprechpartner. Keine Zusatzrechnungen für selbstverständliche Dinge wie Impressum oder DSGVO.',
                        'items' => [
                            ['name' => 'Individuelle Website (bis zu 6 Seiten)', 'description' => 'Responsive, schnell, DSGVO-konform. Startseite, Leistungen, Über uns, Kontakt, Impressum, Datenschutz. Mit allen Texten, Bildern und Inhalten umgesetzt.'],
                            ['name' => 'Logo & Grundlogo-Varianten', 'description' => 'Wortmarke oder Wort-Bild-Marke, horizontale und quadratische Varianten, Hell/Dunkel-Version, als SVG + PNG druck- und webfertig.'],
                            ['name' => 'Corporate Identity Basics', 'description' => 'Farbpalette, Typografie-System, ein einfaches Style-Mini-Manual (PDF), das Sie Ihrem Steuerberater, Ihrem Notar oder einem Druckerei-Dienstleister weitergeben können.'],
                            ['name' => 'Domain-Setup & Business-E-Mail', 'description' => 'Ihre Wunschdomain registriert, Mailkonten (info@, name@) eingerichtet auf einem DSGVO-konformen deutschen Provider, Weiterleitungen, SPF/DKIM/DMARC korrekt gesetzt.'],
                            ['name' => 'Rechtssicheres Impressum + Datenschutzerklärung', 'description' => 'Nicht aus einem Generator kopiert, sondern auf Ihre konkrete Gründungsform (Einzelunternehmen/Freiberufler/UG/GmbH) zugeschnitten. Inklusive Cookie-Banner mit Consent-Management.'],
                            ['name' => 'Google Business Profile eingerichtet', 'description' => 'Frankfurter Local-Pack-Visibility von Tag 1 — inklusive Kategorien, Fotos, Öffnungszeiten und erstem Post.'],
                            ['name' => 'SEO-Grundlagen', 'description' => 'Sitemap, robots.txt, Meta-Titles, strukturierte Daten (Schema.org), lokale Keywords, verifizierte Google Search Console. Sie starten nicht unsichtbar.'],
                            ['name' => 'Hosting & Wartung im ersten Jahr', 'description' => 'Managed Hosting auf deutschen Servern, Backups, Updates, SSL-Zertifikat, Uptime-Monitoring — alles enthalten. Sie müssen im ersten Jahr nichts zusätzlich buchen.'],
                            ['name' => 'Einweisung & 30 Tage Support', 'description' => 'Eine strukturierte Live-Einweisung (60–90 Min), damit Sie Texte, Bilder und Blog-Artikel selbst pflegen können. 30 Tage E-Mail-Support nach Launch für alle Fragen.'],
                        ],
                    ],
                    'pricing' => [
                        'label' => 'Gründerpaket ab 4.500 €',
                        'note' => 'Transparent kalkuliert. Festpreis nach Briefing. Keine Überraschungen am Projektende.',
                    ],
                    'timeline' => [
                        'label' => '4–6 Wochen bis Launch',
                        'note' => 'Von der ersten Abstimmung bis zum Go-Live. Verbindlich.',
                    ],
                    'when_useful' => [
                        'title' => 'Wann das Gründerpaket das Richtige für Sie ist',
                        'intro' => 'Das Paket passt genau dann, wenn Sie professionell starten müssen, aber nicht die Zeit oder das Budget haben, fünf Gewerke parallel zu koordinieren:',
                        'conditions' => [
                            'Sie haben Ihr Unternehmen gerade angemeldet oder stehen kurz davor — Einzelunternehmen, Freiberufler, UG oder GmbH.',
                            'Sie brauchen in den nächsten 4–8 Wochen einen professionellen digitalen Auftritt, nicht in sechs Monaten.',
                            'Sie wollen einen Ansprechpartner, nicht ein Koordinations-Projekt mit drei Freelancern.',
                            'Rechtssicherheit (DSGVO, Impressum, Cookie-Banner) soll vom ersten Tag an stimmen — Sie wollen keine Abmahnung sechs Wochen nach dem Launch.',
                            'Ihr Budget liegt im mittleren vierstelligen Bereich — nicht ab 999 €, aber auch nicht im Konzern-Bereich.',
                            'Sie wollen die Website später selbst pflegen können, ohne bei jeder Textänderung einen Entwickler zu brauchen.',
                        ],
                        'note' => 'Wenn ein Punkt davon nicht passt, ist das Paket oft nicht das Richtige — dann sind entweder unsere Einzelleistungen oder ein individuelles Projekt sinnvoller. Wir sagen Ihnen im Briefing ehrlich, was passt.',
                    ],
                    'use_case_categories' => [
                        ['title' => 'Freiberufler & Einzelunternehmer', 'description' => 'Beratung, Coaching, Therapie, Handwerk, kreative Berufe.', 'items' => ['Coaches', 'Heilpraktiker', 'Architekten', 'freie Berater', 'Grafiker', 'Texter', 'Fotografen']],
                        ['title' => 'Frisch gegründete UGs & GmbHs', 'description' => 'Kapitalgesellschaften in den ersten 6–12 Monaten nach Handelsregister-Eintrag.', 'items' => ['Service-Unternehmen', 'B2B-Beratung', 'kleine Online-Shops', 'spezialisierte Dienstleister']],
                        ['title' => 'Handwerk & lokale Dienstleister', 'description' => 'Betriebe, die Kunden im Frankfurter Einzugsgebiet gewinnen müssen.', 'items' => ['Elektriker', 'Installateure', 'Maler', 'Gärtner', 'Pflegedienste', 'Reinigungsfirmen']],
                        ['title' => 'Praxen & Gesundheitsberufe', 'description' => 'Neue Praxen für Therapeuten, Heilpraktiker und Gesundheitsberufe in Frankfurt und dem Taunus.', 'items' => ['Allgemeinmedizin', 'Physiotherapie', 'Osteopathie', 'Ergotherapie', 'Logopädie']],
                        ['title' => 'Juristische & steuerliche Berufe', 'description' => 'Neu-Kanzleien, Steuerberater, Notariate.', 'items' => ['Rechtsanwälte (Schwerpunkt-Kanzleien)', 'Steuerberater', 'Wirtschaftsprüfer']],
                    ],
                    'cards_intro' => [
                        'title' => 'Was das Paket im Detail abdeckt',
                        'text' => 'Jede Komponente des Gründerpakets ist auch als Einzelleistung verfügbar — falls Sie nur ein Logo brauchen, nur eine Website, oder nur das DSGVO-Setup. In Kombination kostet es weniger als die Einzelleistungen und läuft schneller, weil alles aufeinander abgestimmt ist.',
                    ],
                    'process' => [
                        'title' => 'So läuft das Gründerpaket ab',
                        'steps' => [
                            ['title' => 'Kennenlern-Call (30 Minuten, kostenlos)', 'description' => 'Wir klären Gründungsform, Zielgruppe, Branche und Ihren Zeitrahmen. Am Ende wissen Sie, ob das Paket passt — oder ob Sie etwas anderes brauchen.'],
                            ['title' => 'Festpreis-Angebot & Starttermin (48 Stunden)', 'description' => 'Sie bekommen ein verbindliches Angebot mit exakten Leistungen, Festpreis und einem Starttermin. Keine Kostenfallen.'],
                            ['title' => 'Briefing-Workshop (90 Minuten)', 'description' => 'Wir gehen Ihre Zielgruppe, Positionierung, Tonalität und Inhalte durch. Am Ende haben wir einen Content-Plan, und Sie wissen genau, was Sie uns zuliefern.'],
                            ['title' => 'Design & Konzept (Woche 1–2)', 'description' => 'Logo-Entwürfe, Farbpalette, Website-Konzept, Startseiten-Design. Ein Korrektur-Durchgang ist enthalten.'],
                            ['title' => 'Umsetzung (Woche 2–4)', 'description' => 'Website wird umgesetzt, Texte eingebaut, Domain und E-Mails konfiguriert, Rechtstexte generiert und geprüft, Google Business Profile eingerichtet.'],
                            ['title' => 'Review & Feinschliff (Woche 4–5)', 'description' => 'Sie testen die Seite auf einer Staging-URL. Wir gehen eine Review-Runde durch und schärfen nach.'],
                            ['title' => 'Launch & Einweisung (Woche 5–6)', 'description' => 'Go-Live auf Ihrer Domain. Anschließend eine strukturierte Einweisung (60–90 Min), damit Sie die Inhalte selbst pflegen können.'],
                            ['title' => '30 Tage Support nach Launch', 'description' => 'E-Mail-Support für alle Fragen der ersten 30 Tage nach Launch. Danach optional mit unserem Wartungs-Paket.'],
                        ],
                    ],
                    'capabilities' => [
                        'title' => 'Was technisch und rechtlich abgedeckt ist',
                        'intro' => 'Alle Standardleistungen, ohne dass Sie nachbestellen oder nachverhandeln müssen.',
                        'items' => [
                            'Responsive Design (Desktop / Tablet / Smartphone), getestet auf echten Geräten',
                            'DSGVO-konformes Cookie-Banner mit Consent-Management',
                            'Impressum, Datenschutzerklärung — auf Ihre Gründungsform zugeschnitten',
                            'SSL-Zertifikat (https), HSTS, moderne Security-Header',
                            'SEO-Grundlagen: Meta-Tags, strukturierte Daten, Sitemap, robots.txt',
                            'Google Search Console verifiziert, Google Business Profile eingerichtet',
                            'Performance-Optimierung (Bildkompression, Caching, moderne Bildformate)',
                            'Backup & Monitoring im ersten Jahr enthalten',
                            'Logo (Wortmarke oder Wort-Bild-Marke) mit allen Varianten',
                            'Farbpalette und Typografie-System mit Style-Mini-Manual als PDF',
                        ],
                        'note' => 'Bei besonderen Anforderungen (Mehrsprachigkeit, Online-Shop, individuelle Funktionen) sprechen wir im Briefing ehrlich, was als Erweiterung sinnvoll ist und was sich später nahtlos ergänzen lässt.',
                    ],
                    'differentiation' => [
                        'title' => 'Was das Gründerpaket von anderen Optionen unterscheidet',
                        'text' => $differentiation,
                    ],
                    'growth' => [
                        'title' => 'Was passiert, wenn Sie wachsen',
                        'text' => $growth,
                    ],
                    'comparison' => [
                        'title' => 'Sie vergleichen noch?',
                        'link_text' => 'Geschäftsausstattung für Gründer — die komplette Checkliste',
                    ],
                    'related_guide_slug' => 'geschaeftsausstattung-gruendung-checkliste',
                    'cta' => [
                        'title' => 'Bereit für Ihr Gründerpaket?',
                        'subtitle' => 'Lassen Sie uns 30 Minuten sprechen. Am Ende wissen Sie, ob das Paket zu Ihrer Gründung passt — oder ob etwas anderes für Sie sinnvoller ist. Kostenlos, unverbindlich, ohne Verkaufsgespräch.',
                        'button_text' => 'Kostenloses Kennenlern-Gespräch',
                    ],
                ],
            ],
        ];
    }

    // -----------------------------------------------------------------
    // SPOKES
    // -----------------------------------------------------------------

    /**
     * @return array<int, array<string, mixed>>
     */
    private function spokesData(): array
    {
        return [
            $this->spokeWebsiteExistenzgruender(),
            $this->spokeLogoCorporateIdentity(),
            $this->spokeDigitaleGeschaeftsausstattung(),
            $this->spokeSocialMediaSetup(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function spokeWebsiteExistenzgruender(): array
    {
        $whyNativeText = <<<'TXT'
Wir bauen Websites, die nicht aussehen wie Templates, weil wir keine sind: Bevor wir die erste Zeile Code schreiben oder das erste Bild platzieren, verstehen wir, was Sie wirklich anbieten, für wen Sie es anbieten, und worin sich Ihr Versprechen vom Wettbewerb unterscheidet. Aus diesem Verständnis entsteht eine Website, in der sich Ihre zukünftigen Kunden wiedererkennen — und Sie selbst auch.

Eine Gründer-Website ist kein Selbstzweck. Sie ist die digitale Verkörperung Ihrer Positionierung: derselbe Ton, dieselbe Klarheit, dieselbe Haltung wie in Ihrem ersten Kennenlern-Gespräch. Konsistent durch Startseite, Leistungs-Seiten und Über-uns. Technisch sauber im Hintergrund, damit Performance, SEO und Rechtssicherheit nicht zu nachträglichen Baustellen werden.
TXT;

        $differentiation = <<<'TXT'
Gegen Jimdo / Wix / IONOS / Squarespace: Baukästen suggerieren, dass alles in 30 Minuten fertig sei. In der Realität sparen Sie das Handwerk — und zahlen dafür mit Ihrer Zeit und mit zähen Kompromissen. Drei konkrete Punkte: Performance — Baukasten-Seiten sind systematisch langsamer als individuelle Websites; SEO-Tiefe — Meta-Struktur, strukturierte Daten, saubere URLs sind im Baukasten oft nur in teuren Tarif-Upgrades; Markenauftritt — Baukasten-Templates sehen in jeder Branche gleich aus.

Gegen Freelancer-Lösungen: Ein freier Web-Entwickler baut vielleicht technisch eine saubere Seite. Aber die Rechtstexte? Die SEO-Basis? Das Cookie-Banner? Das Google Business Profile? Oft genug heißt das: Das müssen Sie selbst noch machen. Beim Gründerpaket machen wir alles, was zum Launch gehört — nicht nur den Code.
TXT;

        $growth = <<<'TXT'
Die Gründer-Website ist bewusst schlank — aber auf einer erweiterbaren Basis. Falls Sie in 12–24 Monaten einen Shop dazu brauchen, einen geschützten Bereich für Ihre Kunden, mehrsprachige Inhalte, ein Kundenportal oder eine direkte Integration an Ihr CRM — all das bauen wir oder eine andere Agentur darauf auf, ohne die Basis neu machen zu müssen.

Der häufigste Wachstumspfad unserer Gründer-Kunden: Nach 6–12 Monaten kommen weitere Landingpages dazu (für spezielle Zielgruppen, Angebote, Kampagnen). Nach 12–18 Monaten oft Integrationen: Terminbuchung, CRM, Newsletter-Automation. Nach 2+ Jahren dann strukturelle Erweiterungen wie Kundenportale oder Shop-Funktionen.
TXT;

        return [
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'is_active' => true,
            'sort_order' => 10,
            'slug' => ['de' => 'website-fuer-existenzgruender', 'en' => 'website-fuer-existenzgruender'],
            'title' => ['de' => 'Website für Existenzgründer', 'en' => 'Website for Founders'],
            'meta_title' => ['de' => 'Website für Existenzgründer: DSGVO-konform in 4–6 Wochen'],
            'meta_description' => ['de' => 'Professionelle Gründer-Website in Frankfurt — rechtssicher, responsive, mit Impressum & Datenschutz ab Launch. Festpreis, klarer Zeitrahmen, ohne Plugin-Chaos.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Website · Existenzgründer',
                        'title' => 'Website für Existenzgründer — rechtssicher, schnell, eigenständig zu pflegen',
                        'subtitle' => 'Eine Gründer-Website hat zwei Aufgaben: die ersten Kunden gewinnen und rechtlich nicht angreifbar sein. Beides bauen wir für Frankfurter Gründerinnen und Gründer von Anfang an sauber auf — ohne Baukasten-Optik, ohne Plugin-Chaos, ohne Überraschungen am Launch-Tag.',
                        'icon' => 'globe',
                    ],
                    'why_native' => [
                        'title' => 'Was wir für Sie bauen',
                        'text' => $whyNativeText,
                        'items' => [
                            'Verstehen statt vermuten — wir starten mit Ihrer Positionierung, nicht mit einem Template',
                            'Individueller Auftritt, der zu Ihrer Marke und Ihrer Sprache passt',
                            'Konsistente visuelle und sprachliche Linie über alle Seiten hinweg',
                            'Technisches Fundament, das mit Ihrem Wachstum mitgeht',
                            'Sie können die Inhalte selbst pflegen — ohne Entwickler-Abhängigkeit',
                        ],
                    ],
                    'when' => [
                        'title' => 'Wann ist diese Lösung die richtige?',
                        'intro' => 'Die Existenzgründer-Website ist für den konkreten Anwendungsfall gemacht, in dem Sie jetzt stecken:',
                        'conditions' => [
                            'Sie haben gerade gegründet (oder stehen kurz davor) und brauchen in 4–8 Wochen eine professionelle Website, keine Baustelle.',
                            'Ihre Zielgruppe sucht online nach Ihnen — über Google, über Empfehlungen, über Ihr Google Business Profile, über Visitenkarten.',
                            'Die Website soll rechtssicher sein (DSGVO, Impressum, Cookie-Banner) — auch wenn der erste Cent noch nicht verdient ist.',
                            'Sie möchten die Seite später selbst pflegen können, ohne monatliche Abhängigkeit von einer Agentur.',
                            'Sie wollen ein Ergebnis, das nicht aussieht wie die letzten fünfzig Wix-Seiten in Ihrer Branche.',
                        ],
                        'note' => 'Wenn Ihr Projekt hochkomplex ist — etwa ein mehrsprachiger Online-Shop mit Lagerintegration oder ein Kundenportal mit Login-Bereichen — dann ist die Gründer-Website nicht das Richtige. Dafür haben wir dedizierte Lösungen.',
                    ],
                    'features' => [
                        'title' => 'Was eine gute Gründer-Website leistet',
                        'intro' => 'Diese Bausteine sorgen dafür, dass Sie nicht nur online sind, sondern tatsächlich die ersten Kunden gewinnen:',
                        'items' => [
                            'Leistungs-Seiten, die in 30 Sekunden klar machen, was Sie tun und für wen',
                            'Über-uns-Seite, die Vertrauen schafft — wichtig vor allem bei Einzelunternehmern',
                            'Reibungsfreier Kontakt: Formular, Mail, Telefon, optional Kalender-Buchung',
                            'Lokale Sichtbarkeit mit LocalBusiness-Schema und Anbindung ans Google Business Profile',
                            'Cookie-Banner mit echter Consent-Lösung (nicht nur dekorativ)',
                            'Impressum & Datenschutz auf Ihre Gründungsform zugeschnitten',
                            'Performance-Optimierung — schnelle Ladezeiten auch auf Mobilgeräten',
                            'Saubere SEO-Basis: Meta-Struktur, Sitemap, strukturierte Daten',
                        ],
                    ],
                    'process' => [
                        'title' => 'Wie die Website entsteht',
                        'steps' => [
                            ['title' => 'Briefing (90 Minuten)', 'description' => 'Wir klären Zielgruppe, Ihren Fokus-Service, Ihre Tonalität, was Sie gegenüber Wettbewerbern unterscheidet. Am Ende haben wir einen klaren Content-Plan.'],
                            ['title' => 'Struktur & Design (Woche 1)', 'description' => 'Seitenstruktur, Wireframes, Startseiten-Entwurf. Sie bekommen einen Entwurf, den Sie bewerten können, bevor wir anfangen, alle Unterseiten zu bauen.'],
                            ['title' => 'Umsetzung (Woche 2–4)', 'description' => 'Technische Umsetzung, Content-Integration, Bildoptimierung, Rechtstexte, Cookie-Banner.'],
                            ['title' => 'Review auf Staging (Woche 4–5)', 'description' => 'Sie sehen die fertige Seite auf einer Test-URL. Eine Review-Runde mit Korrekturen ist Teil des Festpreises.'],
                            ['title' => 'Launch & Einweisung (Woche 5–6)', 'description' => 'Go-Live auf Ihrer Domain. Einweisung (60 Min), wie Sie Inhalte selbst aktualisieren. 30 Tage E-Mail-Support danach inklusive.'],
                        ],
                    ],
                    'capabilities' => [
                        'title' => 'Was wir standardmäßig ausliefern',
                        'items' => [
                            'Responsive Design (Desktop, Tablet, Smartphone) — getestet auf echten Geräten',
                            'DSGVO-konformes Cookie-Banner mit Consent-Management',
                            'Impressum, Datenschutzerklärung — auf Ihre Gründungsform zugeschnitten',
                            'SSL-Zertifikat + moderne Security-Header (HSTS, X-Content-Type-Options)',
                            'Performance-Optimierung: WebP-Bilder, Caching, optimierter CSS/JS-Build',
                            'SEO-Grundlagen: Meta-Tags, Open Graph, strukturierte Daten, Sitemap, robots.txt',
                            'Google Search Console + Google Business Profile vorbereitet',
                            'Hosting auf deutschen Servern im ersten Jahr enthalten',
                            'Backup-System + automatische Updates',
                            'Training (60 Min), wie Sie Inhalte pflegen',
                        ],
                        'note' => 'Mehrsprachigkeit, Online-Shop, Login-Bereiche und individuelles Logo-Design sind dedizierte Leistungen.',
                    ],
                    'differentiation' => [
                        'title' => 'Warum nicht einfach einen Baukasten nehmen?',
                        'text' => $differentiation,
                    ],
                    'growth' => ['title' => 'Was später kommt', 'text' => $growth],
                    'cta' => [
                        'title' => 'Bereit für Ihre Gründer-Website?',
                        'subtitle' => '30 Minuten Kennenlern-Gespräch, kostenlos. Wir klären, ob die Gründer-Website das Richtige für Sie ist — oder ob das Gründerpaket mehr Sinn macht.',
                        'button_text' => 'Jetzt Gespräch anfragen',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function spokeLogoCorporateIdentity(): array
    {
        $whyNativeText = <<<'TXT'
Eine Marke ist mehr als ein Logo. Sie ist die optische Verdichtung dessen, wofür Ihr Unternehmen steht — und das, woran Ihre Kunden sich erinnern, wenn sie an Sie denken. Bevor wir auch nur einen Strich zeichnen, verstehen wir, wer Sie sind, wer Ihre Kunden sind, und welche Position Sie im Markt einnehmen wollen.

Daraus entsteht ein durchgängiges visuelles System: Logo, Farben, Typografie, Bildsprache. Nicht aus einem Template, nicht aus einer Bibliothek, sondern individuell für Sie entwickelt. Mit einem Style-Mini-Manual als PDF, das jeder spätere Drucker, Grafiker oder Marketing-Dienstleister versteht und konsistent anwenden kann — von der Visitenkarte bis zur Website, vom Social-Media-Profil bis zur Fahrzeugbeschriftung.

Das Ergebnis: ein Auftritt, in dem Sie sich selbst wiederfinden — und in dem sich Ihre Wunschkunden wiederfinden.
TXT;

        $differentiation = <<<'TXT'
Gegen Fiverr / 99designs / Canva-Eigenbau: Logos aus der 50-Euro-Ecke sind meist nicht individuell — die Designer arbeiten mit Template-Bibliotheken, die hundertfach verkauft werden. Das heißt: Ihr Logo existiert in einer Variante schon bei einem Zahnarzt in Hamburg und einem Hundefrisör in Wien. Das fliegt dann auf, wenn Ihre Wunschkunden zufällig Google-Bildersuche nutzen und entdecken, dass ihr Logo bei fünf anderen ähnlich aussieht. Das kostet Vertrauen.

Gegen klassische Design-Agenturen: Klassische Design-Agenturen in Frankfurt bauen Corporate-Design-Manuals, die 120 Seiten lang sind, kosten 15.000 bis 50.000 €, und dauern drei bis sechs Monate. Das ist wunderbar für Mittelstands-Unternehmen oder Konzerne — für einen Gründer ist es overkill.

Gegen KI-generierte Logos: Tools wie Looka oder Brandmark werfen in 5 Minuten ein Logo aus. Das sieht im Frontend manchmal ordentlich aus. Hinter der Fassade fehlt aber alles, was ein Logo zu einem Markensystem macht: kein skalierbares SVG, keine Farb-Regeln für Druck, keine Typografie, kein Manual.
TXT;

        $growth = <<<'TXT'
Das Markensystem, das wir für Gründer bauen, ist bewusst ausbaubar. Der häufigste Erweiterungspfad unserer Kunden: Nach 6–12 Monaten kommen weitere Touchpoints dazu — Flyer, Broschüren, Messestand-Grafik, Fahrzeugbeschriftung, PowerPoint-Templates. Alle greifen auf das Style-Mini-Manual zurück und bleiben konsistent.

Nach 12–24 Monaten entsteht oft ein ausführlicheres Corporate-Design-Manual — manchmal bei uns, manchmal mit einer spezialisierten Agentur. Das Ausgangs-Manual ist dafür die Basis und muss nicht verworfen werden.

Bei wachsenden Teams wird das Manual dann zum Onboarding-Werkzeug: neue Mitarbeiter, neue Agenturen, neue Dienstleister — alle bekommen dasselbe Dokument und liefern konsistente Ergebnisse.
TXT;

        return [
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'is_active' => true,
            'sort_order' => 20,
            'slug' => ['de' => 'logo-corporate-identity-gruender', 'en' => 'logo-corporate-identity-gruender'],
            'title' => ['de' => 'Logo & Corporate Identity für Gründer', 'en' => 'Logo & Corporate Identity for Founders'],
            'meta_title' => ['de' => 'Logo & Corporate Identity für Gründer: Markenstart in 2 Wochen'],
            'meta_description' => ['de' => 'Logo, Farben, Typografie & ein nutzbares Style-Manual — Corporate Identity für Frankfurter Gründer. Druck- und webfertig, mit allen Varianten zum Festpreis.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Logo · Corporate Identity · Gründer',
                        'title' => 'Logo & Corporate Identity für Gründer — Markenstart in zwei Wochen',
                        'subtitle' => 'Ein Logo ist nicht nur ein Bild. Es ist das Zeichen, das Ihre Rechnung, Ihre Visitenkarte, Ihre Website, Ihren Social-Media-Auftritt und Ihre Fahrzeugbeschriftung zusammenhält. Wir liefern Gründerinnen und Gründern in Frankfurt ein vollständiges Markensystem — nicht nur ein Logo —, damit alles von Tag 1 konsistent wirkt.',
                        'icon' => 'sparkles',
                    ],
                    'why_native' => [
                        'title' => 'Wie wir Ihre Marke entwickeln',
                        'text' => $whyNativeText,
                        'items' => [
                            'Markenkern verstehen — wer Sie sind und was Sie versprechen',
                            'Wettbewerb analysieren — damit Ihr Auftritt wirklich differenziert',
                            'Visuelle Sprache entwickeln — individuell, nicht Template',
                            'Konsistenz von Anfang an — Logo, Farben, Schriften greifen ineinander',
                            'Klare Anwendungsregeln im PDF-Manual für alle späteren Touchpoints',
                            'Sie identifizieren sich mit dem Ergebnis — und Ihre Kunden auch',
                        ],
                    ],
                    'when' => [
                        'title' => 'Wann brauchen Sie mehr als nur ein Logo?',
                        'intro' => 'Ein reines Logo reicht, wenn Sie heute gründen und morgen nie wieder etwas drucken oder online veröffentlichen. In allen anderen Fällen lohnt sich ein Markensystem:',
                        'conditions' => [
                            'Sie brauchen Visitenkarten, Briefpapier, Rechnungen und eine Website — und alles soll einheitlich aussehen.',
                            'Sie werden in Social Media aktiv und brauchen konsistente Profilbilder und Cover-Grafiken.',
                            'Sie arbeiten mit Kooperationspartnern oder Dienstleistern (Drucker, Grafiker, Kampagnen-Agentur), die Ihre Marken-Vorgaben kennen müssen.',
                            'Sie wollen beim Skalieren nicht jedes Mal neu entscheiden, welche Farbe oder Schriftart Sie nehmen — sondern einmal entscheiden und dann anwenden.',
                            'Ihr Auftritt soll professionell wirken, nicht zusammengeklickt.',
                        ],
                    ],
                    'features' => [
                        'title' => 'Was zu einem Markensystem für Gründer gehört',
                        'intro' => 'Vier Bausteine — gemeinsam ergeben sie einen Auftritt, der konsistent wirkt und sich praktisch anwenden lässt:',
                        'items' => [
                            'Logo-System mit allen Varianten (Wortmarke, Wort-Bild-Marke, horizontale & quadratische Form, einfarbig, invertiert)',
                            'Farb-System mit Primär-, Sekundär- und Neutraltönen (Hex, RGB, CMYK, Pantone)',
                            'Typografie-System mit Haupt- und Body-Schrift plus System-Fallback',
                            'Style-Mini-Manual als PDF (8–12 Seiten) für Dienstleister, Drucker und spätere Agenturen',
                            'Favicon und Social-Media-Profilbilder in den Plattform-Formaten',
                            'Alle Quelldateien (SVG, PNG, PDF, AI/Figma) zur freien Weiterverwendung',
                        ],
                    ],
                    'process' => [
                        'title' => 'Wie Ihr Markensystem entsteht',
                        'steps' => [
                            ['title' => 'Briefing (60 Minuten)', 'description' => 'Wir sprechen über Ihre Zielgruppe, Ihre Positionierung, Ihren Wettbewerb und die Tonalität. Das ist keine Design-Show — das sind die Entscheidungen, die später jede Gestaltung prägen.'],
                            ['title' => 'Mood-Board & Richtungsfindung (3–4 Tage)', 'description' => 'Wir zeigen Ihnen zwei bis drei grundsätzliche Richtungen (seriös & klassisch vs. modern & reduziert vs. warm & empathisch). Sie wählen, wohin es geht — bevor wir auch nur einen Logoentwurf machen.'],
                            ['title' => 'Logo-Entwürfe (Woche 1)', 'description' => 'Drei Logo-Entwürfe in der gewählten Richtung. Sie bekommen sie in Anwendungs-Kontexten gezeigt (als Favicon, auf einer Visitenkarte, als Social-Profilbild) — nicht nur isoliert auf weißem Hintergrund.'],
                            ['title' => 'Korrektur-Runde (Woche 2)', 'description' => 'Ein Finetuning-Durchlauf ist im Festpreis enthalten. Sie wählen einen Entwurf und benennen gezielte Anpassungen.'],
                            ['title' => 'Finalisierung & Ableitung (Woche 2)', 'description' => 'Das finale Logo wird in allen Varianten (SVG, PNG, PDF) produziert, Farbsystem und Typografie werden definiert, das Style-Mini-Manual als PDF erstellt.'],
                            ['title' => 'Lieferung', 'description' => 'Alle Dateien kommen in einem sauber strukturierten Ordner. Sie können sie Ihrem Druckdienstleister, Steuerberater, einer späteren Agentur oder einem Grafik-Freelancer einfach übergeben.'],
                        ],
                    ],
                    'capabilities' => [
                        'title' => 'Was Sie bekommen',
                        'items' => [
                            'Logo in Wortmarke oder Wort-Bild-Marke',
                            'Horizontale Variante und quadratische/Kompakt-Variante',
                            'Schwarzweiß-Version (einfarbig)',
                            'Monochrom-Version (invertiert für dunkle Hintergründe)',
                            'Alle Logos als SVG (skalierbar), PNG (transparent), PDF (druckfertig)',
                            'Favicon (ICO + SVG + PNG-Varianten)',
                            'Social-Media-Profilbild (quadratisch, 1080×1080 px)',
                            'Social-Media-Header-Grafik (LinkedIn, Facebook — jeweils optimiert)',
                            'Farbpalette (Primär + Sekundär + Neutral, mit Hex, RGB, CMYK)',
                            'Typografie-Paarung (Haupt- + Body-Schrift, mit Google-Font-Alternativen)',
                            'Style-Mini-Manual (PDF, 8–12 Seiten)',
                            'Alle Quelldateien (AI / Figma / Sketch, je nach Ausgangsdatei)',
                        ],
                        'note' => 'Vollständige Corporate-Design-Manuals, Naming-Entwicklung und Marken-Recherchen sind eigenständige Leistungen außerhalb dieses Pakets.',
                    ],
                    'differentiation' => [
                        'title' => 'Warum nicht ein Fiverr-Logo?',
                        'text' => $differentiation,
                    ],
                    'growth' => ['title' => 'Was später kommt', 'text' => $growth],
                    'cta' => [
                        'title' => 'Bereit für Ihr Markensystem?',
                        'subtitle' => '30 Minuten Gespräch. Wir klären, ob eine reine Einzelleistung reicht oder ob das Gründerpaket mehr Sinn macht. Kostenlos, ohne Verkaufsdruck.',
                        'button_text' => 'Gespräch vereinbaren',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function spokeDigitaleGeschaeftsausstattung(): array
    {
        $whyNativeText = <<<'TXT'
Wir richten Gründern eine digitale Arbeitsumgebung ein, die vom ersten Tag funktioniert — und nicht nach drei Monaten in halbfertigen Tool-Setups, doppelten Rechnungen und vergessenen Passwörtern endet. Bevor wir konfigurieren, hören wir zu: Was bauen Sie auf? Wer arbeitet später mit? Welche Branche, welche Verpflichtungen, welche Schnittstellen?

Aus diesen Antworten leitet sich der Stack ab — kein Standard-Pack, sondern eine auf Sie zugeschnittene Auswahl von Werkzeugen, die nahtlos ineinandergreifen: Domain und E-Mail beim selben deutschen Provider, Cloud passend zur Datenschutz-Stufe Ihrer Branche, Buchhaltungs-Tool, das Ihr Steuerberater bereits kennt, CRM auf Gründer-Volumen dimensioniert.

Das Ergebnis ist eine konsistente, dokumentierte digitale Basis, in der Sie sich auskennen — und die spätere Mitarbeiter oder Dienstleister ohne Rätselraten übernehmen können.
TXT;

        $differentiation = <<<'TXT'
DIY ist möglich. Wenn Sie technisch versiert sind und 2–3 Wochenenden investieren möchten, kriegen Sie viele der Bausteine selbst hin. Die häufigsten Stolperfallen:

1. Domain und E-Mail bei unterschiedlichen Providern — bedeutet doppelte Rechnungen, doppelte Zugänge und fast immer SPF/DKIM-Probleme.

2. Rechtstexte aus dem Generator — rechtlich oft lückenhaft; die Generatoren werden inaktuell, sobald sich die Gesetzeslage ändert (was regelmäßig passiert).

3. Fragmentierte Tool-Landschaft — das CRM redet nicht mit dem Rechnungs-Tool, die Cloud nicht mit dem Kalender, am Ende heißt es für jede kleine Aufgabe: Wo war das nochmal?

4. Keine Dokumentation — Sie wissen nach sechs Monaten selbst nicht mehr, wo welches Passwort liegt, wie die E-Mail-Regeln funktionieren, wer Zugriff auf die Domain hat.

Unser Paket vermeidet diese Fallen strukturell: ein Provider, wo möglich, saubere Verbindungen zwischen den Komponenten, ein zentraler Passwort-Safe, ein kurzes Handbuch.
TXT;

        $growth = <<<'TXT'
Die digitale Geschäftsausstattung, die wir bauen, ist bewusst auf Gründer-Volumen dimensioniert — aber nicht auf Gründer-Volumen beschränkt. Alle Bausteine (Cloud, CRM, Buchhaltung, E-Mail) skalieren nach oben: mehr Nutzer, mehr Speicher, mehr Funktionen. Wenn Sie nach 12 Monaten einen ersten Mitarbeiter einstellen, kommt dieser ohne Reibung ins Setup.

Typische Erweiterungen: Nach 6–12 Monaten Terminbuchungs-System (Calendly, Cal.com) und Anbindung an Website und CRM; automatisierte Angebote/Rechnungen; Newsletter-Lösung. Nach 12–24 Monaten Erweiterung zu einer digitalen Plattform mit Kundenportal, wenn das Geschäftsmodell es verlangt; spezialisiertes CRM, wenn die Anzahl Kontakte über 500 geht. Nach 2+ Jahren Team-Tools, Integrationen, vollständige Compliance-Audits.
TXT;

        return [
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'is_active' => true,
            'sort_order' => 30,
            'slug' => ['de' => 'digitale-geschaeftsausstattung', 'en' => 'digitale-geschaeftsausstattung'],
            'title' => ['de' => 'Digitale Geschäftsausstattung', 'en' => 'Digital Business Setup'],
            'meta_title' => ['de' => 'Digitale Geschäftsausstattung: Was Gründer wirklich brauchen'],
            'meta_description' => ['de' => 'Was zu einer soliden digitalen Geschäftsausstattung gehört — von Domain und E-Mail über Rechtstexte bis Google Business Profile. Ohne Buzzwords, ohne Overkill.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Digitale Basis · Gründer',
                        'title' => 'Digitale Geschäftsausstattung — was Sie wirklich brauchen, und was nicht',
                        'subtitle' => 'Eine Website allein macht Sie noch nicht arbeitsfähig. Es gehört eine Reihe von digitalen Bausteinen dazu, die zusammen die Basis Ihrer unternehmerischen Tätigkeit bilden. Wir erklären, welche davon wirklich wichtig sind — und liefern sie Gründerinnen und Gründern in Frankfurt als Komplettpaket.',
                        'icon' => 'squares-2x2',
                    ],
                    'why_native' => [
                        'title' => 'Wie wir Ihre digitale Basis aufbauen',
                        'text' => $whyNativeText,
                        'items' => [
                            'Bestandsaufnahme zuerst — was Sie schon haben, was wegfällt, was hinzukommt',
                            'Stack auf Ihre Branche, Datenschutz-Stufe und Wachstumserwartung zugeschnitten',
                            'Alle Tool-Lizenzen auf Ihren Konten — keine Abhängigkeit von uns',
                            'Saubere Verbindungen zwischen den Komponenten (Domain ↔ E-Mail ↔ Cloud ↔ CRM)',
                            'Zentraler Passwort-Safe und Kurz-Handbuch — Übergabe-fähig an spätere Dienstleister',
                            'Sie kennen Ihr System und können es selbst pflegen',
                        ],
                    ],
                    'when' => [
                        'title' => 'Wann brauchen Sie eine strukturierte digitale Geschäftsausstattung?',
                        'intro' => 'Für manche Gründer reicht tatsächlich eine Website plus eine E-Mail-Adresse. Für die meisten — und für alle, die wachsen wollen — ist mehr sinnvoll:',
                        'conditions' => [
                            'Sie werden regelmäßig mit Kunden, Dienstleistern oder Lieferanten Dokumente austauschen und brauchen dafür einen sauberen Rahmen.',
                            'Sie planen, Mitarbeiter einzustellen oder mit Freelancern zusammenzuarbeiten — also brauchen Sie gemeinsame Ordner, Kalender, Aufgabenlisten.',
                            'Sie wollen von Tag 1 eine Buchhaltung, die Ihr Steuerberater ohne Ächzen übernehmen kann.',
                            'Sie arbeiten mit sensiblen Daten (Gesundheit, Recht, Finanzen) und müssen DSGVO-konform sein.',
                            'Sie wollen vermeiden, nach einem Jahr drei halbfertige Tool-Setups parallel laufen zu haben.',
                        ],
                    ],
                    'features' => [
                        'title' => 'Die Bausteine Ihrer digitalen Geschäftsausstattung',
                        'intro' => 'Acht Komponenten, die zusammen die digitale Basis Ihres Unternehmens bilden:',
                        'items' => [
                            'Domain & Business-E-Mail (DSGVO-konformer deutscher Provider, SPF/DKIM/DMARC korrekt)',
                            'Cloud-Speicher & Kollaboration (Nextcloud, Google Workspace oder Microsoft 365 mit AVV)',
                            'Rechtstexte & Compliance-Basis (Impressum, Datenschutz, Cookie-Banner)',
                            'Buchhaltungs- & Rechnungs-Setup (DATEV-kompatibel — Ihr Steuerberater wird es lieben)',
                            'Google Business Profile & lokale Sichtbarkeit (vollständig eingerichtet)',
                            'Social-Media-Handles (LinkedIn-Unternehmensseite, Instagram, Facebook nach Bedarf)',
                            'Einfaches CRM / Kontakt-Management (HubSpot Free, Pipedrive, Folk oder Airtable)',
                            'Dokumentation & Passwort-Safe (1Password / Bitwarden + Übergabe-fähiges Handbuch)',
                        ],
                    ],
                    'process' => [
                        'title' => 'So läuft das Setup ab',
                        'steps' => [
                            ['title' => 'Bestandsaufnahme (45 Minuten)', 'description' => 'Wir klären, was Sie schon haben (z.B. vielleicht schon eine Domain), was Sie ergänzen wollen, was Sie nicht brauchen.'],
                            ['title' => 'Stack-Empfehlung & Angebot (2–3 Tage)', 'description' => 'Sie bekommen eine klare Empfehlung, welche Tools wir vorschlagen und warum, inklusive Kosten in den ersten 12 Monaten.'],
                            ['title' => 'Einrichtung (Woche 1–2)', 'description' => 'Wir richten alle Systeme auf Ihren Accounts ein, konfigurieren die Anbindungen, hinterlegen Passwörter im Passwort-Manager.'],
                            ['title' => 'Übergabe & Einweisung (60 Minuten)', 'description' => 'Eine strukturierte Einweisung, wie alles zusammenhängt, wo Sie welche Daten finden, was Sie regelmäßig tun müssen.'],
                            ['title' => '30 Tage Support', 'description' => 'E-Mail-Support für die ersten 30 Tage — alle Fragen, die beim Einleben aufkommen, beantworten wir unkompliziert.'],
                        ],
                    ],
                    'capabilities' => [
                        'title' => 'Was wir einrichten',
                        'items' => [
                            'Domain inkl. DNSSEC, WHOIS-Privacy (soweit möglich)',
                            'Business-E-Mail (bis zu 3 Postfächer), SPF/DKIM/DMARC',
                            'Cloud-Ordnerstruktur (auf Ihrem Provider)',
                            'Rechtstexte (Impressum, Datenschutz, optional Widerruf/AGB)',
                            'Buchhaltungs-Setup (ein Tool nach Bedarf)',
                            'Google Business Profile (vollständig, inkl. Fotos & Kategorien)',
                            'Social-Media-Basics (LinkedIn + ein weiteres Netz nach Wahl)',
                            'CRM-Setup (Tier 0 — kostenfreie Ebene)',
                            'Passwort-Manager mit allen Zugängen',
                            'Dokumentation (Kurz-Handbuch als PDF)',
                        ],
                        'note' => 'Laufende Social-Media-Pflege, monatliche Buchhaltung, individuelle Workflows und Marken-Recherche sind separate Leistungen.',
                    ],
                    'differentiation' => [
                        'title' => 'Warum nicht DIY?',
                        'text' => $differentiation,
                    ],
                    'growth' => ['title' => 'Was später kommt', 'text' => $growth],
                    'cta' => [
                        'title' => 'Bereit für eine vollständige digitale Basis?',
                        'subtitle' => '30 Minuten Kennenlern-Gespräch. Wir klären, was Sie wirklich brauchen — und was Sie sich sparen können. Kostenlos, ohne Verkaufsdruck.',
                        'button_text' => 'Gespräch vereinbaren',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function spokeSocialMediaSetup(): array
    {
        $whyNativeText = <<<'TXT'
Wir bauen Social-Media-Profile, die zu Ihrem Auftritt passen und Sie nicht in eine tägliche Content-Tretmühle zwingen. Bevor wir Profile anlegen, klären wir die wichtigste Frage: Wo ist Ihre Zielgruppe wirklich — und welche Plattformen können Sie ehrlich getrost ignorieren?

Daraus entsteht ein konsistentes Setup: Profile, die vollständig und professionell ausgefüllt sind, Grafiken, die aus Ihrer Corporate Identity abgeleitet sind (kein Mischmasch aus Canva-Templates), und drei bis fünf Eröffnungs-Posts, die Ihre Stimme transportieren — nicht die einer KI oder eines Beraters.

Dazu eine realistische Content-Routine: 1 Post pro Woche, ein paar gezielte Reactions täglich, dazu eine Liste mit 20 thematischen Ideen, die zu Ihrer Gründung passen. So bleiben Sie sichtbar, ohne dass Social Media zu einer zweiten Vollzeit-Stelle wird.
TXT;

        $differentiation = <<<'TXT'
Selbst einrichten ist machbar. Drei häufige Probleme dabei:

Uneinheitliches Branding. Das LinkedIn-Profilbild ist quadratisch und abgeschnitten, das Instagram-Profilbild ist mit einem anderen Filter belegt, der Facebook-Header stammt aus Canva mit einem anderen Farbton als die Website. Jedes einzelne Detail klein — die Summe wirkt unprofessionell.

Halbfertige Profile. Die Beschreibung ist in einer kurzen Google-Pause hingeschrieben, die Branche ist nicht gesetzt, das Unternehmensprofil verweist auf die persönliche E-Mail. Besucher, die das Profil öffnen, bekommen sofort das Gefühl: Die sind neu und nicht wirklich dabei.

Falsche Plattform. Viele Gründer starten auf Instagram, obwohl ihre Zielgruppe ausschließlich auf LinkedIn ist — oder umgekehrt. Das kostet drei bis sechs Monate, bis man merkt: falscher Kanal.

Das Social-Media-Setup-Paket löst alle drei Probleme auf einen Schlag: konsistentes Branding aus der Corporate Identity, vollständig gepflegte Profile, klare Plattform-Wahl basierend auf Ihrer Zielgruppe.
TXT;

        $growth = <<<'TXT'
Nach 3–6 Monaten haben Sie erste Daten: Welche Inhalte wirken bei Ihrer Zielgruppe? Welche Plattform liefert tatsächlich Anfragen? An dem Punkt können Sie fundiert entscheiden, ob Sie eine Stufe höher schalten — mit Video-Content, bezahlten Kampagnen, einem regelmäßigen Newsletter oder einem Podcast.

Typische Erweiterungs-Pfade: Nach 6–12 Monaten erste Ad-Kampagnen (oft LinkedIn Lead-Gen Forms für B2B, Meta-Ads für B2C-Lokales); Newsletter-Anbindung; ggf. Podcasts oder Fachartikel auf der Website. Nach 12–24 Monaten strukturiertes Content-Marketing, spezialisierte Tools (Buffer, Later, Metricool), ggf. ein externer Content-Freelancer. Nach 2+ Jahren Community-Aufbau, Thought-Leadership, Event-Marketing.
TXT;

        return [
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'is_active' => true,
            'sort_order' => 40,
            'slug' => ['de' => 'social-media-setup-gruender', 'en' => 'social-media-setup-gruender'],
            'title' => ['de' => 'Social Media Setup für Gründer', 'en' => 'Social Media Setup for Founders'],
            'meta_title' => ['de' => 'Social Media Setup für Gründer: Profile, Branding & erste Posts'],
            'meta_description' => ['de' => 'LinkedIn, Instagram & Co. professionell einrichten: Profile, Branding, erste Posts und eine schlanke Content-Routine für Frankfurter Gründer. Zum Festpreis.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Social Media · Gründer',
                        'title' => 'Social Media Setup für Gründer — professionell starten, nicht unter Dauer-Content-Stress',
                        'subtitle' => 'Eigentlich bräuchten Sie als Gründer gar keine Social-Media-Präsenz — wenn da nicht die Tatsache wäre, dass zukünftige Kunden, Partner und Talente Sie dort suchen. Wir bauen ein schlankes, konsistentes Setup auf, das Sie nicht jeden Morgen unter Posting-Druck setzt.',
                        'icon' => 'chat-bubble-left-right',
                    ],
                    'why_native' => [
                        'title' => 'Wie wir Ihren Social-Media-Auftritt aufsetzen',
                        'text' => $whyNativeText,
                        'items' => [
                            'Plattform-Wahl auf Basis Ihrer Zielgruppe — keine Aktivität auf Kanälen ohne Wirkung',
                            'Branding aus Ihrer Corporate Identity — konsistent zur Website',
                            'Vollständig ausgefüllte Profile (alle Felder, die Algorithmen bewerten)',
                            'Eröffnungsposts in Ihrer Stimme — wir hören zu, bevor wir formulieren',
                            'Realistische Content-Routine: 30–60 Minuten pro Woche',
                            '20 vorbereitete Content-Ideen für die ersten 6 Monate',
                        ],
                    ],
                    'when' => [
                        'title' => 'Wann lohnt sich Social Media für Gründer?',
                        'intro' => 'Nicht jede Gründung braucht Social Media. Aber die meisten profitieren davon:',
                        'conditions' => [
                            'Ihre Kunden oder Empfehlungsgeber sind auf LinkedIn unterwegs (B2B, Beratung, Agentur, freie Berufe — fast immer ja).',
                            'Sie positionieren sich mit Expertise oder Persönlichkeit, nicht nur über Preis.',
                            'Sie wollen nicht nur über Google gefunden werden, sondern auch über Empfehlungen.',
                            'Sie wollen einen Empfänger-Stapel aufbauen — Menschen für spätere Newsletter, Produkt-Einführungen oder Event-Einladungen.',
                            'Sie wollen Bewerbungen von Freelancern oder späteren Mitarbeitern anziehen.',
                        ],
                        'note' => 'Wenn Ihre Zielgruppe ausschließlich über Google oder lokale Walk-Ins kommt, ist Social Media eine Zusatzkür. Wir sagen Ihnen im Briefing ehrlich, was sich lohnt.',
                    ],
                    'features' => [
                        'title' => 'Welche Plattform für welche Gründer-Art',
                        'intro' => 'Vier Plattformen, klare Zuordnung — wir empfehlen 2 bis 3 davon, nicht alle:',
                        'items' => [
                            'LinkedIn — der B2B-Standard für Beratung, Agentur, Tech, Finanzen, freie Berufe',
                            'Instagram — visuell und lokal: Handwerk, Gesundheit, Wellness, Gastronomie, persönliche Marken',
                            'Facebook — Community-orientiert: lokale Vereine, ältere Zielgruppen, Events',
                            'XING — deutschsprachiger B2B-Raum, parallel zu LinkedIn üblich',
                            'TikTok / YouTube / Threads — bewusst weglassen, außer Ihre Zielgruppe ist klar dort',
                        ],
                    ],
                    'process' => [
                        'title' => 'Wie das Setup abläuft',
                        'steps' => [
                            ['title' => 'Plattform-Briefing (45 Minuten)', 'description' => 'Wir klären Zielgruppe, Ihre Person, Ihre Tonalität, welche Plattformen wirklich zu Ihrem Geschäftsmodell passen.'],
                            ['title' => 'Branding-Abgleich (2–3 Tage)', 'description' => 'Profil- und Cover-Grafiken werden aus Ihrem Corporate-Identity-System abgeleitet. Alle passen visuell zur Website und zum Logo.'],
                            ['title' => 'Profil-Einrichtung (Woche 1)', 'description' => 'Unternehmensprofile werden komplett aufgesetzt, persönliches Profil des Gründers wird optimiert. Alle Felder werden strategisch gefüllt.'],
                            ['title' => 'Erste Posts (Woche 1–2)', 'description' => 'Wir schreiben gemeinsam drei bis fünf Eröffnungsposts — Vorstellung, Ihre Expertise, erste Einblicke. Auf Ihre Sprache zugeschnitten.'],
                            ['title' => 'Content-Routine & Einweisung (60 Minuten)', 'description' => 'Eine schlanke, realistische Routine: 1 Post pro Woche, 2 Reactions pro Tag. Plus 20 Content-Ideen für die ersten sechs Monate.'],
                        ],
                    ],
                    'capabilities' => [
                        'title' => 'Was dabei ist',
                        'items' => [
                            'Plattform-Strategie (Auswahl: 2–3 Plattformen nach Fit)',
                            'Unternehmensprofile (LinkedIn, optional Facebook, Xing)',
                            'Persönliche Profile (Gründer-Profil auf LinkedIn, ggf. Xing)',
                            'Alle nötigen Grafiken (Profilbild, Cover, Bio-Badges) in Formaten der jeweiligen Plattform',
                            '3–5 Eröffnungs-Posts (gemeinsam mit Ihnen geschrieben, veröffentlichungsfertig)',
                            'Content-Ideenliste (20 konkrete Themen für die ersten sechs Monate)',
                            'Content-Routine als PDF (was Sie pro Woche tun, in 30–60 Min)',
                            'Analytics-Grundeinstellung (LinkedIn Insights, Meta Business Suite)',
                            'Einweisung (60 Min, aufgezeichnet)',
                        ],
                        'note' => 'Laufende Content-Produktion, Paid-Campaigns, Influencer-Outreach und Video-Produktion sind separate Leistungen.',
                    ],
                    'differentiation' => [
                        'title' => 'Warum nicht selbst einrichten?',
                        'text' => $differentiation,
                    ],
                    'growth' => ['title' => 'Was später kommt', 'text' => $growth],
                    'cta' => [
                        'title' => 'Bereit für ein schlankes Social-Media-Setup?',
                        'subtitle' => '30 Minuten Kennenlern-Gespräch. Wir klären, welche Plattformen zu Ihrem Gründungs-Fall passen und welche Sie getrost ignorieren können. Kostenlos.',
                        'button_text' => 'Gespräch vereinbaren',
                    ],
                ],
            ],
        ];
    }

    // -----------------------------------------------------------------
    // GUIDES
    // -----------------------------------------------------------------

    /**
     * @return array<int, array<string, mixed>>
     */
    private function guidesData(): array
    {
        return [
            $this->guideChecklist(),
            $this->guideWebsiteKosten(),
            $this->guideImpressumPflicht(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function guideChecklist(): array
    {
        $intro = <<<'TXT'
Wenn Sie gerade gründen, stehen Sie vor einer Welle an Einzelentscheidungen — Domain, E-Mail, Logo, Rechtstexte, Buchhaltung, Website, Social Media, CRM, Cloud. Jede einzelne davon wirkt klein. Zusammen verschlingen sie in der Regel mehr Zeit als das eigentliche Gründen. Diese Checkliste geht die vollständige digitale Geschäftsausstattung für Existenzgründer durch — praxisorientiert, mit Budgetrahmen pro Position, mit konkreten Tool-Empfehlungen, ohne Buzzwords.
TXT;

        $section0 = <<<'HTML'
<p>Drei Entscheidungen müssen vor jeder digitalen Einrichtung getroffen sein, weil alles andere daran hängt:</p><p><strong>1. Ihre Gründungsform.</strong> Einzelunternehmen oder Freiberufler? UG oder GmbH? Diese Entscheidung beeinflusst, welche Rechtstexte Sie brauchen, wie die Rechnungen aussehen müssen und welche Rechtsform im Impressum steht. Klären Sie das mit Ihrem Steuerberater — fast alle Folge-Schritte hängen daran.</p><p><strong>2. Ihr Firmenname (und ob er frei ist).</strong> Der Name muss (a) rechtlich zulässig sein, (b) markenrechtlich frei, (c) als Domain noch verfügbar. Prüfen Sie in dieser Reihenfolge: DPMA-Marken-Recherche (kostenlos), Handelsregister-Check, und zuletzt Domain-Check bei einem seriösen Registrar.</p><p><strong>3. Ihre Kernbotschaft in einem Satz.</strong> Wen bedienen Sie, mit welchem Angebot, in welchem Rahmen. „Physiotherapie-Praxis in Frankfurt-Bornheim mit Fokus auf Sportverletzungen" ist ein brauchbarer Satz. „Innovative Gesundheits-Lösungen für den modernen Kunden" ist keiner.</p><p><em>Zeitaufwand Schritt 0: 1–2 Tage, verteilt über 1–2 Wochen.</em></p>
HTML;

        $section1 = <<<'HTML'
<p><strong>Was Sie brauchen:</strong> Eine eigene Domain (<code>ihrname.de</code>) und professionelle E-Mail-Adressen auf dieser Domain (<code>info@ihrname.de</code>, <code>name@ihrname.de</code>). Gmail-Adressen auf der Visitenkarte wirken wie Hobby-Projekt.</p><p><strong>Kostenrahmen:</strong> 10–30 € pro Jahr für eine .de-Domain. 3–8 € pro Monat für ein professionelles E-Mail-Postfach bei einem deutschen Provider.</p><p><strong>Empfehlung:</strong> Domain und E-Mail beim selben deutschen Provider registrieren (mailbox.org, Strato, IONOS Cloud, Hetzner). Gründe: DSGVO-Konformität, ein einziger Ansprechpartner, SPF/DKIM/DMARC werden automatisch korrekt gesetzt.</p><ul><li>Der Registrar muss DNSSEC unterstützen</li><li>Der E-Mail-Provider muss SPF, DKIM und DMARC korrekt konfigurieren</li><li>Vermeiden Sie kostenlose E-Mail-Angebote bei unbekannten Providern</li></ul><p><em>Zeitaufwand: 2–3 Stunden für die Einrichtung.</em></p>
HTML;

        $section2 = <<<'HTML'
<p><strong>Was Sie brauchen:</strong> Ein Logo (Wortmarke oder Wort-Bild-Marke), eine Farbpalette, eine Typografie-Kombination, ein minimales Style-Mini-Manual als PDF.</p><p><strong>Kostenrahmen:</strong> Fiverr/99designs 50–500 € (oft Template-basiert), Freelancer 800–2.500 €, Agentur-Paket 2.000–5.000 €, volles Corporate-Design-Projekt 10.000–50.000 € (für Gründer overkill).</p><p><strong>Empfehlung:</strong> Sweet Spot ist ein Paket mit Logo, Farben, Typografie und Mini-Manual als PDF. Mehr dazu in unserer Leistung <a href="/loesungen/logo-corporate-identity-gruender">Logo &amp; Corporate Identity für Gründer</a>.</p><p><em>Zeitaufwand: 2 Wochen für ein solides Paket.</em></p>
HTML;

        $section3 = <<<'HTML'
<p><strong>Was Sie brauchen:</strong> Eine Website mit mindestens Startseite, Über uns, Leistungen, Kontakt, Impressum, Datenschutz. Responsive, schnell, rechtssicher.</p><p><strong>Kostenrahmen:</strong> Baukasten (Jimdo, Wix, IONOS) 10–40 €/Monat (Selbstbau), Freelancer 1.500–4.000 €, Agentur-Paket 2.500–6.000 €, individuelle Website 5.000–15.000 €.</p><ul><li>Cookie-Banner mit Consent-Management (Pflicht seit 2020)</li><li>Impressum, das zur Gründungsform passt</li><li>Datenschutzerklärung, die mit den eingesetzten Tools konsistent ist</li><li>SSL-Zertifikat — heute Standard</li></ul><p>Mehr dazu in unserem Ratgeber <a href="/ratgeber/website-kosten-existenzgruender">Was kostet eine Website für Existenzgründer?</a> und unserer Leistung <a href="/loesungen/website-fuer-existenzgruender">Website für Existenzgründer</a>.</p><p><em>Zeitaufwand: 4–8 Wochen bei einem Dienstleister.</em></p>
HTML;

        $section4 = <<<'HTML'
<p><strong>Was Sie brauchen:</strong></p><ul><li><strong>Impressum</strong> — nach §5 TMG/DDG und §18 MStV</li><li><strong>Datenschutzerklärung</strong> — nach Art. 13 DSGVO</li><li><strong>Cookie-Banner</strong> mit granularer Einwilligung</li><li><strong>Widerrufsbelehrung</strong> — nur bei Online-Verträgen mit Verbrauchern</li><li><strong>AGB</strong> — empfohlen, nicht immer Pflicht</li></ul><p><strong>Kostenrahmen:</strong> Generator 0–15 €/Monat, Fachanwalt 500–2.500 €, Agentur-Paket im Gründerpaket enthalten.</p><p>Bei sensiblen Daten (Gesundheit, Recht, Finanzen, Kinder) direkt Fachanwalt. Mehr im <a href="/ratgeber/impressum-pflicht-selbststaendige">Impressum-Pflicht-Ratgeber</a>.</p><p><em>Zeitaufwand: 2–4 Stunden Generator, 1–2 Wochen Fachanwalt.</em></p>
HTML;

        $section5 = <<<'HTML'
<p><strong>Was Sie brauchen:</strong> Ein Rechnungs-Tool, das GoBD-konform und DATEV-kompatibel ist.</p><ul><li><strong>sevDesk</strong> — solider Marktstandard, 16–30 €/Monat</li><li><strong>lexoffice</strong> — ähnlich, 10–30 €/Monat</li><li><strong>Papierkram</strong> — günstiger (ab 8 €/Monat)</li></ul><p>Pflicht-Merkmale jeder Rechnung: Name + Adresse beider Parteien, Steuernummer oder USt-ID, fortlaufende Rechnungsnummer, Leistungsdatum, Bezeichnung der Leistung, Nettobetrag + Mehrwertsteuer. Ab 2025 zusätzlich die E-Rechnungs-Pflicht im B2B-Bereich.</p><p><em>Zeitaufwand: 1 Nachmittag.</em></p>
HTML;

        $section6 = <<<'HTML'
<p><strong>Kostenrahmen:</strong> Kostenlos.</p><ul><li>Korrekte Adresse (exakt gleich wie auf Website und Impressum)</li><li>Primär-Kategorie und 2–4 Sekundär-Kategorien</li><li>Öffnungszeiten</li><li>Beschreibung (750 Zeichen) mit Ihren Ziel-Keywords</li><li>Mindestens 10 Fotos</li><li>Verifizierung (meist per Postkarte)</li></ul><p><strong>Häufigster Fehler:</strong> Falsche Kategorie. Das ist laut Whitespark der #1 negative Ranking-Faktor im Local Pack.</p><p><em>Zeitaufwand: 2–3 Stunden initial, plus Verifizierungs-Wartezeit.</em></p>
HTML;

        $section7 = <<<'HTML'
<p>Details im ausführlichen <a href="/loesungen/social-media-setup-gruender">Social Media Setup für Gründer</a>.</p><ul><li>B2B-Gründer: LinkedIn ist Pflicht, XING optional.</li><li>B2C-/Lokal-Gründer: Instagram und/oder Facebook — je nach Zielgruppen-Alter.</li><li>In allen Fällen: konsistentes Branding, vollständig ausgefüllte Profile, 3–5 Eröffnungsposts.</li></ul><p><strong>Kostenrahmen:</strong> Kostenlos (nur Ihre Zeit, 1–2 Tage).</p>
HTML;

        $section8 = <<<'HTML'
<ul><li>Cloud-Speicher (Nextcloud, Google Workspace, Microsoft 365 — DSGVO-konform konfiguriert)</li><li>Kalender (meist im Cloud-Paket enthalten)</li><li>Passwort-Manager (1Password, Bitwarden) für alle Zugänge</li></ul><p><strong>Kostenrahmen:</strong> Nextcloud 3–10 €/Monat, Google Workspace ~6 €/Monat, Microsoft 365 ~6 €/Monat, 1Password ~8 €/Monat (Bitwarden kostenlos bis 2 Nutzer).</p><p><em>Zeitaufwand: 1 Nachmittag.</em></p>
HTML;

        $section9 = <<<'HTML'
<ul><li><strong>HubSpot Free</strong> — kostenlos</li><li><strong>Pipedrive Starter</strong> — ~14 €/Monat</li><li><strong>Folk</strong> — ~15 €/Monat</li><li><strong>Airtable</strong> — flexibel, ab 10 €/Monat</li></ul><p>Starten Sie mit HubSpot Free. Wechsel zu Pipedrive/Salesforce ist später einfach.</p><p><em>Zeitaufwand: 2–3 Stunden Initial-Setup.</em></p>
HTML;

        $sectionBudget = <<<'HTML'
<p><strong>DIY:</strong> ~100–200 € einmalig + ~40–100 €/Monat laufend. Zeitaufwand: 4–8 Wochen, oft mehr.</p><p><strong>Agentur-Paket:</strong> 2.500–6.000 € einmalig + ~40–80 €/Monat. Zeitaufwand: 4–6 Wochen, davon 5–10 Stunden Ihre eigene Zeit.</p><p><strong>Was Sie beim Paket kaufen, ist nicht nur das Ergebnis, sondern die Zeit.</strong> Bei einem Stundensatz von 50 € brechen DIY-Ansätze nach 40–60 Stunden in die Region, in der ein Agentur-Paket günstiger ist — und Sie haben trotzdem noch keinen Steuerberater-tauglichen Workflow.</p><p>Falls das Paket-Modell für Sie passt: <a href="/loesungen/gruenderpaket-frankfurt">Das Gründerpaket Frankfurt im Detail</a>.</p>
HTML;

        return [
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 50,
            'slug' => ['de' => 'geschaeftsausstattung-gruendung-checkliste', 'en' => 'geschaeftsausstattung-gruendung-checkliste'],
            'title' => ['de' => 'Geschäftsausstattung für Existenzgründer — die komplette Checkliste', 'en' => 'Business Setup Checklist for Founders'],
            'meta_title' => ['de' => 'Checkliste: Geschäftsausstattung für Existenzgründer 2026'],
            'meta_description' => ['de' => 'Die komplette digitale Checkliste für Existenzgründer — von Domain & Website über Rechtstexte bis Google Business Profile. Mit Frankfurt-spezifischen Tipps.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Checkliste · Existenzgründung',
                        'subtitle' => 'Was Gründer wirklich brauchen, um professionell und rechtssicher zu starten — und was sie sich sparen können. Praxisorientiert, mit Budgetrahmen pro Punkt.',
                    ],
                    'intro' => ['text' => $intro],
                    'sections' => [
                        ['title' => 'Schritt 0: Bevor der digitale Teil losgeht', 'content' => $section0],
                        ['title' => '1. Domain & Business-E-Mail (Pflicht)', 'content' => $section1],
                        ['title' => '2. Corporate Identity & Logo', 'content' => $section2],
                        ['title' => '3. Website (Pflicht für die meisten Gründer)', 'content' => $section3],
                        ['title' => '4. Rechtstexte & Compliance (Pflicht — keine Option)', 'content' => $section4],
                        ['title' => '5. Buchhaltung & Rechnungsstellung (Pflicht)', 'content' => $section5],
                        ['title' => '6. Google Business Profile & lokale Sichtbarkeit', 'content' => $section6],
                        ['title' => '7. Social Media Setup', 'content' => $section7],
                        ['title' => '8. Cloud, Kollaboration & Passwort-Management', 'content' => $section8],
                        ['title' => '9. CRM / Kontakt-Management (empfohlen)', 'content' => $section9],
                        ['title' => 'Budget-Übersicht: Was kostet das komplette digitale Setup?', 'content' => $sectionBudget],
                    ],
                    'cta' => [
                        'title' => 'Ready, aber keine Zeit für DIY?',
                        'subtitle' => 'Wir packen die gesamte digitale Geschäftsausstattung in ein Paket — Website, Logo, DSGVO, E-Mail, GBP, Social — zum Festpreis. 30 Minuten Gespräch reichen.',
                        'button_text' => 'Gründerpaket-Gespräch anfragen',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function guideWebsiteKosten(): array
    {
        $intro = <<<'TXT'
Was kostet eine Website? ist die am häufigsten gestellte Frage vor jeder Gründung — und die am häufigsten schlecht beantwortete. Die ehrliche Antwort ist: zwischen 0 und 50.000 €. Der Unterschied liegt nicht primär in billiger oder teurer, sondern in was genau ist das Ergebnis. Dieser Ratgeber gibt Ihnen eine realistische Preisrange für die vier Wege, auf denen Gründer heute zu einer Website kommen.
TXT;

        $sectionTable = <<<'HTML'
<table><thead><tr><th>Weg</th><th>Einmal-Kosten</th><th>Laufende Kosten</th><th>Zeitaufwand</th><th>Wartungsrisiko</th></tr></thead><tbody><tr><td>Baukasten (DIY)</td><td>0 €</td><td>10–40 €/Mo</td><td>40–80 Stunden</td><td>Hoch — alles selbst</td></tr><tr><td>Freelancer</td><td>1.500–4.000 €</td><td>10–30 €/Mo</td><td>10–20 Stunden</td><td>Mittel</td></tr><tr><td>Agentur-Gründerpaket</td><td>2.500–6.000 €</td><td>0–40 €/Mo (erstes Jahr oft inkl.)</td><td>8–15 Stunden</td><td>Niedrig</td></tr><tr><td>Individuelle Entwicklung</td><td>7.000–25.000 €</td><td>100–500 €/Mo</td><td>20–40 Stunden</td><td>Mittel</td></tr></tbody></table><p><em>Alle Zahlen sind Marktdurchschnitte für deutsche Anbieter in 2026.</em></p>
HTML;

        $sectionDiy = <<<'HTML'
<p><strong>Was Sie zahlen:</strong> 0 € einmalig. 10–40 € pro Monat. Bei 24 Monaten ~250–1.000 €.</p><p><strong>Was Sie bekommen:</strong> Konto, Template-Pool, grafischen Editor, Grundgerüst. Rechtstexte meist via Generator.</p><p><strong>Was Sie einbringen:</strong> Ihre Zeit. Realistisch sind 40–80 Stunden, bis eine Baukasten-Seite professionell wirkt.</p><ul><li>Premium-Tarife für Grundfunktionen (eigene Domain, SSL, Cookie-Banner)</li><li>Transaktionsgebühren pro Verkauf/Buchung</li><li>Lock-In: Content nicht sauber exportierbar</li><li>SEO-Limits</li></ul><p><strong>Wann das passt:</strong> Sie sind technisch fit, haben echte 40+ Stunden, niedrige SEO-Sensibilität.</p><p><strong>Wann nicht:</strong> Sie konkurrieren über Google, Sie haben rechtssensible Daten, Sie wollen messbares Wachstum.</p>
HTML;

        $sectionFreelancer = <<<'HTML'
<p><strong>Was Sie zahlen:</strong> 1.500–4.000 € einmalig. 10–30 €/Monat für Hosting, Domain, E-Mail.</p><p><strong>Was Sie bekommen:</strong> Eine individuell gebaute Website, meist auf WordPress oder Webflow. Technische Qualität schwankt stark.</p><p><strong>Was oft NICHT enthalten ist:</strong></p><ul><li>Rechtstexte</li><li>SEO-Grundlagen</li><li>Google Business Profile</li><li>Einweisung / Dokumentation</li><li>Cookie-Banner mit Consent-Management</li></ul><p><strong>Versteckte Kosten:</strong></p><ul><li>Umfang-Drift bei Stundenabrechnung</li><li>Wartungslücke nach Projektende</li><li>Plugin-Abhängigkeit (15–25 Plugins = Sicherheits- und Update-Aufgaben)</li></ul>
HTML;

        $sectionAgentur = <<<'HTML'
<p><strong>Was Sie zahlen:</strong> 2.500–6.000 € einmalig. Laufende Kosten oft im ersten Jahr enthalten.</p><p><strong>Was Sie bekommen:</strong> Website + Logo/CI + Rechtstexte + DSGVO + GBP + teilweise Social Media + Einweisung + Post-Launch-Support. Ein Ansprechpartner, eine Rechnung, ein Festpreis.</p><p><strong>Wie Sie ein gutes Gründerpaket erkennen:</strong></p><ul><li>Transparenter Festpreis (Ab X € mit Leistungsliste)</li><li>Verbindlicher Zeitrahmen (4–6 Wochen als Festtermin)</li><li>Rechtstexte inklusive, auf Gründungsform zugeschnitten</li><li>Einweisung + 30 Tage Support nach Launch</li><li>Echte Kunden-Referenzen, idealerweise aus der Region</li></ul><p>Unser <a href="/loesungen/gruenderpaket-frankfurt">Gründerpaket Frankfurt</a> liegt in dieser Kategorie.</p>
HTML;

        $sectionIndividual = <<<'HTML'
<p><strong>Was Sie zahlen:</strong> 7.000–25.000 € einmalig. Plattformen, Shops, Kundenportale liegen deutlich höher.</p><p><strong>Was Sie bekommen:</strong> Maßgeschneiderte Lösung für speziellen Anwendungsfall.</p><p><strong>Wann das passt:</strong> Ihre Website ist ein Produkt, kein Visitenkarten-Ersatz; spezielle Funktionen, die Standard-Pakete nicht abbilden.</p>
HTML;

        $sectionDecide = <<<'HTML'
<p><strong>1. Wie wettbewerbsintensiv ist Ihr Markt?</strong></p><ul><li>Niedrig: Baukasten oder Freelancer reichen.</li><li>Mittel: Gründerpaket ist der Sweet Spot.</li><li>Hoch: Gründerpaket oder individuelle Entwicklung.</li></ul><p><strong>2. Wieviel Zeit haben Sie wirklich?</strong> Wenn Sie neben der Gründung auch noch Patienten behandeln, Mandanten beraten oder Produkte bauen müssen, sind 40+ Stunden für DIY unrealistisch.</p><p><strong>3. Wie rechtssensibel ist Ihr Feld?</strong></p><ul><li>Rechtssensibel (Gesundheit, Recht, Finanzen, Kinder): Nie DIY-Rechtstexte.</li><li>Normal: Generator-Rechtstexte sind akzeptabel, müssen gepflegt werden.</li></ul>
HTML;

        $sectionMonthly = <<<'HTML'
<ul><li><strong>Domain:</strong> 10–30 €/Jahr</li><li><strong>Business-E-Mail:</strong> 3–10 €/Monat</li><li><strong>Hosting:</strong> 5–30 €/Monat</li><li><strong>Cookie-Banner:</strong> 0–15 €/Monat</li><li><strong>Rechtstexte-Generator:</strong> 5–15 €/Monat</li><li><strong>Buchhaltung:</strong> 8–30 €/Monat</li><li><strong>Passwort-Manager:</strong> 0–8 €/Monat</li><li><strong>Cloud:</strong> 3–10 €/Monat</li></ul><p>Realistisch: <strong>30–100 € pro Monat</strong>.</p>
HTML;

        return [
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 60,
            'slug' => ['de' => 'website-kosten-existenzgruender', 'en' => 'website-kosten-existenzgruender'],
            'title' => ['de' => 'Was kostet eine Website für Existenzgründer?', 'en' => 'Website Costs for Founders'],
            'meta_title' => ['de' => 'Website-Kosten für Existenzgründer: Was wirklich realistisch ist'],
            'meta_description' => ['de' => 'Was kostet eine professionelle Website für Existenzgründer in Frankfurt? Transparente Preisrange für DIY, Freelancer, Agentur-Paket — mit Entscheidungs-Leitfaden.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Ratgeber · Kosten',
                        'subtitle' => 'Transparente Preisrange für die häufigsten Optionen — DIY, Freelancer, Agentur-Paket — mit Entscheidungs-Leitfaden, was sich für welche Gründungs-Art lohnt.',
                    ],
                    'intro' => ['text' => $intro],
                    'sections' => [
                        ['title' => 'Die vier Wege zu einer Gründer-Website — im Überblick', 'content' => $sectionTable],
                        ['title' => 'Weg 1: DIY-Baukasten (Jimdo, Wix, IONOS, Squarespace, WordPress.com)', 'content' => $sectionDiy],
                        ['title' => 'Weg 2: Freelancer (Web-Entwickler oder Designer auf Einzelbasis)', 'content' => $sectionFreelancer],
                        ['title' => 'Weg 3: Agentur-Gründerpaket (Komplettlösung)', 'content' => $sectionAgentur],
                        ['title' => 'Weg 4: Individuelle Entwicklung', 'content' => $sectionIndividual],
                        ['title' => 'Welcher Weg passt zu Ihrer Gründung? Entscheidungs-Leitfaden', 'content' => $sectionDecide],
                        ['title' => 'Unabhängig vom Weg: Was Sie monatlich einplanen sollten', 'content' => $sectionMonthly],
                    ],
                    'cta' => [
                        'title' => 'Noch unsicher, welcher Weg für Sie passt?',
                        'subtitle' => 'Wir sprechen unverbindlich 30 Minuten mit Ihnen. Am Ende wissen Sie, welcher der vier Wege für Ihre Gründung der sinnvolle ist — auch wenn das nicht der Weg zu uns ist.',
                        'button_text' => 'Kennenlern-Gespräch anfragen',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function guideImpressumPflicht(): array
    {
        $intro = <<<'TXT'
Impressum und Datenschutzerklärung sind die am meisten unterschätzten Elemente einer Gründer-Website. Sie werden oft in der letzten halben Stunde vor Launch aus einem Generator zusammenkopiert — und genau darin liegt das Risiko. Abmahnungen im Umfeld von DSGVO und TMG/MStV sind ein eigenes Geschäftsmodell geworden. Der typische Streitwert liegt zwischen 300 € und 5.000 €. Hinweis: Dieser Ratgeber ist eine Orientierung, keine Rechtsberatung. Für eine verbindliche Einschätzung bitte einen Fachanwalt für IT-Recht konsultieren.
TXT;

        $sectionLaw = <<<'HTML'
<p>Die Impressum-Pflicht ist in Deutschland zweigeteilt:</p><ul><li><strong>§5 TMG / DDG (Digitale-Dienste-Gesetz):</strong> Allgemeine Anbieter-Angaben auf Online-Medien mit geschäftsmäßigem Charakter.</li><li><strong>§18 MStV (Medienstaatsvertrag):</strong> Zusätzliche Angaben für journalistisch-redaktionelle Inhalte.</li></ul><p><strong>Praktische Regel:</strong> Sobald Sie eine Website für geschäftliche Zwecke betreiben — auch eine reine Visitenkarten-Website mit Kontaktformular — fallen Sie unter §5 TMG. Das gilt für Einzelunternehmer, Freiberufler, alle Gesellschaftsformen.</p>
HTML;

        $sectionForms = <<<'HTML'
<h3>Einzelunternehmer</h3><ul><li>Vorname und Nachname des Inhabers (vollständig)</li><li>Vollständige ladungsfähige Anschrift (kein Postfach)</li><li>Kontakt: Telefonnummer oder schnelle Alternative</li><li>E-Mail-Adresse</li><li>Umsatzsteuer-ID (falls vorhanden)</li><li>Ggf. zuständige Aufsichtsbehörde</li></ul><h3>Freiberufler</h3><p>Zusätzlich:</p><ul><li>Berufsbezeichnung und Staat, in dem sie verliehen wurde</li><li>Zuständige Kammer</li><li>Berufsrechtliche Regelungen (Link/Verweis)</li></ul><h3>UG (haftungsbeschränkt)</h3><p>Zusätzlich:</p><ul><li>Firmenname mit Zusatz UG (haftungsbeschränkt)</li><li>Sitz der Gesellschaft</li><li>Handelsregister + Registernummer</li><li>Vertretungsberechtigte Geschäftsführer (alle, vollständige Namen)</li><li>Umsatzsteuer-ID oder Wirtschafts-ID-Nummer</li></ul><h3>GmbH</h3><p>Wie UG, mit Firmenzusatz GmbH.</p><h3>GbR</h3><p>Alle Gesellschafter einzeln mit vollständigem Namen und Anschrift.</p>
HTML;

        $sectionMistakes = <<<'HTML'
<ol><li><strong>Unvollständige Anschrift</strong> — Postfach reicht nicht.</li><li><strong>Fehlende Telefonnummer ohne Alternative</strong> — Sie brauchen eine dokumentierte schnelle Alternative.</li><li><strong>Falsche Gesellschaftsform</strong> — UG ohne (haftungsbeschränkt) ist fehlerhaft.</li><li><strong>Geschäftsführer fehlen</strong> — alle vertretungsberechtigten müssen genannt werden.</li><li><strong>Veraltete Datenschutzerklärung</strong> — typisch 6–18 Monate hinter den Tools her.</li><li><strong>Cookie-Banner ohne echte Ablehn-Möglichkeit</strong> — nach EuGH/BGH muss Ablehnen gleichwertig sein.</li><li><strong>Tracking vor Einwilligung</strong> — Skripte dürfen nicht laden, bevor der Nutzer zugestimmt hat.</li><li><strong>Kontaktformular ohne DSGVO-Hinweis</strong></li><li><strong>Keine SSL-Verschlüsselung</strong></li><li><strong>Keine AV-Verträge mit Dienstleistern</strong> — für jeden externen Dienst.</li></ol>
HTML;

        $sectionPrivacy = <<<'HTML'
<p>Grobstruktur:</p><ol><li>Verantwortlicher (Name, Adresse, Kontaktdaten)</li><li>Zwecke und Rechtsgrundlagen (Art. 6 DSGVO)</li><li>Kategorien von Empfängern</li><li>Speicherdauer</li><li>Rechte der Betroffenen</li><li>Drittland-Übermittlungen (USA-Tools mit Schutzmechanismen)</li><li>Spezifische Tool-Abschnitte (Google Analytics mit IP-Anonymisierung, Google Fonts lokal hosten, Meta Pixel, Newsletter-Tool, Kontaktformular, Cookie-Banner-Provider)</li><li>Automatische Löschung / Speicherdauer auch für Kontaktformular-Daten</li></ol><p><strong>Tool-Empfehlung:</strong> Gute Generatoren (e-recht24, DSGVO-Generator.de, Datenschutz.de) decken die Basis ab — aber Sie müssen die Erklärung bei jedem Tool-Wechsel anpassen.</p>
HTML;

        $sectionCookie = <<<'HTML'
<p><strong>Pflicht-Merkmale:</strong></p><ul><li><strong>Echte Opt-in-Lösung</strong> — Tracking-Skripte erst nach aktiver Zustimmung.</li><li><strong>Gleichwertige Ablehnen-Option</strong> — optisch und funktional auf Augenhöhe.</li><li><strong>Granulare Wahl</strong> — Kategorien einzeln wählbar.</li><li><strong>Leicht widerrufbar</strong> — jederzeit ohne Browser-Cookie-Reset.</li><li><strong>Nachweis der Einwilligung</strong> — wer wann was zugestimmt hat.</li></ul><p><strong>Tool-Empfehlungen:</strong> Klaro! (Open Source), Borlabs Cookie (WordPress, kommerziell), Real Cookie Banner (WordPress).</p>
HTML;

        $sectionExtra = <<<'HTML'
<ul><li><strong>Widerrufsbelehrung:</strong> Bei B2C-Online-Verträgen.</li><li><strong>AGB:</strong> Empfohlen, vor Vertragsschluss sichtbar und speicherbar.</li><li><strong>Preisangabenverordnung:</strong> Endpreise (brutto) bei Endkunden.</li><li><strong>OS-Plattform-Hinweis:</strong> Bei Online-Handel mit Verbrauchern Link zur EU-Online-Streitbeilegungsplattform.</li><li><strong>Berufliche Meldepflichten:</strong> Bei regulierten Berufen Zulassungsnummer und Kammer.</li></ul>
HTML;

        $sectionConclusion = <<<'HTML'
<p><strong>1. Starten Sie nicht ohne Impressum und Datenschutzerklärung live.</strong></p><p><strong>2. Lassen Sie die Form zum Start von einem Dienstleister machen</strong> — entweder über eine Agentur (z.B. unser <a href="/loesungen/gruenderpaket-frankfurt">Gründerpaket Frankfurt</a>), einen Fachanwalt (500–2.500 €), oder gepflegten Generator als Einstieg.</p><p><strong>3. Prüfen Sie alle 6–12 Monate.</strong></p><p><strong>4. Bei sensiblen Branchen: Fachanwalt.</strong> Gesundheit, Recht, Finanzen, Kinder.</p><p><strong>5. Dokumentieren Sie Ihre Entscheidungen.</strong> Welche Tools, welche Rechtsgrundlage, welche AV-Verträge?</p>
HTML;

        return [
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 70,
            'slug' => ['de' => 'impressum-pflicht-selbststaendige', 'en' => 'impressum-pflicht-selbststaendige'],
            'title' => ['de' => 'Impressum & DSGVO für Selbstständige — was ist Pflicht?', 'en' => 'Imprint & DSGVO for Founders'],
            'meta_title' => ['de' => 'Impressum & DSGVO für Selbstständige: Was 2026 wirklich Pflicht ist'],
            'meta_description' => ['de' => 'Impressum, Datenschutz, Cookie-Banner — was Existenzgründer und Selbstständige 2026 rechtssicher brauchen. Praxisorientiert, mit Muster-Struktur und Fallen-Liste.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Ratgeber · Rechtssicherheit',
                        'subtitle' => 'Einzelunternehmer, Freiberufler, UG oder GmbH — jede Form hat ihre eigenen Impressum- und Datenschutz-Anforderungen. Dieser Ratgeber führt Sie durch die Pflicht-Inhalte und die häufigsten Abmahnfallen.',
                    ],
                    'intro' => ['text' => $intro],
                    'sections' => [
                        ['title' => 'Wo die Pflicht herkommt: §5 TMG/DDG und §18 MStV', 'content' => $sectionLaw],
                        ['title' => 'Was ins Impressum gehört — nach Gründungsform', 'content' => $sectionForms],
                        ['title' => 'Die 10 häufigsten Impressum- und Datenschutz-Fehler', 'content' => $sectionMistakes],
                        ['title' => 'Datenschutzerklärung: Was 2026 drin stehen muss', 'content' => $sectionPrivacy],
                        ['title' => 'Cookie-Banner 2026: Was rechtssicher ist', 'content' => $sectionCookie],
                        ['title' => 'Was zusätzlich je nach Geschäftsmodell Pflicht sein kann', 'content' => $sectionExtra],
                        ['title' => 'Fazit: Was Sie jetzt konkret tun sollten', 'content' => $sectionConclusion],
                    ],
                    'cta' => [
                        'title' => 'Rechtstexte sauber — und zwar einmalig erledigt?',
                        'subtitle' => 'Unser Gründerpaket enthält alle Rechtstexte — auf Ihre Gründungsform zugeschnitten, DSGVO-konform, mit Cookie-Banner und 12 Monaten Aktualisierungs-Service.',
                        'button_text' => 'Gründerpaket anfragen',
                    ],
                ],
            ],
        ];
    }
}
