<?php

namespace Database\Seeders;

use App\Models\BlogArticle;
use Illuminate\Database\Seeder;

class BlogArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Warum digitale Projekte scheitern – und wie Sie es vermeiden',
                'slug' => 'warum-digitale-projekte-scheitern',
                'category' => 'Digitale Systeme',
                'excerpt' => 'Die meisten digitalen Projekte scheitern nicht an fehlender Technologie, sondern an unklaren Anforderungen, fehlender Struktur und unsauberen Integrationen. Ein Blick auf die häufigsten Stolpersteine.',
                'intro' => 'Nach über 10 Jahren Erfahrung in der Entwicklung digitaler Systeme haben wir ein Muster erkannt: Die Technologie ist selten das Problem. Die meisten Projekte scheitern an organisatorischen und strukturellen Defiziten, die sich erst während der Umsetzung zeigen – wenn es teuer wird.',
                'sections' => [
                    ['heading' => 'Problem 1: Unklare Anforderungen', 'content' => 'Ohne präzise Anforderungen wird jede technische Architektur zu einem Ratespiel. Die Folgen: Features, die niemand nutzt, Funktionen, die nachträglich angepasst werden müssen, Zeitverzögerungen durch ständige Rückfragen.'],
                    ['heading' => 'Problem 2: Fehlende technische Struktur', 'content' => 'Viele Projekte starten mit „Wir brauchen erstmal eine Website" – und enden mit einem monolithischen System, das keine Erweiterungen zulässt und bei jeder Änderung komplett neu gedacht werden muss.'],
                    ['heading' => 'Problem 3: Unsaubere Integrationen', 'content' => 'Ein Shop soll mit dem ERP kommunizieren. Ein CRM soll Daten aus verschiedenen Quellen beziehen. Was einfach klingt, wird oft zur größten Baustelle.'],
                ],
                'conclusion' => 'Digitale Projekte scheitern nicht, weil Technologie zu komplex ist – sondern weil strukturelles Denken fehlt. Wer langfristig erfolgreiche Systeme bauen will, muss Anforderungen klären, Architektur durchdenken und sauber arbeiten.',
                'read_time' => 8,
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'API-First: Warum moderne Systeme mit Schnittstellen beginnen',
                'slug' => 'api-first-architektur',
                'category' => 'API-Integration',
                'excerpt' => 'API-First bedeutet nicht nur technische Architektur, sondern strategisches Denken. Warum Systeme, die von Anfang an auf Integration ausgelegt sind, langfristig erfolgreicher sind.',
                'intro' => 'Viele Unternehmen beginnen mit einer Website oder App – und merken später, dass sie Daten austauschen müssen. Dann wird eine Schnittstelle „nachgerüstet". Das Problem: Was als Quick Fix gedacht war, wird zur langfristigen Last. API-First dreht diesen Ansatz um.',
                'sections' => [
                    ['heading' => 'Was bedeutet API-First konkret?', 'content' => 'API-First heißt: Bevor ein Frontend gebaut wird, wird die API definiert. Das klingt technisch, ist aber eine strategische Entscheidung. Daten und Logik werden unabhängig von der Darstellung gedacht.'],
                    ['heading' => 'Warum klassische Ansätze scheitern', 'content' => 'Der klassische Weg: Erst wird die Website gebaut, dann „irgendwann" eine API. Was passiert dabei? Die Geschäftslogik liegt im Frontend, Daten werden direkt aus der Datenbank gelesen.'],
                    ['heading' => 'API-First in der Praxis', 'content' => 'Flexibilität bei Frontend-Änderungen, mehrere Frontends parallel, externe Integrationen ohne Umbau, besseres Testing, Dokumentation von Anfang an.'],
                ],
                'conclusion' => 'API-First ist kein Trend, sondern moderne Software-Architektur. Systeme, die von Anfang an auf Schnittstellen ausgelegt sind, sind flexibler, wartbarer und zukunftssicherer.',
                'read_time' => 6,
                'is_published' => true,
                'published_at' => now()->subDays(21),
            ],
            [
                'title' => 'Von Excel zu digitalen Workflows: Ein Praxisleitfaden',
                'slug' => 'von-excel-zu-digitalen-workflows',
                'category' => 'Prozessautomatisierung',
                'excerpt' => 'Excel ist kein Prozess-Management-Tool. Wie Sie manuelle Abläufe systematisch digitalisieren – von der Analyse über die Konzeption bis zur technischen Umsetzung.',
                'intro' => '„Wir haben alles in Excel" – ein Satz, den wir häufig hören. Excel ist vielseitig, verfügbar und jeder kennt es. Aber: Excel ist nicht für Workflows, Prozesse und Multi-User-Szenarien gebaut.',
                'sections' => [
                    ['heading' => 'Warum Excel an seine Grenzen stößt', 'content' => 'Excel ist ein exzellentes Tool – für das, wofür es gebaut wurde: Tabellenkalkulation. Was Excel nicht ist: keine Datenbank, kein Workflow-System, keine Schnittstelle, keine Rechteverwaltung.'],
                    ['heading' => 'Schritt 1: Prozesse verstehen', 'content' => 'Bevor Sie digitalisieren, müssen Sie verstehen, was genau passiert. Welche Schritte durchläuft ein Vorgang? Wer ist an welchem Punkt beteiligt?'],
                    ['heading' => 'Schritt 2: Anforderungen definieren', 'content' => 'Jetzt wissen Sie, was passiert. Die nächste Frage: Was soll das digitale System können? Funktionale, technische und organisatorische Anforderungen klären.'],
                ],
                'conclusion' => 'Von Excel zu digitalen Workflows ist kein technisches Projekt – es ist ein Change-Prozess. Die Technologie ist der einfache Teil. Die Herausforderung ist, Prozesse zu verstehen und Menschen mitzunehmen.',
                'read_time' => 10,
                'is_published' => true,
                'published_at' => now()->subDays(28),
            ],
            [
                'title' => 'Headless CMS: Wann sich die Trennung von Frontend und Backend lohnt',
                'slug' => 'headless-cms-wann-sinnvoll',
                'category' => 'WordPress',
                'excerpt' => 'Headless CMS-Architekturen bieten Flexibilität, aber nicht jedes Projekt braucht sie. Eine technische Einordnung, wann klassisches WordPress ausreicht und wann Headless sinnvoll ist.',
                'intro' => 'Headless CMS trennt Content-Verwaltung von der Darstellung. Wann lohnt sich dieser Ansatz? Content wird über APIs bereitgestellt, Frontend ist komplett entkoppelt.',
                'sections' => [
                    ['heading' => 'Was ist Headless CMS?', 'content' => 'Maximale Flexibilität, höhere Komplexität. Content wird über APIs bereitgestellt, das Frontend ist komplett vom Backend entkoppelt.'],
                    ['heading' => 'Wann macht Headless Sinn?', 'content' => 'Multi-Channel-Publishing, Performance-kritische Anwendungen, moderne Frontend-Frameworks. Nicht für einfache Websites.'],
                ],
                'conclusion' => 'Headless ist mächtig, aber nicht immer nötig. Klassisches WordPress reicht oft aus. Die Entscheidung sollte auf konkreten Anforderungen basieren.',
                'read_time' => 7,
                'is_published' => true,
                'published_at' => now()->subDays(35),
            ],
            [
                'title' => 'E-Commerce-Integration: Shop und Warenwirtschaft sauber verbinden',
                'slug' => 'ecommerce-warenwirtschaft-integration',
                'category' => 'E-Commerce',
                'excerpt' => 'Ein Online-Shop ohne ERP-Anbindung führt zu Doppelpflege und Fehlern. Wie Sie Bestandsabgleich, Produktdaten und Bestellungen automatisiert synchronisieren.',
                'intro' => 'Shop und ERP müssen kommunizieren. Sonst entstehen Inkonsistenzen, die Kunden kosten. Bestandsabweichungen, Preisfehler, manuelle Bestellübertragung – alles vermeidbar.',
                'sections' => [
                    ['heading' => 'Typische Integrationsprobleme', 'content' => 'Bestandsabweichungen, Preisfehler, manuelle Bestellübertragung. Alles vermeidbar durch saubere Integration.'],
                    ['heading' => 'Architektur einer E-Commerce-Integration', 'content' => 'Echtzeit vs. Batch, Conflict Resolution, Fehlerbehandlung. Wichtig: Monitoring und Logging.'],
                ],
                'conclusion' => 'E-Commerce-Integration ist komplex, aber unverzichtbar. Einmal richtig gebaut, spart sie täglich Zeit und verhindert kostspielige Fehler.',
                'read_time' => 9,
                'is_published' => true,
                'published_at' => now()->subDays(42),
            ],
            [
                'title' => 'Skalierbare Systemarchitektur: Was bedeutet das konkret?',
                'slug' => 'skalierbare-systemarchitektur',
                'category' => 'Digitale Systeme',
                'excerpt' => 'Skalierbarkeit ist kein Buzzword, sondern eine Frage der Architektur. Wie Sie Systeme bauen, die mit steigenden Anforderungen wachsen – technisch und organisatorisch.',
                'intro' => 'Skalierbarkeit bedeutet nicht „schneller", sondern „wachstumsfähig". Vertikal vs. horizontal – die richtige Strategie hängt vom Anwendungsfall ab.',
                'sections' => [
                    ['heading' => 'Technische Skalierbarkeit', 'content' => 'Load Balancing, Caching, Datenbank-Optimierung. Architektur-Entscheidungen, die später schwer zu ändern sind.'],
                    ['heading' => 'Organisatorische Skalierbarkeit', 'content' => 'Code-Struktur, Team-Aufteilung, Deployment-Prozesse. Skalierbarkeit ist mehr als Technik.'],
                ],
                'conclusion' => 'Skalierbar bauen kostet anfangs kaum mehr, zahlt sich aber langfristig massiv aus. Die Grundlagen richtig legen ist entscheidend.',
                'read_time' => 8,
                'is_published' => true,
                'published_at' => now()->subDays(49),
            ],
        ];

        foreach ($articles as $article) {
            BlogArticle::create($article);
        }
    }
}
