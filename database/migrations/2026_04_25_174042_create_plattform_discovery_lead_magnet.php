<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Phase C — Create the Discovery-Workshop lead magnet as a Solution Detail
 * page under the Plattformen hub: /loesungen/plattformen/plattform-discovery.
 *
 * The workshop is positioned as a fixed-price (990 €), 2-hour session that
 * produces a documented output (Discovery-Dokument: requirements, tech stack
 * recommendation, effort estimate, roadmap). It serves three purposes:
 *
 * 1. Lower the entry barrier from "schedule a sales call" to "book a paid
 *    workshop with deliverable" — qualifies serious buyers, filters tire-
 *    kickers.
 * 2. Generate a tangible artifact that either becomes the brief for a
 *    follow-on platform engagement, or that the customer takes elsewhere.
 *    Either way the customer has paid for value received.
 * 3. Demonstrate the methodology — exactly the discovery/PO capability that
 *    differentiates the new B2B-platform positioning.
 *
 * Idempotent: re-running updates the existing page rather than creating a
 * duplicate.
 */
return new class extends Migration
{
    public function up(): void
    {
        $plattformenHub = Page::where('type', Page::TYPE_SOLUTION_HUB)
            ->where('slug->de', 'plattformen')
            ->whereNull('parent_id')
            ->first();

        if (! $plattformenHub) {
            // Hub not present — skip, this is not a critical migration target
            return;
        }

        $page = Page::where('slug->de', 'plattform-discovery')->first();

        $payload = [
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $plattformenHub->id,
            'is_active' => true,
            'sort_order' => 1,
            'slug' => [
                'de' => 'plattform-discovery',
                'en' => 'plattform-discovery',
            ],
            'title' => [
                'de' => 'Plattform-Discovery — 2-Stunden-Workshop für Mittelständler',
                'en' => 'Platform Discovery — 2-hour workshop for Mittelstand',
            ],
            'meta_title' => [
                'de' => 'Plattform-Discovery — Workshop zum Festpreis · 990 € · Mit dokumentiertem Ergebnis',
            ],
            'meta_description' => [
                'de' => 'Klären Sie in 2 Stunden, ob eine eigene Plattform für Sie sinnvoll ist — und wie sie aussehen sollte. Festpreis 990 €, schriftliches Discovery-Dokument, keine Verkaufsgespräch-Falle.',
            ],
            'content' => [
                'de' => [
                    'hero' => [
                        'category' => 'Plattform-Discovery · Workshop',
                        'tagline' => 'Klären Sie in 2 Stunden, ob eine eigene Plattform für Sie sinnvoll ist — und wenn ja, wie sie aussehen sollte. Festpreis 990 €. Mit dokumentiertem Ergebnis.',
                    ],
                    'meta' => [
                        ['label' => 'Format', 'value' => '2-Stunden-Workshop · vor Ort oder remote'],
                        ['label' => 'Preis', 'value' => '990 € netto · Festpreis'],
                        ['label' => 'Output', 'value' => 'Discovery-Dokument als PDF'],
                        ['label' => 'Termine', 'value' => 'typisch innerhalb von 2 Wochen'],
                        ['label' => 'Verbindlichkeit', 'value' => 'Keine — Sie entscheiden danach'],
                    ],
                    'why_native' => [
                        'title' => 'Was Sie aus dem Workshop mitnehmen',
                        'text' => 'Plattform-Projekte scheitern selten an der Technik — sondern an unklaren Anforderungen, die zu spät auffallen. Bevor Sie 50.000 € und 6 Monate in eine Lösung stecken, sollten Sie mit Klarheit starten: Was muss die Plattform leisten? Welche Daten und Prozesse stecken dahinter? Welche Risiken sind real? Welcher Tech-Stack passt zu Ihrer Situation?',
                        'items' => [
                            'Konkrete Anforderungs-Liste statt vager Feature-Wünsche',
                            'Empfehlung für den passenden Tech-Stack — neutral und begründet',
                            'Aufwand-Schätzung, mit der Sie planen können',
                            'Roadmap mit klar getrennten Phasen (Pilot, Ausbau, Skalierung)',
                            'Risiko-Liste — was kann schiefgehen, wie verhindern wir das',
                            'Schriftliche Dokumentation, die Sie behalten — auch ohne Folgeprojekt',
                        ],
                    ],
                    'when' => [
                        'title' => 'Für wen der Workshop sinnvoll ist',
                        'intro' => 'Der Discovery-Workshop ist für die konkrete Phase gemacht, in der Sie zwischen Standard-Software, Eigenentwicklung und DIY-Tools stehen und eine fundierte Entscheidung brauchen:',
                        'conditions' => [
                            'Sie überlegen, eine eigene Plattform bauen zu lassen — und haben noch keinen klaren Plan, wie das aussehen soll.',
                            'Standard-Software (Personio, SAP, Shopify, Microsoft Dynamics) deckt Ihre Workflows nicht ab.',
                            'Sie haben bereits Angebote von 2–3 Agenturen und wollen eine fundierte zweite Meinung — strukturiert, neutral, dokumentiert.',
                            'Sie erwägen DIY/Lovable/AI-Builder und wollen vorher prüfen, ob das zu Ihrem Anwendungsfall passt — oder ob Komplexität und Compliance dagegen sprechen.',
                            'Ihr Budget liegt im 5-stelligen Bereich oder höher. Bei kleineren Vorhaben ist unser Gründerpaket oft die bessere Wahl.',
                        ],
                        'note' => 'Wenn Sie bereits eine fertige Anforderungs-Spezifikation und ein klares Budget haben, brauchen Sie keinen Discovery-Workshop — dann sprechen wir direkt über das Folgeprojekt.',
                    ],
                    'features' => [
                        [
                            'title' => 'Vor dem Workshop',
                            'description' => 'Sie bekommen ein kurzes Briefing-Template und füllen es aus (15–30 Minuten Aufwand). Bestandstools und -systeme können Sie als Liste oder Screenshots beifügen — mehr ist nicht nötig.',
                            'items' => [
                                'Briefing-Template per E-Mail (Branche, Geschäftsmodell, Stakeholder, Bestandssysteme)',
                                'Optional: Liste der heutigen Tools/Excel-Listen, die Sie ablösen wollen',
                                'Optional: bestehende Angebote anderer Anbieter (für eine fundierte zweite Meinung)',
                            ],
                        ],
                        [
                            'title' => 'Im Workshop (2 Stunden)',
                            'description' => 'Strukturierter Workshop, bei dem wir gemeinsam Anforderungen aufnehmen, Workflows skizzieren, Stakeholder mappen, Tech-Optionen besprechen und Risiken benennen. Vor Ort in Frankfurt oder remote per Video.',
                            'items' => [
                                'Anforderungs-Workshop mit strukturiertem Discovery-Framework',
                                'Workflow-Skizzen direkt am Board (analog oder digital)',
                                'Stakeholder-Mapping: wer entscheidet, wer setzt um, wer ist betroffen',
                                'Tech-Stack-Optionen mit Trade-Offs (Make, Buy, Build, Hybrid)',
                                'Risiko-Identifikation mit Mitigation-Vorschlägen',
                            ],
                        ],
                        [
                            'title' => 'Nach dem Workshop (Aufbereitung)',
                            'description' => 'Wir bereiten die Ergebnisse als Discovery-Dokument auf. Lieferung typisch 5–7 Werktage nach dem Workshop. Inklusive einem Follow-up-Termin (30 Min) für Rückfragen.',
                            'items' => [
                                'Discovery-Dokument als PDF (typisch 12–20 Seiten)',
                                'Anforderungs-Liste, priorisiert nach Must-Have / Nice-to-Have',
                                'Tech-Stack-Empfehlung mit Begründung',
                                'Aufwand-Schätzung (Personenmonate, Budget-Range)',
                                'Phasenplan: Pilot, Ausbau, Skalierung',
                                'Follow-up-Termin (30 Min) für Klärung von Rückfragen',
                            ],
                        ],
                    ],
                    'differentiation' => [
                        'title' => 'Warum nicht das übliche kostenlose Sales-Gespräch?',
                        'text' => "Kostenlose Erstgespräche kosten beide Seiten Zeit, ohne dass etwas Greifbares entsteht. Sie bekommen Marketing-Sprache und ein Angebot, dessen Grundlagen unklar bleiben. Wir bekommen unverbindliche Anfragen, von denen 80 % nie zu echten Projekten werden.\n\nDer bezahlte Workshop dreht das um: Sie zahlen für eine konkrete Leistung mit dokumentiertem Output — und behalten das Ergebnis, auch wenn wir nicht weiterarbeiten. Das filtert auf beiden Seiten ernsthaft Interessierte heraus, und beide Seiten kommen aus dem Workshop mit etwas Greifbarem.\n\nWir verzichten bewusst auf Discovery-Workshops, die zur Sales-Falle werden. Wenn Sie nach dem Workshop entscheiden, mit einem anderen Anbieter weiterzuarbeiten oder selbst zu bauen — gut so. Sie haben das Discovery-Dokument, Sie können es nutzen.",
                    ],
                    'solution' => [
                        'title' => 'Drei typische Wege nach dem Workshop',
                        'description' => 'Mit dem Discovery-Dokument können Sie weitermachen — bei uns oder anderswo. Drei Wege, die Discovery-Kunden typisch wählen:',
                        'items' => [
                            'Pilot-Modul: erste sichtbare Funktion in 4–6 Wochen, dann iterativ ausbauen',
                            'Vollprojekt: Plattform-Entwicklung über 3–12 Monate, eingebetteter Product Owner inklusive',
                            'Anderer Anbieter: Sie nehmen das Discovery-Dokument als Briefing für Ausschreibungen — das Ergebnis gehört Ihnen',
                        ],
                    ],
                    'growth' => [
                        'title' => 'Wenn aus dem Workshop ein Folgeprojekt wird',
                        'text' => 'Bei Folgeprojekten verrechnen wir die 990 € auf das Projektbudget — Sie zahlen den Workshop also faktisch nur, wenn wir nicht weiterarbeiten. Bei Vollprojekten beginnen wir typisch mit dem Pilot-Modul aus dem Discovery-Dokument: ein klar abgegrenztes erstes Feature, das in 4–6 Wochen Live ist und sofort Wert liefert. Daraus entwickelt sich dann iterativ die volle Plattform — über Monate, nicht über ein einziges Projekt.',
                    ],
                    'cta' => [
                        'title' => 'Workshop anfragen',
                        'subtitle' => 'Erste Termine typisch innerhalb von 2 Wochen verfügbar. Kurze E-Mail mit Ihrer Situation reicht — wir melden uns mit 2–3 Terminvorschlägen.',
                        'button_text' => 'Workshop anfragen',
                    ],
                ],
            ],
        ];

        if ($page) {
            $page->fill($payload);
            $page->save();
        } else {
            Page::create($payload);
        }
    }

    public function down(): void
    {
        $page = Page::where('slug->de', 'plattform-discovery')->first();
        if ($page) {
            $page->delete();
        }
    }
};
