<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Ratgeber article "Was kostet eine professionelle Website?" targeting the
 * keyword "website erstellen lassen kosten" (480 searches/month, keyword
 * difficulty 12 per SE Ranking, July 2026) plus the surrounding cost cluster.
 *
 * Run via:
 *   php artisan db:seed --class=WebsiteCostGuideSeeder --force
 *
 * Idempotent: matches by slug->de and updates in place (overwrites manual
 * Filament edits to this article).
 */
class WebsiteCostGuideSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'website-erstellen-lassen-kosten')
            ->first();

        $payload = $this->payload();

        if ($page) {
            $page->fill($payload);
            $page->save();
            $this->command?->info('  • updated /ratgeber/website-erstellen-lassen-kosten (Page TYPE_GUIDE)');

            return;
        }

        Page::create($payload);
        $this->command?->info('  • created /ratgeber/website-erstellen-lassen-kosten (Page TYPE_GUIDE)');
    }

    /** @return array<string, mixed> */
    private function payload(): array
    {
        $intro = <<<'TXT'
„Was kostet eine Website?" ist die häufigste erste Frage an jede Webagentur — und die ehrliche Antwort lautet: zwischen 0 € und 50.000 €, je nachdem, was Sie brauchen. Das hilft niemandem. Dieser Ratgeber macht die Frage beantwortbar: mit realistischen Preisspannen für 2026, den Faktoren, die den Preis wirklich bestimmen, den laufenden Kosten, die gern verschwiegen werden — und den Stellen, an denen Sparen teuer wird.
TXT;

        $sectionRanges = <<<'HTML'
<p>Marktübliche Preisspannen in Deutschland (Stand 2026), wenn Sie eine Website professionell erstellen lassen:</p><table><thead><tr><th>Website-Typ</th><th>Typischer Umfang</th><th>Einmalige Kosten</th></tr></thead><tbody><tr><td>Baukasten (selbst gemacht)</td><td>Wix, Squarespace, Jimdo — Sie investieren Zeit statt Geld</td><td>0–500 € + viele Abende</td></tr><tr><td>Starter-Website vom Profi</td><td>5–10 Seiten, individuelles Design, sauberes SEO-Fundament</td><td>1.500–5.000 €</td></tr><tr><td>Website mit CMS (z. B. WordPress)</td><td>Selbst pflegbare Inhalte, Blog, individuelle Gestaltung</td><td>3.000–10.000 €</td></tr><tr><td>Individuelle Website mit Sonderfunktionen</td><td>Buchung, Konfiguratoren, Schnittstellen, Portale</td><td>8.000–25.000 €+</td></tr><tr><td>Online-Shop</td><td>Produktkatalog, Checkout, Zahlarten, Warenwirtschaft</td><td>5.000–30.000 €+</td></tr></tbody></table><p>Diese Spannen sind bewusst breit — der tatsächliche Preis hängt von den Faktoren im nächsten Abschnitt ab. Ein seriöses Angebot bekommen Sie nur auf eine konkrete Anforderungsliste, nicht auf die Frage „was kostet eine Website?".</p>
HTML;

        $sectionDrivers = <<<'HTML'
<ol><li><strong>Design: Vorlage oder individuell</strong> — ein angepasstes Template ist deutlich günstiger als ein von Grund auf gestaltetes Design. Der Unterschied liegt typisch bei 1.000–4.000 €. Individuelles Design lohnt sich, wenn Ihre Website sich vom Wettbewerb abheben muss.</li><li><strong>Umfang und Struktur</strong> — 5 Seiten oder 50? Jede zusätzliche Seitenart (nicht Seite!) mit eigenem Layout kostet Design- und Entwicklungszeit. Zehn Leistungsseiten mit gleichem Aufbau sind billiger als fünf komplett unterschiedliche.</li><li><strong>Inhalte: liefern Sie oder die Agentur?</strong> — Texte, Fotos und Übersetzungen sind ein eigener Kostenblock. Professionelle Texte kosten 80–150 € pro Seite, ein Fototermin 500–1.500 €. Wer gute Inhalte selbst liefert, spart vierstellig.</li><li><strong>Funktionen</strong> — Kontaktformulare sind Standard. Terminbuchung, Mitgliederbereiche, Mehrsprachigkeit, Rechner oder Konfiguratoren sind es nicht: jede dieser Funktionen kostet je nach Komplexität 500–5.000 €.</li><li><strong>Schnittstellen zu anderen Systemen</strong> — Newsletter-Tool, CRM, Warenwirtschaft, Buchungssystem. Eine saubere Anbindung liegt je nach API-Qualität der Gegenseite bei 500–5.000 € pro System.</li><li><strong>SEO-Fundament</strong> — saubere Technik (Ladezeit, strukturierte Daten, Meta-Angaben, interne Verlinkung) gehört in jedes seriöse Projekt. Umfangreiche Keyword-Recherche und Content-Strategie sind ein eigenes Budget: ab 1.000 € einmalig oder als laufende Betreuung.</li><li><strong>Barrierefreiheit</strong> — seit Juni 2025 ist Barrierefreiheit für viele Websites mit Verbraucherkontakt gesetzliche Pflicht (BFSG). Von Anfang an mitgedacht kostet sie wenig Aufpreis; nachträglich nachgerüstet wird sie teuer. Details dazu in unserem Angebot <a href="/loesungen/websites/barrierefreies-webdesign">Barrierefreies Webdesign</a>.</li></ol>
HTML;

        $sectionProviders = <<<'HTML'
<p><strong>Baukasten (selbst)</strong> — sinnvoll, wenn das Budget unter 1.000 € liegt und die Website vor allem eine digitale Visitenkarte sein soll. Die realen Kosten sind Ihre Zeit und die Grenzen des Systems: eigenes Design, SEO-Feinheiten und Sonderfunktionen stoßen schnell an Grenzen, ein späterer Umzug ist praktisch immer ein Neuaufbau.</p><p><strong>Freelancer</strong> — Stundensätze typisch 60–110 €. Gut für klar umrissene Projekte, wenn Sie eine Empfehlung haben und mit Ausfallrisiko (Krankheit, Auslastung, Verfügbarkeit in zwei Jahren) leben können.</p><p><strong>Webagentur</strong> — Stundensätze typisch 90–160 €. Sie zahlen für ein eingespieltes Team, Vertretung, Prozesse und einen Ansprechpartner, der auch in drei Jahren noch erreichbar ist. Bei Projekten ab ~3.000 € und allem, was wachsen soll, meist die wirtschaftlichere Wahl — gerechnet über die Lebensdauer der Website, nicht über den Angebotspreis.</p><p>Wichtiger als die Anbieter-Kategorie ist die Frage, ob der Anbieter versteht, was die Website <em>erwirtschaften</em> soll. Eine Website für 4.000 €, die Anfragen bringt, ist billiger als eine für 1.500 €, die keiner findet.</p>
HTML;

        $sectionRunning = <<<'HTML'
<p>Die einmaligen Erstellungskosten sind nur die halbe Wahrheit. Realistische laufende Kosten:</p><ul><li><strong>Hosting</strong> — 10–50 € pro Monat für ein ordentliches Hosting mit SSL, Backups und guter Ladezeit. Bei Shops und Anwendungen mehr.</li><li><strong>Domain</strong> — 10–30 € pro Jahr.</li><li><strong>Wartung und Updates</strong> — bei CMS-Websites (WordPress & Co.) sicherheitsrelevant und nicht optional: 50–200 € pro Monat je nach Umfang. Ungepatchte Websites sind das häufigste Einfallstor für Angriffe.</li><li><strong>Inhaltspflege</strong> — entweder eigene Arbeitszeit oder gebuchte Stunden beim Dienstleister.</li><li><strong>Lizenzkosten</strong> — Premium-Plugins, Buchungstools, Newsletter-Dienste: je nach Setup 0–100 € pro Monat.</li></ul><p>Als Faustregel: Planen Sie <strong>15–25 % der Erstellungskosten pro Jahr</strong> für Betrieb und Pflege ein. Was ein professioneller Betrieb umfasst, zeigt unsere Seite <a href="/betrieb-hosting-wartung">Betrieb, Hosting & Wartung</a>.</p>
HTML;

        $sectionCheap = <<<'HTML'
<p>Die 800-€-Website vom Bekannten eines Bekannten ist verlockend — und wird in der Praxis regelmäßig zur teuersten Option. Die typischen versteckten Kosten:</p><ul><li><strong>Unsichtbarkeit</strong> — ohne technisches SEO-Fundament (Ladezeit, saubere Struktur, Meta-Angaben) findet Google die Website nicht. Die Website existiert, bringt aber keine einzige Anfrage.</li><li><strong>Template-Lock-in</strong> — zusammengeklickte Themes mit 40 Plugins sind nach zwei Jahren nicht mehr wartbar. Der „günstige" Start endet im kompletten Relaunch.</li><li><strong>Rechtliche Nachrüstung</strong> — DSGVO-konforme Einbindungen, Cookie-Handling und BFSG-Barrierefreiheit nachträglich einzubauen kostet oft mehr als der ursprüngliche Projektpreis.</li><li><strong>Kein Ansprechpartner</strong> — wenn nach 18 Monaten etwas kaputtgeht und niemand mehr erreichbar ist, zahlen Sie einen Neuen fürs Einarbeiten in fremden Code.</li></ul><p>Umgekehrt gilt genauso: Nicht jedes Projekt braucht die 20.000-€-Lösung. Ein ehrlicher Anbieter sagt Ihnen auch, wenn die kleinere Variante reicht.</p>
HTML;

        $sectionSaving = <<<'HTML'
<p>An diesen Stellen können Sie den Preis senken, ohne die Substanz zu beschädigen:</p><ul><li><strong>Inhalte selbst liefern</strong> — gut vorbereitete Texte und brauchbare Fotos sparen schnell 1.000–3.000 €.</li><li><strong>Klarer Scope statt Wunschliste</strong> — eine präzise Liste („diese 7 Seiten, diese 2 Funktionen") macht Angebote vergleichbar und verhindert Puffer-Aufschläge.</li><li><strong>In Stufen starten</strong> — erst eine fokussierte <a href="/loesungen/websites/starter-website">Starter-Website</a>, die Anfragen bringt; Ausbau (Blog, weitere Leistungsseiten, Funktionen) folgt, wenn sie sich trägt. Wichtig ist nur, dass die technische Basis erweiterbar angelegt ist.</li><li><strong>Standard nutzen, wo Standard reicht</strong> — ein bewährtes CMS wie WordPress mit sauberem, individuellem Theme ist oft der beste Mittelweg aus Kosten und Flexibilität. Mehr dazu: <a href="/loesungen/websites/wordpress-website">WordPress & CMS-Website</a>.</li></ul><p>Woran Sie nicht sparen sollten: technisches Fundament, Ladezeit, Sicherheit und die Erweiterbarkeit des Codes. Das sind genau die Punkte, die beim Billiganbieter fehlen — und die einen späteren Relaunch erzwingen.</p>
HTML;

        $sectionExample = <<<'HTML'
<p>Ein Handwerksbetrieb mit 12 Mitarbeitern möchte über die Website Anfragen für zwei Hauptleistungen gewinnen. Anforderungen: 8 Seiten, individuelles Design auf Basis eines Gestaltungsrasters, Kontakt- und Rückruf-Formular, Referenzgalerie, technisches SEO-Fundament, DSGVO-konform, barrierearm.</p><ul><li>Konzept und Design: 1.200–2.000 €</li><li>Umsetzung inkl. CMS und Formularen: 1.800–3.000 €</li><li>SEO-Grundeinrichtung (Meta, strukturierte Daten, Google Business Profile): 400–800 €</li><li>Texte (Kunde liefert Rohfassung, Agentur schärft): 300–600 €</li></ul><p><strong>Gesamt: rund 3.700–6.400 € einmalig</strong>, plus 60–120 € pro Monat für Hosting und Wartung. Bringt die Website auch nur eine zusätzliche Anfrage pro Monat, die zum Auftrag wird, hat sie sich im ersten Jahr bezahlt gemacht — das ist die Rechnung, die zählt.</p>
HTML;

        $sectionConclusion = <<<'HTML'
<p>Die Frage „was kostet eine Website?" beantwortet sich in drei Schritten: klären, was die Website erreichen soll (Anfragen? Sichtbarkeit? Verkauf?), daraus den tatsächlich nötigen Umfang ableiten — und erst dann Angebote einholen, alle auf derselben Anforderungs-Grundlage. So vergleichen Sie Preise statt Vermutungen.</p><p>Wenn Sie wissen möchten, wo Ihr Vorhaben in diesen Spannen liegt: Wir sagen Ihnen im kostenlosen Erstgespräch ehrlich, welche Variante zu Ihrem Ziel passt — auch dann, wenn die Antwort „die kleine" lautet. Sie bekommen ein Festpreis-Angebot mit klarem Leistungsumfang statt einer Stundensatz-Wundertüte.</p>
HTML;

        return [
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 3,
            'slug' => ['de' => 'website-erstellen-lassen-kosten', 'en' => 'website-cost-guide'],
            'title' => ['de' => 'Was kostet eine professionelle Website? Realistische Preise 2026', 'en' => 'What does a professional website cost? Realistic prices 2026'],
            'meta_title' => ['de' => 'Website erstellen lassen: Kosten 2026 — realistische Preisspannen'],
            'meta_description' => ['de' => 'Was kostet es, eine professionelle Website erstellen zu lassen? Realistische Preisspannen 2026 für Starter-Website, WordPress/CMS, individuelle Website und Shop — plus laufende Kosten und Spartipps.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Ratgeber · Kosten',
                        'subtitle' => 'Realistische Preisspannen für 2026, die sieben wichtigsten Preistreiber, laufende Kosten — und die Stellen, an denen Sparen teuer wird.',
                    ],
                    'intro' => ['text' => $intro],
                    'sections' => [
                        ['title' => 'Die kurze Antwort: realistische Preisspannen 2026', 'content' => $sectionRanges],
                        ['title' => 'Die sieben Faktoren, die den Preis wirklich bestimmen', 'content' => $sectionDrivers],
                        ['title' => 'Baukasten, Freelancer oder Agentur?', 'content' => $sectionProviders],
                        ['title' => 'Die laufenden Kosten: Hosting, Wartung, Pflege', 'content' => $sectionRunning],
                        ['title' => 'Warum die billigste Website oft die teuerste ist', 'content' => $sectionCheap],
                        ['title' => 'Wo Sie sparen können — und wo besser nicht', 'content' => $sectionSaving],
                        ['title' => 'Beispielrechnung: Website für einen Handwerksbetrieb', 'content' => $sectionExample],
                        ['title' => 'Fazit: Erst Ziel und Umfang klären, dann Angebote vergleichen', 'content' => $sectionConclusion],
                    ],
                    'related_solutions' => ['starter-website', 'wordpress-website', 'betrieb-hosting-wartung'],
                    'cta' => [
                        'title' => 'Wissen, was Ihre Website kosten würde?',
                        'subtitle' => 'Im kostenlosen Erstgespräch klären wir Ziel und Umfang — Sie bekommen ein Festpreis-Angebot mit klarem Leistungsumfang. Ehrlich auch dann, wenn die kleine Variante reicht.',
                        'button_text' => 'Kostenloses Erstgespräch anfragen',
                        'button_link' => '/kontakt',
                    ],
                ],
            ],
        ];
    }
}
