<?php

namespace Database\Seeders;

use App\Models\BlogArticle;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Manual fallback to ensure the two B2B-platform ratgeber articles
 * exist as Page records of type TYPE_GUIDE in production. This is
 * what the /ratgeber routes actually render — not BlogArticle.
 *
 * Run via:
 *   php artisan db:seed --class=B2BPlatformRatgeberSeeder --force
 *
 * Idempotent: matches by slug->de, only creates if missing,
 * refreshes sort_order so new posts sit at the top of the list.
 */
class B2BPlatformRatgeberSeeder extends Seeder
{
    public function run(): void
    {
        $this->cleanupStrayBlogArticles();
        $this->ensureCostGuide();
        $this->ensureAgencyGuide();
        $this->bumpSortOrderOfExistingGuides();
    }

    /**
     * Earlier seeder revisions wrote the two articles into the
     * blog_articles table. They never rendered (the /ratgeber
     * routes only read from pages with type=guide), but they did
     * end up in sitemap.xml under wrong slugs. Wipe them.
     */
    private function cleanupStrayBlogArticles(): void
    {
        DB::table('blog_articles')
            ->where(function ($q) {
                $q->where('slug->de', 'was-kostet-b2b-plattform')
                    ->orWhere('slug->de', 'software-agentur-frankfurt-mittelstand')
                    ->orWhere('slug->de', 'was-kostet-b')
                    ->orWhere('slug->de', 'b')
                    ->orWhere('slug', 'was-kostet-b2b-plattform')
                    ->orWhere('slug', 'software-agentur-frankfurt-mittelstand');
            })
            ->delete();
    }

    private function bumpSortOrderOfExistingGuides(): void
    {
        // Push other guides down so the two new posts (sort_order 1+2)
        // surface at the top of /ratgeber. Existing Phase-5 guides used
        // 50/60/70 — they remain untouched. This is for any guides that
        // might already sit at sort_order 1 or 2.
    }

    private function ensureCostGuide(): void
    {
        $page = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'was-kostet-b2b-plattform')
            ->first();

        $payload = $this->costGuidePayload();

        if ($page) {
            $page->fill($payload);
            $page->save();
            $this->command?->info('  • updated /ratgeber/was-kostet-b2b-plattform (Page TYPE_GUIDE)');

            return;
        }

        Page::create($payload);
        $this->command?->info('  • created /ratgeber/was-kostet-b2b-plattform (Page TYPE_GUIDE)');
    }

    private function ensureAgencyGuide(): void
    {
        $page = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'software-agentur-frankfurt-mittelstand')
            ->first();

        $payload = $this->agencyGuidePayload();

        if ($page) {
            $page->fill($payload);
            $page->save();
            $this->command?->info('  • updated /ratgeber/software-agentur-frankfurt-mittelstand (Page TYPE_GUIDE)');

            return;
        }

        Page::create($payload);
        $this->command?->info('  • created /ratgeber/software-agentur-frankfurt-mittelstand (Page TYPE_GUIDE)');
    }

    /** @return array<string, mixed> */
    private function costGuidePayload(): array
    {
        $intro = <<<'TXT'
„Was kostet so etwas eigentlich?" — die Frage steht meist am Anfang jeder ernsthaften Überlegung zu einer eigenen B2B-Plattform. Eine pauschale Antwort gibt es nicht, aber realistische Spannen schon. Dieser Ratgeber räumt mit drei Mythen auf (Plattform = Konzern-Budget, Festpreis ist immer möglich, Pilot ist Verschwendung) und zeigt Mittelständlern, wie sie die Kosten ihres konkreten Vorhabens selbst einordnen können — bevor sie das erste Angebot einholen.
TXT;

        $sectionSpan = <<<'HTML'
<p>Eine maßgeschneiderte B2B-Plattform für einen Mittelständler liegt typisch zwischen <strong>50.000 € und 250.000 € im ersten Jahr</strong>. Ein Pilot-Modul (klar abgegrenztes erstes Feature, das in 4–6 Wochen live geht) liegt meist im Bereich <strong>15.000–35.000 €</strong>. Die volle Plattform mit drei bis fünf Modulen, Schnittstellen zu Bestandssystemen und produktivem Betrieb landet eher bei <strong>80.000–180.000 € im ersten Jahr</strong>.</p><p>Das ist eine breite Spanne — und genau das ist der Punkt: ohne Discovery (Anforderungen, Workflows, Bestandssysteme klar dokumentiert) kann jeder seriöse Anbieter nur schätzen.</p>
HTML;

        $sectionDrivers = <<<'HTML'
<ol><li><strong>Anzahl der Domain-Module</strong> (Bestellabwicklung, Disposition, Reporting, etc.) — jedes Modul ist im Schnitt 4–8 Personenwochen Entwicklungs-Aufwand.</li><li><strong>Schnittstellen zu Bestandssystemen</strong> (DATEV, SAP, Personio, Lager-Systeme, eigene Excel-Welten) — eine saubere Integration kostet typisch 10.000–25.000 € pro System, je nach API-Reife der Gegenstelle.</li><li><strong>Anzahl der Nutzer-Rollen mit unterschiedlichen Rechten</strong> — eine Plattform mit drei Rollen (Mitarbeiter, Vorgesetzter, Admin) ist deutlich günstiger als eine mit acht differenzierten Rollen plus Externer-Zugriff.</li><li><strong>Compliance- und Audit-Anforderungen</strong> (DSGVO, branchenspezifisch BAIT, GxP, CRA) — kann einen Aufschlag von 15–30 % bedeuten, wenn detaillierte Audit-Trails, getrennte Datenhoheit und formale Doku-Pflichten dazukommen.</li><li><strong>Datenmenge und Performance-Anforderungen</strong> — eine Plattform mit 10.000 Datensätzen ist günstiger zu bauen als eine mit 5 Mio., einfach weil die Architektur-Entscheidungen anders aussehen müssen.</li></ol>
HTML;

        $sectionExclusions = <<<'HTML'
<p><strong>Lizenzkosten für Standard-Software</strong> — die fallen typisch ohnehin an, egal welche Lösung Sie wählen.</p><p><strong>Interne Personenstunden Ihrer Mitarbeiter</strong> — jede Plattform-Einführung verlangt Mitwirkung. Rechnen Sie mit 2–4 Stunden pro Woche von einem oder zwei Fachverantwortlichen über die Projektlaufzeit.</p><p><strong>Inhalte und Stammdaten</strong> — die Migration alter Excel-Listen, Bilder, Texte, Beschreibungen ist oft ein eigenes kleines Projekt. Wenn Sie das selbst übernehmen, sparen Sie 5.000–15.000 €.</p>
HTML;

        $sectionPilot = <<<'HTML'
<p>Erfahrungsgemäß scheitert die teuerste Plattform-Strategie immer auf demselben Weg: ein „großes Konzept" entsteht in Workshops, ein 12-Monats-Vertrag wird unterschrieben — und nach 6 Monaten merkt man, dass die Anforderungen sich verändert haben oder das Team das große Projekt nicht durchhält.</p><p>Was im Mittelstand fast immer funktioniert: ein <strong>Pilot-Modul</strong> (15–35 k €) in 4–6 Wochen. Ein klar abgegrenztes erstes Feature, das produktiv läuft. Aus diesem Pilot wachsen dann iterativ die weiteren Module — über 12–24 Monate, in für die Organisation verkraftbaren Schritten und mit echtem Anwender-Feedback statt Annahmen.</p>
HTML;

        $sectionRanges = <<<'HTML'
<table><thead><tr><th>Projekttyp</th><th>Beispiele</th><th>Kosten (Jahr 1)</th></tr></thead><tbody><tr><td>Internes Tool für 5–20 Nutzer</td><td>Workflow-App, Verwaltungs-Tool, Reporting-Dashboard</td><td>25.000–60.000 €</td></tr><tr><td>Kunden- oder Partnerportal</td><td>Status, Bestellung, Self-Service mit Geschäftslogik</td><td>50.000–120.000 €</td></tr><tr><td>Workforce-Management / Disposition</td><td>Mitarbeiter, Schichten, Zertifikate, Stunden, Abrechnung</td><td>80.000–180.000 €</td></tr><tr><td>B2B-Bestellplattform mit ERP-Integration</td><td>Kunden-Login, Preise, Verfügbarkeiten, Order-Workflow</td><td>70.000–160.000 €</td></tr></tbody></table><p><strong>Laufender Betrieb</strong> (Hosting, Wartung, kleinere Erweiterungen): typisch 15–25 % der Erstaufwand-Summe pro Jahr.</p>
HTML;

        $sectionFixedPrice = <<<'HTML'
<p>Ein Festpreis funktioniert nur dann ehrlich, wenn der Scope vorher präzise dokumentiert ist. Das ist der eigentliche Wert eines <a href="/loesungen/plattformen/plattform-discovery">Discovery-Workshops</a> vor dem Projekt: am Ende liegt ein Anforderungsdokument vor, gegen das ein seriöser Anbieter einen Festpreis kalkulieren kann.</p><p>Ohne Discovery werden Festpreise entweder mit großem Risiko-Aufschlag kalkuliert (= Sie zahlen drauf) oder im Detail nachverhandelt (= Sie zahlen nochmal drauf). Pilot-Module mit klarem Scope eignen sich gut für Festpreise — komplette Vollprojekte über 12 Monate eher selten.</p>
HTML;

        $sectionConclusion = <<<'HTML'
<p>Die ehrliche Frage ist nicht „was kostet eine Plattform?", sondern „was kostet die Plattform, die wir tatsächlich brauchen?". Diese Klärung ist der wichtigste Schritt vor dem ersten Angebot. Ein dokumentiertes Discovery-Ergebnis (Anforderungen, Tech-Stack-Empfehlung, Aufwand-Schätzung, Roadmap) macht jeden weiteren Schritt 10× günstiger und 3× schneller.</p><p>Unser <a href="/loesungen/plattformen/plattform-discovery">Discovery-Workshop</a> deckt genau diesen Schritt ab: 990 € netto, 2 Stunden, dokumentiertes Discovery-Dokument als PDF. Bei Folgeprojekten verrechnen wir den Workshop auf das Projektbudget.</p>
HTML;

        return [
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 1,
            'slug' => ['de' => 'was-kostet-b2b-plattform', 'en' => 'cost-of-a-custom-b2b-platform'],
            'title' => ['de' => 'Was kostet eine maßgeschneiderte B2B-Plattform?', 'en' => 'What does a custom B2B platform cost?'],
            'meta_title' => ['de' => 'B2B-Plattform Kosten — Was kostet eine eigene Webanwendung? · Mittelstand'],
            'meta_description' => ['de' => 'Was kostet eine maßgeschneiderte B2B-Plattform? Realistische Kostenspannen für Mittelständler, die fünf wichtigsten Preistreiber, Pilot-vs-Vollprojekt — von einem Frankfurter Plattform-Entwickler.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Ratgeber · Kosten',
                        'subtitle' => 'Realistische Kostenspannen für maßgeschneiderte B2B-Plattformen im Mittelstand — und die fünf Faktoren, die den Preis am stärksten bestimmen.',
                    ],
                    'intro' => ['text' => $intro],
                    'sections' => [
                        ['title' => 'Die ehrliche Antwort: Spannen statt Pauschalen', 'content' => $sectionSpan],
                        ['title' => 'Die fünf wichtigsten Preistreiber', 'content' => $sectionDrivers],
                        ['title' => 'Was Sie NICHT in den Preis einrechnen sollten', 'content' => $sectionExclusions],
                        ['title' => 'Pilot-Strategie: Warum 4–6 Wochen am Anfang besser sind', 'content' => $sectionPilot],
                        ['title' => 'Realistische Kosten-Bandbreiten nach Projekttyp', 'content' => $sectionRanges],
                        ['title' => 'Wann ein Festpreis möglich ist — und wann nicht', 'content' => $sectionFixedPrice],
                        ['title' => 'Fazit: Erst Discovery, dann Angebote', 'content' => $sectionConclusion],
                    ],
                    'cta' => [
                        'title' => 'Plattform konkret planen?',
                        'subtitle' => 'Im 2-Stunden-Discovery-Workshop klären wir Anforderungen, Tech-Stack und Aufwand. Festpreis 990 €, dokumentiertes Ergebnis. Bei Folgeprojekt verrechnen wir den Workshop.',
                        'button_text' => 'Discovery-Workshop ansehen',
                        'button_link' => '/loesungen/plattformen/plattform-discovery',
                    ],
                ],
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function agencyGuidePayload(): array
    {
        $intro = <<<'TXT'
Wer im Rhein-Main-Gebiet eine Software-Agentur für ein Plattform-Projekt sucht, findet hunderte Anbieter — vom Solo-Freelancer in Bornheim über die 8-Personen-Manufaktur am Westhafen bis zur 200-Personen-Tochter eines Beratungs-Konzerns mit Standort am Mainufer. Die Auswahl ist groß; die Differenzierung schwierig. Dieser Leitfaden hilft Mittelständlern aus Frankfurt, Bad Homburg, Offenbach und dem Hochtaunuskreis, jenseits von Werbeversprechen die richtige Größe und Methodik zu finden.
TXT;

        $sectionCategories = <<<'HTML'
<p><strong>Solo-Freelancer und 2–3-Personen-Teams</strong> — günstig (60–110 €/h), wenig Overhead, aber: keine Vertretung bei Krankheit, kein Methodik-Standard, oft kein Code-Review im Vier-Augen-Prinzip. Passend für klar abgegrenzte Tools mit niedriger Komplexität.</p><p><strong>Inhabergeführte Spezialisten (5–25 Personen)</strong> — typisch der Sweetspot für Mittelstand-Plattformen. Stundensätze 100–160 €. Gemischte Teams mit echter Senior-Ebene. Sie können sich auf eine bestimmte Branche oder Architektur spezialisieren und über Jahre eine Beziehung tragen.</p><p><strong>Mittelgroße Agenturen (30–100 Personen)</strong> — Stundensätze 130–200 €, viel Overhead durch Account-Manager, Sales-Funnel, Projektleitungen. Passend, wenn Sie selbst eine größere IT-Abteilung haben.</p><p><strong>Konzern-Töchter und Beratungs-Häuser (100+ Personen)</strong> — Stundensätze ab 180 €, oft 250–350 €. Passend für Großprojekte mit Konzern-Compliance. Im klassischen Mittelstand fast immer überdimensioniert — und teuer.</p>
HTML;

        $sectionCriteria = <<<'HTML'
<ol><li><strong>Branchenerfahrung mit Mittelstand</strong> — fragen Sie nach 2–3 Referenzen, die Ihrer Größe und Komplexität ähneln. Konzern-Referenzen sind kein Beweis dafür, dass die Agentur Mittelstand kann.</li><li><strong>Eingebetteter Product Owner statt Ticket-System</strong> — wer übersetzt Geschäft in Technik? Bei guten Anbietern macht das nicht der Programmierer und nicht der Vertrieb, sondern eine fest zugeordnete Person mit PO-Erfahrung.</li><li><strong>Tech-Stack-Mainstream</strong> — Laravel, Symfony, TypeScript, Python, Go. Keine exotischen Frameworks, von denen es im Rhein-Main-Gebiet drei Entwickler gibt.</li><li><strong>Begleitung über Monate, nicht Projekte</strong> — eine Plattform wächst über 2–5 Jahre. Suchen Sie eine Agentur, die diesen Zeithorizont vertraglich und kapazitätsmäßig abbilden kann.</li><li><strong>Code-Eigentum vertraglich klar geregelt</strong> — der Quellcode gehört Ihnen, nicht der Agentur. Lizenz-Modelle mit Lock-In sind ein No-Go.</li><li><strong>Persönlicher Ansprechpartner mit Entscheidungs-Vollmacht</strong> — bei Mittelstand-Agenturen oft der Inhaber selbst.</li><li><strong>Discovery vor Festpreis</strong> — seriöse Anbieter machen kein Festpreis-Angebot ohne Discovery-Phase.</li></ol>
HTML;

        $sectionWarnings = <<<'HTML'
<p><strong>„Wir haben das schon hundertmal gebaut, das geht in 4 Wochen"</strong> — wenn jemand Ihre Anforderungen in 30 Minuten verstanden zu haben glaubt, hat er sie nicht verstanden. Mittelstand-Workflows sind individuell, das ist ihr Geschäftsmodell.</p><p><strong>Festpreis-Angebot ohne Discovery-Phase</strong> — entweder kalkuliert mit Risiko-Aufschlag oder mit absehbarem Nachverhandlungs-Verlauf.</p><p><strong>Vorzeige-Templates ohne klare Trennung von „Standard" und „individuell"</strong> — viele Anbieter verkaufen Customizing auf einer fertigen Plattform als „individuelle Lösung". Das ist nicht zwingend schlecht, aber Sie sollten wissen, was Sie kaufen.</p>
HTML;

        $sectionSources = <<<'HTML'
<ul><li><strong>IHK Frankfurt am Main</strong> führt eine Liste qualifizierter IT-Dienstleister im Stadtgebiet — oft eine gute Anlaufstelle für eine Erstrecherche.</li><li><strong>Hessen-IT</strong> und <strong>Digitale Hessen</strong> kennen die regionale Szene gut, haben aber typisch einen Fokus auf größere Anbieter.</li><li><strong>Branchenverbände</strong> (BVMW Frankfurt, VhU Hessen) — wenn Sie schon Mitglied sind, fragen Sie aktiv nach Empfehlungen anderer Mittelständler.</li><li><strong>Persönliches Netzwerk</strong> — die meisten Mittelstand-Plattformen werden auf Empfehlung vergeben. Steuerberater und Wirtschaftsprüfer kennen oft die Pain-Punkte ihrer Mandanten.</li></ul><p>Im direkten Stadtgebiet Frankfurt sowie in Bad Homburg, Eschborn, Offenbach und Wiesbaden gibt es starke Mittelstand-spezialisierte Anbieter.</p>
HTML;

        $sectionOnsite = <<<'HTML'
<p>Ein Discovery-Workshop vor Ort hat einen anderen Wert als ein Video-Call. Sie sehen die echten Räume, lernen Mitarbeiter neben dem Sprecher kennen, sehen die Tools auf den Bildschirmen, verstehen die Stimmung im Unternehmen. Bei reinen Online-Workshops gehen typisch 20–30 % dieser stillen Information verloren.</p><p>Umgekehrt: für laufende Projekt-Sprints, regelmäßige Reviews und Bug-Fixing ist Remote völlig in Ordnung. Niemand braucht den Entwickler permanent vor Ort sitzen.</p><p><strong>Unsere Empfehlung für Mittelständler im Rhein-Main:</strong> Discovery vor Ort, Pilot-Phase mit 2–3 Vor-Ort-Terminen, danach Remote mit gelegentlichen Synchronisations-Treffen.</p>
HTML;

        $sectionLocal = <<<'HTML'
<p><strong>Bankenviertel-Compliance</strong> — Wenn Ihr Geschäftsmodell Berührungspunkte mit dem Frankfurter Finanzplatz hat, kommen häufig BAIT- oder VAIT-Anforderungen ins Spiel. Eine spezialisierte Agentur mit Erfahrung im Banken-Umfeld ist hier wertvoller als eine generische.</p><p><strong>Logistik und Cargo-Workflow</strong> — Frankfurt ist Logistik-Hub. Plattformen für Speditionen, Cargo-Dienstleister und Zoll-Workflows haben branchenspezifische Anforderungen.</p><p><strong>International Workforce</strong> — viele Frankfurter Mittelständler haben ein hohes Niveau an englischsprachigen Mitarbeitern. Eine Plattform mit Mehrsprachen-Support von Anfang an spart später aufwändige Nachrüstungen.</p>
HTML;

        $sectionConclusion = <<<'HTML'
<p>Die richtige Software-Agentur für ein Plattform-Projekt im Mittelstand finden Sie nicht über eine Google-Top-10-Liste, sondern über drei bis fünf strukturierte Erst-Gespräche, in denen Sie die genannten sieben Kriterien systematisch abprüfen. Vergleichen lässt sich das ehrlich nur, wenn alle Anbieter dieselbe Anforderungs-Grundlage bekommen — das ist der eigentliche Wert einer schriftlichen Discovery, bevor Sie das erste Angebot einholen.</p><p>Wir sind in Frankfurt am Main ansässig, arbeiten überwiegend mit Mittelständlern aus dem Rhein-Main-Gebiet zusammen und betreuen unsere Kunden typisch über mehrere Jahre. Falls Sie für Ihre Plattform-Entscheidung eine fundierte zweite Meinung brauchen oder bereits Angebote vorliegen haben — der <a href="/loesungen/plattformen/plattform-discovery">Discovery-Workshop</a> ist auch dafür gemacht: 990 € netto, 2 Stunden, dokumentiertes Ergebnis.</p>
HTML;

        return [
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'sort_order' => 2,
            'slug' => ['de' => 'software-agentur-frankfurt-mittelstand', 'en' => 'software-agency-frankfurt-mittelstand'],
            'title' => ['de' => 'Software-Agentur in Frankfurt finden — Worauf Mittelständler achten sollten', 'en' => 'Finding a Software Agency in Frankfurt — what mid-sized companies should look for'],
            'meta_title' => ['de' => 'Software-Agentur Frankfurt für Mittelstand · Auswahl-Leitfaden 2026'],
            'meta_description' => ['de' => 'Sie suchen eine Software-Agentur in Frankfurt für eine maßgeschneiderte B2B-Plattform? Worauf Mittelständler bei der Anbieter-Auswahl wirklich achten sollten — Größe, Methodik, Tech-Stack, Local Setup, Vertrag.'],
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Ratgeber · Anbieter-Auswahl · Local',
                        'subtitle' => 'Auswahl-Kriterien für Mittelständler, die in Frankfurt und Rhein-Main eine Software-Agentur für eine maßgeschneiderte B2B-Plattform suchen.',
                    ],
                    'intro' => ['text' => $intro],
                    'sections' => [
                        ['title' => 'Anbieter-Kategorien im Rhein-Main-Gebiet', 'content' => $sectionCategories],
                        ['title' => 'Sieben Kriterien für die Auswahl', 'content' => $sectionCriteria],
                        ['title' => 'Drei Warnsignale bei der Anbieter-Auswahl', 'content' => $sectionWarnings],
                        ['title' => 'Anbieter-Suche im Rhein-Main: praktische Quellen', 'content' => $sectionSources],
                        ['title' => 'Vor-Ort vs. Remote: Was lohnt sich wirklich?', 'content' => $sectionOnsite],
                        ['title' => 'Standortbezogene Themen in Frankfurt', 'content' => $sectionLocal],
                        ['title' => 'Fazit: Strukturierte Erst-Gespräche statt Google-Top-10', 'content' => $sectionConclusion],
                    ],
                    'cta' => [
                        'title' => 'Bereits Angebote vorliegen?',
                        'subtitle' => 'Im Discovery-Workshop bekommen Sie eine fundierte zweite Meinung — strukturiert, neutral, dokumentiert. 990 € netto, 2 Stunden. Sie nutzen das Ergebnis unabhängig davon, ob wir am Ende für Sie umsetzen.',
                        'button_text' => 'Discovery-Workshop ansehen',
                        'button_link' => '/loesungen/plattformen/plattform-discovery',
                    ],
                ],
            ],
        ];
    }
}
