<?php

use App\Models\BlogArticle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Cleanup for migration 2026_04_26_193432.
 *
 * The "Was kostet eine maßgeschneiderte B2B-Plattform?" article was
 * created with `slug` passed as an associative array
 * (['de' => 'was-kostet-b2b-plattform', 'en' => '...']). On the live
 * MySQL JSON column this resulted in a corrupted record whose
 * de-locale slug ended up as "was-kostet-b" (truncated at the "2"),
 * plus an extra phantom row with slug "b". Both rendered as 404 in
 * the routing layer.
 *
 * (The second article — passed as a plain string — saved correctly,
 * so we don't touch it.)
 *
 * This migration:
 *   1. Deletes any BlogArticle whose de-slug matches the corrupted
 *      shapes "was-kostet-b" or just "b" (limited to articles
 *      created after 2026-04-26 so we don't nuke unrelated data).
 *   2. Re-creates the article with the same payload, but with all
 *      translatable fields as plain strings — matches the existing
 *      BlogArticleSeeder convention and works identically on MySQL
 *      and SQLite.
 *
 * Idempotent: if the article already exists with the correct slug,
 * the create call is skipped via a slug->de lookup.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Remove corrupted records from the previous migration run.
        DB::table('blog_articles')
            ->where('created_at', '>=', '2026-04-26')
            ->where(function ($q) {
                $q->where('slug->de', 'was-kostet-b')
                    ->orWhere('slug->de', 'b')
                    ->orWhere('slug', 'was-kostet-b')
                    ->orWhere('slug', 'b');
            })
            ->delete();

        // 2. Re-create with plain-string slug if the correct record
        //    isn't already there.
        $existing = BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->first();
        if ($existing) {
            return;
        }

        $intro = <<<'TXT'
„Was kostet so etwas eigentlich?" — die Frage steht meist am Anfang jeder ernsthaften Überlegung zu einer eigenen B2B-Plattform. Eine pauschale Antwort gibt es nicht, aber realistische Spannen schon. Dieser Artikel räumt mit drei Mythen auf (Plattform = Konzern-Budget, Festpreis ist immer möglich, Pilot ist Verschwendung) und zeigt Mittelständlern, wie sie die Kosten ihres konkreten Vorhabens selbst einordnen können — bevor sie das erste Angebot einholen.
TXT;

        $costSpan = <<<'TXT'
Eine maßgeschneiderte B2B-Plattform für einen Mittelständler liegt typisch zwischen 50.000 € und 250.000 € im ersten Jahr. Ein Pilot-Modul (klar abgegrenztes erstes Feature, das in 4–6 Wochen live geht) liegt meist im Bereich 15.000–35.000 €. Die volle Plattform mit drei bis fünf Modulen, Schnittstellen zu Bestandssystemen und produktivem Betrieb landet eher bei 80.000–180.000 € im ersten Jahr. Das ist eine breite Spanne — und genau das ist der Punkt: ohne Discovery (Anforderungen, Workflows, Bestandssysteme klar dokumentiert) kann jeder seriöse Anbieter nur schätzen.
TXT;

        $costDrivers = <<<'TXT'
**1. Anzahl der Domain-Module** (Bestellabwicklung, Disposition, Reporting, etc.) — jedes Modul ist im Schnitt 4–8 Personenwochen Entwicklungs-Aufwand.

**2. Schnittstellen zu Bestandssystemen** (DATEV, SAP, Personio, Lager-Systeme, eigene Excel-Welten) — eine saubere Integration kostet typisch 10.000–25.000 € pro System, je nach API-Reife der Gegenstelle.

**3. Anzahl der Nutzer-Rollen mit unterschiedlichen Rechten** — eine Plattform mit drei Rollen (Mitarbeiter, Vorgesetzter, Admin) ist deutlich günstiger als eine mit acht differenzierten Rollen plus Externer-Zugriff.

**4. Compliance- und Audit-Anforderungen** (DSGVO, branchenspezifisch BAIT, GxP, CRA) — kann einen Aufschlag von 15–30 % bedeuten, wenn detaillierte Audit-Trails, getrennte Datenhoheit und formale Doku-Pflichten dazukommen.

**5. Datenmenge und Performance-Anforderungen** — eine Plattform mit 10.000 Datensätzen ist günstiger zu bauen als eine mit 5 Mio., einfach weil die Architektur-Entscheidungen anders aussehen müssen.
TXT;

        $costExclusions = <<<'TXT'
**Lizenzkosten für Standard-Software** — die fallen typisch ohnehin an, egal welche Lösung Sie wählen.

**Interne Personenstunden Ihrer Mitarbeiter** — jede Plattform-Einführung verlangt Mitwirkung. Rechnen Sie mit 2–4 Stunden pro Woche von einem oder zwei Fachverantwortlichen über die Projektlaufzeit.

**Inhalte und Stammdaten** — die Migration alter Excel-Listen, Bilder, Texte, Beschreibungen ist oft ein eigenes kleines Projekt. Wenn Sie das selbst übernehmen, sparen Sie 5.000–15.000 €.
TXT;

        $costPilot = <<<'TXT'
Erfahrungsgemäß scheitert die teuerste Plattform-Strategie immer auf demselben Weg: ein „großes Konzept" entsteht in Workshops, ein 12-Monats-Vertrag wird unterschrieben — und nach 6 Monaten merkt man, dass die Anforderungen sich verändert haben oder das Team das große Projekt nicht durchhält.

Was im Mittelstand fast immer funktioniert: ein **Pilot-Modul** (15–35 k €) in 4–6 Wochen. Ein klar abgegrenztes erstes Feature, das produktiv läuft. Aus diesem Pilot wachsen dann iterativ die weiteren Module — über 12–24 Monate, in für die Organisation verkraftbaren Schritten und mit echtem Anwender-Feedback statt Annahmen.
TXT;

        $costRanges = <<<'TXT'
**Internes Tool für 5–20 Nutzer** (Workflow-App, Verwaltungs-Tool, Reporting-Dashboard): 25.000–60.000 € im ersten Jahr.

**Kunden- oder Partnerportal mit Geschäftslogik** (Status, Bestellung, Self-Service): 50.000–120.000 € im ersten Jahr.

**Workforce-Management oder Disposition** (Mitarbeiter, Schichten, Zertifikate, Stunden, Abrechnung): 80.000–180.000 € im ersten Jahr.

**B2B-Bestellplattform mit ERP-Integration** (Kunden-Login, Preise, Verfügbarkeiten, Order-Workflow): 70.000–160.000 € im ersten Jahr.

Laufender Betrieb (Hosting, Wartung, kleinere Erweiterungen): typisch 15–25 % der Erstaufwand-Summe pro Jahr.
TXT;

        $costFixedPrice = <<<'TXT'
Ein Festpreis funktioniert nur dann ehrlich, wenn der Scope vorher präzise dokumentiert ist. Das ist der eigentliche Wert eines Discovery-Workshops vor dem Projekt: am Ende liegt ein Anforderungsdokument vor, gegen das ein seriöser Anbieter einen Festpreis kalkulieren kann.

Ohne Discovery werden Festpreise entweder mit großem Risiko-Aufschlag kalkuliert (= Sie zahlen drauf) oder im Detail nachverhandelt (= Sie zahlen nochmal drauf). Pilot-Module mit klarem Scope eignen sich gut für Festpreise — komplette Vollprojekte über 12 Monate eher selten.
TXT;

        $costConclusion = <<<'TXT'
Die ehrliche Frage ist nicht „was kostet eine Plattform?", sondern „was kostet die Plattform, die wir tatsächlich brauchen?". Diese Klärung ist der wichtigste Schritt vor dem ersten Angebot. Ein dokumentiertes Discovery-Ergebnis (Anforderungen, Tech-Stack-Empfehlung, Aufwand-Schätzung, Roadmap) macht jeden weiteren Schritt 10× günstiger und 3× schneller. Wer auf dieser Basis Angebote vergleicht, weiß auch, ob ein Preis fair ist oder nicht.

Unser Discovery-Workshop deckt genau diesen Schritt ab: 990 € netto, 2 Stunden, dokumentiertes Discovery-Dokument als PDF. Bei Folgeprojekten verrechnen wir den Workshop auf das Projektbudget — Sie zahlen ihn faktisch nur, wenn Sie nicht weiterarbeiten möchten.
TXT;

        BlogArticle::create([
            'slug' => 'was-kostet-b2b-plattform',
            'category' => 'Digitale Plattformen',
            'title' => 'Was kostet eine maßgeschneiderte B2B-Plattform? Kostenfaktoren für Mittelständler',
            'meta_title' => 'B2B-Plattform Kosten — Was kostet eine eigene Webanwendung? · Mittelstand',
            'meta_description' => 'Was kostet eine maßgeschneiderte B2B-Plattform? Realistische Kostenspannen für Mittelständler, die fünf wichtigsten Preistreiber, Pilot-vs-Vollprojekt — von einem Frankfurter Plattform-Entwickler.',
            'excerpt' => 'Realistische Kostenspannen für maßgeschneiderte B2B-Plattformen im Mittelstand — und die fünf Faktoren, die den Preis am stärksten bestimmen. Mit Beispielen und Pilot-statt-Komplettprojekt-Strategie.',
            'intro' => $intro,
            'sections' => [
                ['heading' => 'Die ehrliche Antwort: Spannen statt Pauschalen', 'content' => $costSpan],
                ['heading' => 'Die fünf wichtigsten Preistreiber', 'content' => $costDrivers],
                ['heading' => 'Was Sie NICHT in den Preis einrechnen sollten', 'content' => $costExclusions],
                ['heading' => 'Pilot-Strategie: Warum 4–6 Wochen am Anfang besser sind als 12 Monate Komplett-Projekt', 'content' => $costPilot],
                ['heading' => 'Realistische Kosten-Bandbreiten nach Projekttyp', 'content' => $costRanges],
                ['heading' => 'Wann ein Festpreis möglich ist — und wann nicht', 'content' => $costFixedPrice],
            ],
            'conclusion' => $costConclusion,
            'read_time' => 11,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
    }

    public function down(): void
    {
        BlogArticle::where('slug->de', 'was-kostet-b2b-plattform')->delete();
    }
};
