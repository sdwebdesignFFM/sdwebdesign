<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Ratgeber article "Was kostet eine App?" targeting "app entwicklung kosten"
 * (260 searches/month, KD 9) and "app entwickeln lassen" (390/month, KD 16).
 * GSC already shows 105 impressions at position ~32 for "android app
 * entwickeln lassen" without a matching informational page.
 *
 * Run via:
 *   php artisan db:seed --class=AppCostGuideSeeder --force
 *
 * Idempotent: matches by slug->de and updates in place.
 */
class AppCostGuideSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'app-entwicklung-kosten')
            ->first();

        $payload = $this->payload();

        if ($page) {
            $page->fill($payload);
            $page->save();
            $this->command?->info('  • updated /ratgeber/app-entwicklung-kosten (Page TYPE_GUIDE)');

            return;
        }

        Page::create($payload);
        $this->command?->info('  • created /ratgeber/app-entwicklung-kosten (Page TYPE_GUIDE)');
    }

    /** @return array<string, mixed> */
    private function payload(): array
    {
        $intro = <<<'TXT'
Eine App entwickeln zu lassen ist eine der teuersten Möglichkeiten, ein digitales Problem zu lösen — und manchmal die einzig richtige. Umso wichtiger ist eine ehrliche Kostenbetrachtung, bevor die erste Zeile Code entsteht. Dieser Ratgeber zeigt realistische Entwicklungskosten für 2026, erklärt, warum eine Progressive Web App oft 60–70 % des Budgets spart, und rechnet die laufenden Kosten mit ein, die in den meisten Angeboten fehlen.
TXT;

        $sectionRanges = <<<'HTML'
<p>Marktübliche Spannen in Deutschland (Stand 2026), wenn Sie eine App entwickeln lassen:</p><table><thead><tr><th>App-Typ</th><th>Typischer Einsatz</th><th>Entwicklungskosten</th></tr></thead><tbody><tr><td>Progressive Web App (PWA)</td><td>App-Erlebnis über den Browser, installierbar, offlinefähig</td><td>8.000–30.000 €</td></tr><tr><td>Hybrid-App (ein Code für iOS + Android)</td><td>Standard-Anwendungen ohne tiefe Geräteintegration</td><td>15.000–60.000 €</td></tr><tr><td>Native App (iOS oder Android)</td><td>Beste Performance, volle Geräteintegration</td><td>25.000–100.000 €+ pro Plattform</td></tr><tr><td>Native App (iOS und Android)</td><td>Zwei Plattformen, zwei Codebasen</td><td>40.000–180.000 €+</td></tr></tbody></table><p>Einfache MVP-Apps mit wenigen Kernfunktionen liegen am unteren Rand, Apps mit Backend, Nutzerkonten, Push-Nachrichten und Schnittstellen in der Mitte, komplexe Produkte mit Echtzeit-Funktionen oder Hardware-Anbindung am oberen Rand.</p>
HTML;

        $sectionDrivers = <<<'HTML'
<ol><li><strong>Plattform-Strategie</strong> — der größte Hebel. iOS und Android nativ verdoppeln Entwicklungs- und Wartungsaufwand; eine <a href="/loesungen/mobile-anwendungen/pwa">Progressive Web App</a> bedient beide Welten mit einer Codebasis.</li><li><strong>Backend und Nutzerkonten</strong> — eine App ohne Server (Rechner, Nachschlagewerk) ist günstig. Sobald Login, Datensynchronisation und Verwaltung dazukommen, entsteht ein zweites Softwareprojekt hinter der App: typisch 30–50 % des Gesamtbudgets.</li><li><strong>Schnittstellen</strong> — Anbindung an Warenwirtschaft, CRM, Zahlungsdienste oder Buchungssysteme: je nach API-Qualität 2.000–15.000 € pro System.</li><li><strong>Geräteintegration</strong> — Kamera, GPS, Bluetooth, NFC, Offline-Modus: jede tiefe Integration kostet Entwicklungs- und vor allem Testaufwand über viele Gerätegenerationen.</li><li><strong>Design und Nutzerführung</strong> — Apps werden an den besten Consumer-Apps gemessen. Ein durchdachtes UX-Konzept mit Prototyp kostet 3.000–10.000 € und verhindert die teuerste Fehlentwicklung: eine App, die niemand bedienen mag.</li><li><strong>Store-Anforderungen</strong> — Apple-Review, Datenschutz-Labels, Sandbox-Anforderungen: der Weg in die Stores ist planbar, aber nicht kostenlos.</li></ol>
HTML;

        $sectionPwa = <<<'HTML'
<p>Die wichtigste Kostenfrage lautet nicht „iOS oder Android?", sondern: <strong>Braucht es überhaupt eine native App?</strong></p><p>Eine Progressive Web App läuft im Browser, lässt sich auf dem Startbildschirm installieren, funktioniert offline und sendet Push-Nachrichten (unter Android vollständig, unter iOS mit Einschränkungen). Für die meisten Geschäftsanwendungen — Kundenportale, interne Tools, Buchung, Kataloge — reicht das vollständig aus.</p><p>Der Kostenvorteil ist erheblich: <strong>eine Codebasis statt zwei bis drei</strong>, keine Store-Gebühren und -Reviews, Updates sofort bei allen Nutzern. Typisch spart eine PWA 60–70 % gegenüber nativer Doppel-Entwicklung. Nativ bleibt die richtige Wahl bei tiefer Geräteintegration, höchsten Performance-Ansprüchen oder wenn die Store-Präsenz selbst Marketingziel ist — Details in unserem Vergleich <a href="/ratgeber/app-oder-pwa">App oder PWA?</a> und auf den Seiten <a href="/loesungen/mobile-anwendungen/ios-apps">iOS-Apps</a> und <a href="/loesungen/mobile-anwendungen/android-apps">Android-Apps</a>.</p>
HTML;

        $sectionRunning = <<<'HTML'
<p>Apps verursachen laufende Kosten, die in vielen Angeboten fehlen — und über fünf Jahre gerechnet oft die Erstentwicklung übersteigen:</p><ul><li><strong>Betriebssystem-Updates</strong> — iOS und Android erscheinen jährlich neu. Rechnen Sie mit 10–20 % der Entwicklungskosten pro Jahr für Anpassungen und Tests.</li><li><strong>Server und Backend-Betrieb</strong> — je nach Nutzerzahl 20–500 € pro Monat plus Wartung.</li><li><strong>Store-Gebühren</strong> — Apple 99 $/Jahr, Google einmalig 25 $; bei Verkäufen über die Stores 15–30 % Provision.</li><li><strong>Monitoring und Fehlerbehebung</strong> — Crash-Reports, Sicherheits-Patches, Support: als Wartungsvertrag typisch 150–500 € pro Monat.</li><li><strong>Weiterentwicklung</strong> — eine App ohne neue Funktionen verliert Nutzer. Erfolgreiche Apps planen ein jährliches Weiterentwicklungs-Budget ein.</li></ul><p>Faustregel: <strong>Planen Sie über drei Jahre das 1,5- bis 2-fache der Erstentwicklung</strong> als Gesamtbudget. Wie professioneller App- und Web-Betrieb aussieht, zeigt unsere Seite <a href="/betrieb-hosting-wartung">Betrieb, Hosting & Wartung</a>.</p>
HTML;

        $sectionSaving = <<<'HTML'
<ul><li><strong>Mit einem MVP starten</strong> — die Kernfunktion zuerst, echtes Nutzer-Feedback einsammeln, dann ausbauen. Das verhindert die teuerste Kostenfalle: monatelang am Bedarf vorbei zu entwickeln.</li><li><strong>PWA statt nativer Doppel-Entwicklung</strong> — wo die Anforderungen es zulassen (siehe oben).</li><li><strong>Standard-Backend nutzen</strong> — erprobte Frameworks statt Eigenbau für Login, Rechte und Verwaltung.</li><li><strong>Scope schriftlich fixieren</strong> — eine präzise Funktionsliste macht Angebote vergleichbar und verhindert Puffer-Aufschläge.</li></ul><p>Woran Sie nicht sparen sollten: UX-Konzept, Sicherheit (gerade bei Nutzerdaten) und automatisierte Tests. Diese drei Punkte entscheiden darüber, ob die App nach zwei Jahren noch wartbar ist.</p>
HTML;

        $sectionExample = <<<'HTML'
<p>Ein Dienstleistungsunternehmen möchte seinen Kunden eine App bieten: Aufträge einsehen, Dokumente hochladen, Termine buchen, Push-Benachrichtigung bei Statusänderung.</p><ul><li>UX-Konzept und Design: 4.000–7.000 €</li><li>PWA-Entwicklung (Frontend): 8.000–14.000 €</li><li>Backend mit Nutzerkonten und Rechteverwaltung: 7.000–12.000 €</li><li>Anbindung an das bestehende Verwaltungssystem: 3.000–6.000 €</li></ul><p><strong>Gesamt: rund 22.000–39.000 €</strong> als PWA — nativ für iOS und Android läge dasselbe Projekt bei 50.000–90.000 €. Laufend: ca. 300–600 € pro Monat für Betrieb, Wartung und kleinere Weiterentwicklungen.</p>
HTML;

        $sectionConclusion = <<<'HTML'
<p>Die Kosten einer App entscheiden sich in drei Fragen: Was ist die Kernfunktion (MVP)? Reicht eine PWA oder braucht es native Entwicklung? Und wer trägt Betrieb und Weiterentwicklung? Wer diese Fragen vor dem ersten Angebot beantwortet, bekommt vergleichbare Preise statt Wundertüten — und baut die App, die Nutzer tatsächlich brauchen.</p><p>Im kostenlosen Erstgespräch klären wir genau das: Ich sage Ihnen ehrlich, ob Ihr Vorhaben eine native App rechtfertigt, eine PWA reicht — oder ob eine gut gemachte Website das Problem günstiger löst.</p>
HTML;

        return [
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 4,
            'slug' => ['de' => 'app-entwicklung-kosten', 'en' => 'app-development-cost'],
            'title' => ['de' => 'Was kostet eine App? Entwicklungskosten realistisch kalkuliert', 'en' => 'What does an app cost? Development costs calculated realistically'],
            'meta_title' => ['de' => 'App entwickeln lassen: Kosten 2026 — realistische Preisspannen'],
            'meta_description' => ['de' => 'Was kostet es, eine App entwickeln zu lassen? Realistische Entwicklungskosten 2026 für native Apps, Hybrid-Apps und PWAs — plus laufende Kosten, Preistreiber und Spartipps.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Ratgeber · Kosten',
                        'subtitle' => 'Realistische Entwicklungskosten für native Apps, Hybrid-Apps und PWAs — mit laufenden Kosten, Preistreibern und einer ehrlichen Antwort auf die Frage, ob es überhaupt eine App sein muss.',
                    ],
                    'intro' => ['text' => $intro],
                    'sections' => [
                        ['title' => 'Die kurze Antwort: realistische Kostenspannen 2026', 'content' => $sectionRanges],
                        ['title' => 'Die sechs Faktoren, die App-Kosten wirklich bestimmen', 'content' => $sectionDrivers],
                        ['title' => 'PWA statt native App: der größte Kostenhebel', 'content' => $sectionPwa],
                        ['title' => 'Die laufenden Kosten: Updates, Betrieb, Stores', 'content' => $sectionRunning],
                        ['title' => 'Wo Sie sparen können — und wo besser nicht', 'content' => $sectionSaving],
                        ['title' => 'Beispielrechnung: Kunden-App für ein Dienstleistungsunternehmen', 'content' => $sectionExample],
                        ['title' => 'Fazit: Erst MVP und Plattform-Frage klären, dann Angebote vergleichen', 'content' => $sectionConclusion],
                    ],
                    'related_solutions' => ['pwa', 'ios-apps', 'betrieb-hosting-wartung'],
                    'cta' => [
                        'title' => 'Wissen, was Ihre App kosten würde?',
                        'subtitle' => 'Im kostenlosen Erstgespräch klären wir Kernfunktion und Plattform-Frage — Sie bekommen eine ehrliche Einschätzung, auch wenn die Antwort „keine App nötig" lautet.',
                        'button_text' => 'Kostenloses Erstgespräch anfragen',
                        'button_link' => '/kontakt',
                    ],
                ],
            ],
        ];
    }
}
