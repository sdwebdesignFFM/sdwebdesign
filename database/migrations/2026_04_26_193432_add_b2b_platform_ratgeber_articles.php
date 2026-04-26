<?php

use App\Models\BlogArticle;
use Illuminate\Database\Migrations\Migration;

/**
 * Phase F.2 — Add two new ratgeber articles aligned with the B2B-
 * platform positioning. Selected for SEO leverage + funnel impact:
 *
 *  1. "Was kostet eine maßgeschneiderte B2B-Plattform?" — TOFU/MOFU,
 *     high-intent keyword cluster ("B2B Plattform Kosten",
 *     "Webanwendung Preis Mittelstand"). Anchors the cost
 *     conversation that triggers the Discovery workshop.
 *
 *  2. "Software-Agentur in Frankfurt finden — worauf Mittelständler
 *     achten" — BOFU + strong local-SEO play (Frankfurt, Rhein-
 *     Main, Bad Homburg). Captures regional intent that already
 *     wants to buy.
 *
 * Both articles cross-link to the Discovery workshop, the
 * whitepaper and (article 2) to the local landing pages.
 *
 * Idempotent: looks up by slug, updates in place if present.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->articles() as $payload) {
            // slug is a JSON-translatable column — updateOrCreate's
            // built-in WHERE on `slug = 'foo'` does not match the
            // JSON shape. Look it up by the JSON path instead.
            $slugDe = is_array($payload['slug']) ? $payload['slug']['de'] : $payload['slug'];
            $existing = BlogArticle::where('slug->de', $slugDe)->first();
            if ($existing) {
                $existing->update($payload);
            } else {
                BlogArticle::create($payload);
            }
        }
    }

    public function down(): void
    {
        foreach (['was-kostet-b2b-plattform', 'software-agentur-frankfurt-mittelstand'] as $slug) {
            BlogArticle::where('slug->de', $slug)->delete();
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function articles(): array
    {
        // ============================================================
        // 1) Cost article — TOFU/MOFU, high-volume keyword cluster
        // ============================================================
        $costIntro = <<<'TXT'
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

        // ============================================================
        // 2) Local-SEO article — BOFU, Frankfurt-anchored
        // ============================================================
        $agencyIntro = <<<'TXT'
Wer im Rhein-Main-Gebiet eine Software-Agentur für ein Plattform-Projekt sucht, findet hunderte Anbieter — vom Solo-Freelancer in Bornheim über die 8-Personen-Manufaktur am Westhafen bis zur 200-Personen-Tochter eines Beratungs-Konzerns mit Standort am Mainufer. Die Auswahl ist groß; die Differenzierung schwierig. Dieser Leitfaden hilft Mittelständlern aus Frankfurt, Bad Homburg, Offenbach und dem Hochtaunuskreis, jenseits von Werbeversprechen die richtige Größe und Methodik zu finden.
TXT;

        $agencyCategories = <<<'TXT'
**Solo-Freelancer und 2–3-Personen-Teams** — günstig (60–110 €/h), wenig Overhead, aber: keine Vertretung bei Krankheit, kein Methodik-Standard, oft kein Code-Review im Vier-Augen-Prinzip. Passend für klar abgegrenzte Tools mit niedriger Komplexität.

**Inhabergeführte Spezialisten (5–25 Personen)** — typisch der Sweetspot für Mittelstand-Plattformen. Stundensätze 100–160 €. Gemischte Teams mit echter Senior-Ebene. Sie können sich auf eine bestimmte Branche oder Architektur spezialisieren und über Jahre eine Beziehung tragen.

**Mittelgroße Agenturen (30–100 Personen)** — Stundensätze 130–200 €, viel Overhead durch Account-Manager, Sales-Funnel, Projektleitungen. Passend, wenn Sie selbst eine größere IT-Abteilung haben und mit dieser Sprache reden möchten.

**Konzern-Töchter und Beratungs-Häuser (100+ Personen)** — Stundensätze ab 180 €, oft 250–350 €. Passend für Großprojekte mit Konzern-Compliance. Im klassischen Mittelstand fast immer überdimensioniert — und teuer.
TXT;

        $agencyCriteria = <<<'TXT'
**1. Branchenerfahrung mit Mittelstand** — fragen Sie nach 2–3 Referenzen, die Ihrer Größe und Komplexität ähneln. Konzern-Referenzen sind kein Beweis dafür, dass die Agentur Mittelstand kann.

**2. Eingebetteter Product Owner statt Ticket-System** — die wichtigste Frage: wer übersetzt Geschäft in Technik? Bei guten Anbietern macht das nicht der Programmierer und nicht der Vertrieb, sondern eine fest zugeordnete Person mit PO-Erfahrung.

**3. Tech-Stack-Mainstream** — Laravel, Symfony, TypeScript, Python, Go. Keine exotischen Frameworks, von denen es im Rhein-Main-Gebiet drei Entwickler gibt.

**4. Begleitung über Monate, nicht Projekte** — eine Plattform wächst über 2–5 Jahre. Suchen Sie eine Agentur, die diesen Zeithorizont vertraglich und kapazitätsmäßig abbilden kann.

**5. Code-Eigentum vertraglich klar geregelt** — der Quellcode gehört Ihnen, nicht der Agentur. Lizenz-Modelle mit Lock-In sind ein No-Go.

**6. Persönlicher Ansprechpartner mit Entscheidungs-Vollmacht** — bei Mittelstand-Agenturen oft der Inhaber selbst. Bei größeren Anbietern sollten Sie wissen, wer Sie übernimmt — und ob diese Person auch in 2 Jahren noch da ist.

**7. Discovery vor Festpreis** — seriöse Anbieter machen kein Festpreis-Angebot ohne Discovery-Phase. Wer das ohne Anforderungs-Klärung tut, kalkuliert entweder mit großem Risiko-Puffer (Sie zahlen drauf) oder verhandelt nach (Sie zahlen erst recht drauf).
TXT;

        $agencyWarnings = <<<'TXT'
**„Wir haben das schon hundertmal gebaut, das geht in 4 Wochen"** — wenn jemand Ihre Anforderungen in 30 Minuten verstanden zu haben glaubt, hat er sie nicht verstanden. Mittelstand-Workflows sind individuell, das ist ihr Geschäftsmodell.

**Festpreis-Angebot ohne Discovery-Phase** — siehe oben. Entweder kalkuliert mit Risiko-Aufschlag oder mit absehbarem Nachverhandlungs-Verlauf.

**Vorzeige-Templates ohne klare Trennung von „Standard" und „individuell"** — viele Anbieter verkaufen Customizing auf einer fertigen Plattform als „individuelle Lösung". Das ist nicht zwingend schlecht, aber Sie sollten wissen, was Sie kaufen. Wenn Sie später raus wollen oder zu einem anderen Anbieter wechseln möchten, ist Standard-Customizing meist nicht migrierbar.
TXT;

        $agencySources = <<<'TXT'
**IHK Frankfurt am Main** führt eine Liste qualifizierter IT-Dienstleister im Stadtgebiet — oft eine gute Anlaufstelle für eine Erstrecherche.

**Hessen-IT** und **Digitale Hessen** kennen die regionale Szene gut, haben aber typisch einen Fokus auf größere Anbieter und Cluster-Themen.

**Branchenverbände** (BVMW Frankfurt, VhU Hessen) — wenn Sie schon Mitglied sind, fragen Sie aktiv nach Empfehlungen anderer Mittelständler.

**Persönliches Netzwerk** — die meisten Mittelstand-Plattformen werden auf Empfehlung vergeben. Steuerberater und Wirtschaftsprüfer kennen oft die Pain-Punkte ihrer Mandanten und haben einen Überblick darüber, wer was kann.

Im direkten Stadtgebiet Frankfurt sowie in Bad Homburg, Eschborn, Offenbach und Wiesbaden gibt es starke Mittelstand-spezialisierte Anbieter. Außerhalb des Speckgürtels — Aschaffenburg, Mainz, Darmstadt — auch, allerdings dann mit längeren Anfahrtswegen für Vor-Ort-Workshops.
TXT;

        $agencyOnsite = <<<'TXT'
Ein Discovery-Workshop vor Ort hat einen anderen Wert als ein Video-Call. Sie sehen die echten Räume, lernen Mitarbeiter neben dem Sprecher kennen, sehen die Tools auf den Bildschirmen, verstehen die Stimmung im Unternehmen. Bei reinen Online-Workshops gehen typisch 20–30 % dieser stillen Information verloren.

Umgekehrt: für laufende Projekt-Sprints, regelmäßige Reviews und Bug-Fixing ist Remote völlig in Ordnung. Niemand braucht den Entwickler permanent vor Ort sitzen — das treibt nur Reisekosten und Stundensätze hoch.

Unsere Empfehlung für Mittelständler im Rhein-Main: Discovery vor Ort, Pilot-Phase mit 2–3 Vor-Ort-Terminen, danach Remote mit gelegentlichen Synchronisations-Treffen vor Ort.
TXT;

        $agencyLocal = <<<'TXT'
**Bankenviertel-Compliance** — Wenn Ihr Geschäftsmodell Berührungspunkte mit dem Frankfurter Finanzplatz hat, kommen häufig BAIT- oder VAIT-Anforderungen ins Spiel. Eine spezialisierte Agentur mit Erfahrung im Banken-Umfeld ist hier wertvoller als eine generische.

**Logistik und Cargo-Workflow** — Frankfurt ist Logistik-Hub. Plattformen für Speditionen, Cargo-Dienstleister und Zoll-Workflows haben branchenspezifische Anforderungen, die nicht jeder Anbieter kennt.

**International Workforce** — viele Frankfurter Mittelständler haben ein hohes Niveau an englischsprachigen Mitarbeitern und externen Dienstleistern. Eine Plattform mit Mehrsprachen-Support von Anfang an spart später aufwändige Nachrüstungen.
TXT;

        $agencyConclusion = <<<'TXT'
Die richtige Software-Agentur für ein Plattform-Projekt im Mittelstand finden Sie nicht über eine Google-Top-10-Liste, sondern über drei bis fünf strukturierte Erst-Gespräche, in denen Sie die genannten sieben Kriterien systematisch abprüfen. Vergleichen lässt sich das ehrlich nur, wenn alle Anbieter dieselbe Anforderungs-Grundlage bekommen — das ist der eigentliche Wert einer schriftlichen Discovery, bevor Sie das erste Angebot einholen.

Wir sind in Frankfurt am Main ansässig, arbeiten überwiegend mit Mittelständlern aus dem Rhein-Main-Gebiet zusammen und betreuen unsere Kunden typisch über mehrere Jahre. Falls Sie für Ihre Plattform-Entscheidung eine fundierte zweite Meinung brauchen oder bereits Angebote vorliegen haben — der Discovery-Workshop ist auch dafür gemacht: 990 € netto, 2 Stunden, dokumentiertes Discovery-Dokument. Sie nutzen das Ergebnis unabhängig davon, ob wir am Ende für Sie umsetzen oder nicht.
TXT;

        return [
            [
                'slug' => ['de' => 'was-kostet-b2b-plattform', 'en' => 'cost-of-a-custom-b2b-platform'],
                'category' => ['de' => 'Digitale Plattformen', 'en' => 'Digital Platforms'],
                'title' => ['de' => 'Was kostet eine maßgeschneiderte B2B-Plattform? Kostenfaktoren für Mittelständler', 'en' => 'What does a custom B2B platform cost? Cost factors for mid-sized companies'],
                'meta_title' => ['de' => 'B2B-Plattform Kosten — Was kostet eine eigene Webanwendung? · Mittelstand'],
                'meta_description' => ['de' => 'Was kostet eine maßgeschneiderte B2B-Plattform? Realistische Kostenspannen für Mittelständler, die fünf wichtigsten Preistreiber, Pilot-vs-Vollprojekt — von einem Frankfurter Plattform-Entwickler.'],
                'excerpt' => ['de' => 'Realistische Kostenspannen für maßgeschneiderte B2B-Plattformen im Mittelstand — und die fünf Faktoren, die den Preis am stärksten bestimmen. Mit Beispielen und Pilot-statt-Komplettprojekt-Strategie.'],
                'intro' => $costIntro,
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
                'published_at' => now()->subHours(2),
            ],
            [
                'slug' => 'software-agentur-frankfurt-mittelstand',
                'category' => 'Anbieter-Auswahl',
                'title' => 'Software-Agentur in Frankfurt finden — Worauf Mittelständler achten sollten',
                'meta_title' => 'Software-Agentur Frankfurt für Mittelstand · Auswahl-Leitfaden 2026',
                'meta_description' => 'Sie suchen eine Software-Agentur in Frankfurt für eine maßgeschneiderte B2B-Plattform? Worauf Mittelständler bei der Anbieter-Auswahl wirklich achten sollten — Größe, Methodik, Tech-Stack, Local Setup, Vertrag.',
                'excerpt' => 'Auswahl-Kriterien für Mittelständler, die in Frankfurt und Rhein-Main eine Software-Agentur für eine maßgeschneiderte B2B-Plattform suchen. Sieben Kriterien jenseits von „macht doch jeder" — und drei Warnsignale.',
                'intro' => $agencyIntro,
                'sections' => [
                    ['heading' => 'Welche Anbieter-Kategorien gibt es im Rhein-Main-Gebiet?', 'content' => $agencyCategories],
                    ['heading' => 'Sieben Kriterien für die Auswahl', 'content' => $agencyCriteria],
                    ['heading' => 'Drei Warnsignale, bei denen Sie vorsichtig werden sollten', 'content' => $agencyWarnings],
                    ['heading' => 'Anbieter-Suche im Rhein-Main: praktische Quellen', 'content' => $agencySources],
                    ['heading' => 'Vor-Ort vs. Remote: Was lohnt sich wirklich?', 'content' => $agencyOnsite],
                    ['heading' => 'Standortbezogene Themen, die in Frankfurt oft eine Rolle spielen', 'content' => $agencyLocal],
                ],
                'conclusion' => $agencyConclusion,
                'read_time' => 13,
                'is_published' => true,
                'published_at' => now()->subHour(),
            ],
        ];
    }
};
