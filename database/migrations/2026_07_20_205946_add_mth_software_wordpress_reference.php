<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Add the MTH Software reference as the last project in the portfolio.
 *
 * MTH Software GmbH & Co. KG is an established vendor of association-management
 * software (Vereinsverwaltung, 30+ years, 9.500+ associations). For their
 * WordPress platform we built the design and development, two custom plugins
 * (Roadmap & System-Status), a form-based ordering system with conditional
 * logic for complex software downloads, a training-video/support area, and
 * several custom post types with Meta Box fields so the client maintains the
 * content itself — backed by an ongoing maintenance contract.
 *
 * The owner gave permission to name MTH Software and reference publicly visible
 * facts (30+ years, 9.500+ associations). No confidential figures are used.
 *
 * This adds a new TYPE_REFERENCE_DETAIL page (slug `mth-software-wordpress-
 * plattform`) and appends the matching entry to the /referenzen overview
 * (content.projects) so it renders last. Idempotent, and a no-op when the
 * references overview page does not exist (mirrors the Normatec migrations).
 */
return new class extends Migration
{
    private const SLUG_DE = 'mth-software-wordpress-plattform';

    private const SLUG_EN = 'mth-software-wordpress-platform';

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
        $page->sort_order = (int) Page::where('type', Page::TYPE_REFERENCE_DETAIL)->max('sort_order') + 1;

        $page->setTranslation('slug', 'de', self::SLUG_DE);
        $page->setTranslation('slug', 'en', self::SLUG_EN);
        $page->setTranslation('title', 'de', 'MTH Software — WordPress-Plattform mit Custom-Plugins');
        $page->setTranslation('title', 'en', 'MTH Software — WordPress Platform with Custom Plugins');
        $page->setTranslation('meta_title', 'de', 'MTH Software — WordPress-Plattform & Custom-Plugins');
        $page->setTranslation('meta_title', 'en', 'MTH Software — WordPress Platform & Custom Plugins');
        $page->setTranslation('meta_description', 'de', 'WordPress-Plattform für MTH Software: Custom-Plugins für Roadmap & System-Status, Bestellsystem mit Conditional Logic, Schulungsvideos & CPTs zur Selbstpflege.');
        $page->setTranslation('meta_description', 'en', 'WordPress platform for MTH Software: custom plugins for roadmap & system status, a conditional-logic ordering system, training videos and self-managed custom post types.');

        $page->setTranslation('content', 'de', $this->contentDe());
        $page->save();

        $this->appendToOverview($overview);
    }

    public function down(): void
    {
        Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', self::SLUG_DE)
            ->delete();

        $overview = Page::where('type', Page::TYPE_REFERENCES)->first();
        if (! $overview) {
            return;
        }

        $content = $overview->getTranslation('content', 'de');
        if (! is_array($content)) {
            return;
        }

        $projects = $content['projects'] ?? [];

        $projects = array_values(array_filter(
            $projects,
            fn (array $project): bool => ($project['detail_slug'] ?? null) !== self::SLUG_DE
        ));

        // Re-number remaining entries so the sequence stays contiguous.
        foreach ($projects as $index => &$project) {
            $project['number'] = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
        }
        unset($project);

        $content['projects'] = $projects;
        $overview->setTranslation('content', 'de', $content);
        $overview->save();
    }

    private function appendToOverview(Page $overview): void
    {
        $content = $overview->getTranslation('content', 'de');

        // Overview content not in the expected translatable array shape → skip
        // safely rather than crash or overwrite existing project data.
        if (! is_array($content)) {
            return;
        }

        $projects = $content['projects'] ?? [];

        foreach ($projects as $project) {
            if (($project['detail_slug'] ?? null) === self::SLUG_DE) {
                return; // already present — keep idempotent
            }
        }

        $projects[] = $this->overviewEntry(count($projects) + 1);

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
                'category' => 'WordPress · Custom-Plugin-Entwicklung',
                'tagline' => 'Design, Entwicklung und laufende Betreuung der WordPress-Plattform von MTH Software — Anbieter von Vereinsverwaltungs-Software mit über 30 Jahren Erfahrung und mehr als 9.500 betreuten Vereinen. Von maßgeschneiderten Custom-Plugins für Roadmap und System-Status über ein formularbasiertes Bestellsystem mit Conditional Logic bis zum Schulungsvideo-Bereich — alles über Custom Post Types selbst pflegbar.',
            ],

            'meta' => [
                ['label' => 'Kunde', 'value' => 'MTH Software GmbH & Co. KG'],
                ['label' => 'Branche', 'value' => 'Software für Vereinsverwaltung'],
                ['label' => 'Website', 'value' => 'mth-software.de', 'link' => 'https://www.mth-software.de/'],
                ['label' => 'Leistung', 'value' => 'Design, Entwicklung & Custom-Plugins'],
                ['label' => 'Engagement', 'value' => 'Laufender Wartungsvertrag'],
                ['label' => 'Stack', 'value' => 'WordPress · PHP · Meta Box'],
            ],

            'description' => [
                'title' => 'Über das Projekt',
                'text' => 'MTH Software entwickelt seit über 30 Jahren Verwaltungssoftware für Vereine — den Vereins-Manager für die Mitgliederverwaltung und den Vereins-Profi für die Vereinsbuchhaltung. Über 9.500 Vereine arbeiten mit den Produkten. Für den digitalen Auftritt dieses etablierten Software-Anbieters haben wir die komplette WordPress-Plattform gestaltet und entwickelt: eine Website, die nicht nur informiert, sondern echte Aufgaben übernimmt — vom Software-Vertrieb über die Auslieferung komplexer Downloads bis zum Self-Service-Support. Der Anspruch: eine Plattform, die MTH Software weitgehend selbst pflegen kann, ohne für jede Änderung einen Entwickler zu brauchen — und die technisch dauerhaft gewartet und weiterentwickelt wird.',
            ],

            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Software-Anbieter braucht mehr als eine Broschüren-Website. Interessenten wollen Demoversionen testen, Bestandskunden brauchen die passende Vollversion und Updates, und komplexe Downloads müssen je nach Produktlinie, Version und Kundenstatus korrekt ausgeliefert werden. Gleichzeitig soll der Support entlastet werden, und das Team muss Inhalte wie Roadmap, System-Status und Schulungsvideos selbst pflegen können — ohne technisches Fachwissen.',
                'items' => [
                    'Zwei Produktlinien (Vereins-Manager & Vereins-Profi) je als Desktop- und Online-Variante',
                    'Komplexe Download-Logik: Demo, Vollversion und Update je nach Produkt und Kundenstatus',
                    'Support entlasten durch Self-Service mit Video-Tutorials und Handbüchern',
                    'Roadmap und System-Status transparent und aktuell halten',
                    'Redaktionelle Pflege durch das MTH-Team selbst — ohne Entwickler',
                    'Dauerhaft stabiler, sicherer und gewarteter Betrieb',
                ],
            ],

            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine maßgeschneiderte WordPress-Plattform mit zwei eigens entwickelten Custom-Plugins, einem formularbasierten Bestellsystem mit Conditional Logic und einem strukturierten Schulungs- und Supportbereich. Alle wiederkehrenden Inhalte laufen über Custom Post Types und Meta-Box-Felder, sodass MTH Software die Plattform eigenständig pflegt. Ein laufender Wartungsvertrag sichert Updates, Sicherheit und Weiterentwicklung ab.',
                'items' => [
                    'Custom-Plugin „Roadmap" — geplante Funktionen transparent und selbst pflegbar',
                    'Custom-Plugin „System-Status" — aktueller Betriebsstatus der Dienste auf einen Blick',
                    'Formularbasiertes Bestellsystem mit Conditional Logic für komplexe Software-Downloads',
                    'Schulungs- & Supportbereich mit strukturierten Video-Tutorials',
                    'Custom Post Types + Meta-Box-Felder für die einfache Selbststeuerung',
                    'Dauerhafter Wartungsvertrag für Sicherheit, Updates und Weiterentwicklung',
                ],
            ],

            'tech_stack' => [
                'WordPress · CMS & Fundament',
                'PHP · Custom-Plugin-Entwicklung',
                'Custom-Plugin: Roadmap',
                'Custom-Plugin: System-Status',
                'Meta Box · Custom Post Types & Felder',
                'Formularbasiertes Bestellsystem · Conditional Logic',
                'Schulungsvideo- & Support-Bereich',
                'Responsive Design · individuelles Theme',
                'Laufender Wartungsvertrag · Updates & Security',
            ],

            'features' => [
                [
                    'title' => 'Custom-Plugin: Roadmap',
                    'image' => '/images/references/mth-software/roadmap.png',
                    'description' => 'Ein eigens entwickeltes Plugin macht die Produkt-Roadmap transparent: Geplante und neue Funktionen werden strukturiert dargestellt und lassen sich vom MTH-Team ohne Entwickler pflegen. Kunden sehen jederzeit, was kommt.',
                    'items' => [
                        'Geplante und veröffentlichte Funktionen strukturiert dargestellt',
                        'Redaktionell selbst pflegbar über einen eigenen Post-Type',
                        'Statusanzeige pro Roadmap-Eintrag',
                        'Nahtlos in Design und Navigation integriert',
                    ],
                ],
                [
                    'title' => 'Custom-Plugin: System-Status',
                    'image' => '/images/references/mth-software/system-status.png',
                    'description' => 'Ein zweites Custom-Plugin zeigt den aktuellen Betriebsstatus der Dienste. Störungen und Wartungsfenster werden transparent kommuniziert — das schafft Vertrauen und entlastet den Support.',
                    'items' => [
                        'Aktueller Status der Systeme auf einen Blick',
                        'Störungen und Wartungshinweise selbst pflegbar',
                        'Vertrauensbildung durch Transparenz',
                        'Support-Entlastung durch Self-Service',
                    ],
                ],
                [
                    'title' => 'Bestellsystem mit Conditional Logic',
                    'image' => '/images/references/mth-software/bestellsystem.png',
                    'description' => 'Das Herzstück: ein formularbasiertes Bestellsystem, das die komplexe Download-Logik abbildet. Je nach Produktlinie, Version und Kundenstatus werden die passenden Optionen ein- oder ausgeblendet — Interessenten und Bestandskunden erhalten genau den richtigen Download.',
                    'items' => [
                        'Bedingte Formular-Logik je nach Auswahl (Conditional Logic)',
                        'Unterscheidung von Demo, Vollversion und Update',
                        'Korrekte Auslieferung je Produktlinie und Kundenstatus',
                        'Geführter Bestell- und Download-Prozess',
                    ],
                ],
                [
                    'title' => 'Schulungs- & Supportbereich',
                    'image' => '/images/references/mth-software/schulungsvideos.png',
                    'description' => 'Ein strukturierter Video- und Supportbereich entlastet den Telefon-Support: nach Produkt und Themenblock (Einführung, Import, Bankeinzug, Kommunikation, Dokumente) geordnete Tutorials, ergänzt um Handbücher und FAQ.',
                    'items' => [
                        'Video-Tutorials nach Produkt und Themenblock geordnet',
                        'Ergänzt um Handbücher (PDF) und FAQ',
                        'Reduziert wiederkehrende Support-Anfragen',
                        'Selbst pflegbar über Custom Post Types',
                    ],
                ],
            ],

            'technical_details' => [
                [
                    'icon' => 'code',
                    'title' => 'Custom-Plugin-Entwicklung',
                    'description' => 'Zwei maßgeschneiderte WordPress-Plugins (Roadmap und System-Status) — sauber gekapselt statt Theme-Wildwuchs, damit sie updatesicher und wartbar bleiben.',
                    'items' => [
                        'Eigene Post-Types und Felder',
                        'Gekapselte Plugin-Architektur',
                        'Update- und wartungssicher',
                        'Nahtlose Design-Integration',
                    ],
                ],
                [
                    'icon' => 'layers',
                    'title' => 'Struktur & Selbstpflege',
                    'description' => 'Custom Post Types und Meta-Box-Felder bilden die Inhaltsstruktur ab — MTH Software pflegt Videos, Roadmap und Status eigenständig, das Frontend bleibt konsistent.',
                    'items' => [
                        'Custom Post Types via Meta Box',
                        'Strukturierte Eingabemasken',
                        'Redaktionelle Autonomie',
                        'Konsistentes Frontend',
                    ],
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Betrieb & Wartung',
                    'description' => 'Ein dauerhafter Wartungsvertrag sichert Updates, Sicherheit und laufende Weiterentwicklung — die Plattform bleibt aktuell und stabil.',
                    'items' => [
                        'Laufende WordPress- und Plugin-Updates',
                        'Security und Backups',
                        'Kontinuierliche Weiterentwicklung',
                        'Fester Ansprechpartner',
                    ],
                ],
            ],

            'impact_results' => [
                'Vom statischen Web-Auftritt zur Plattform, die Vertrieb, Auslieferung und Support übernimmt',
                'Komplexe Software-Downloads werden über Conditional Logic korrekt und geführt ausgeliefert',
                'Self-Service-Support mit Video-Tutorials entlastet den Telefon-Support spürbar',
                'MTH Software pflegt Inhalte eigenständig — ohne Entwickler für jede Änderung',
                'Dauerhafter Wartungsvertrag sichert Sicherheit, Updates und Weiterentwicklung',
            ],

            'results' => [
                ['value' => '2', 'label' => 'Custom-Plugins (Roadmap & Status)'],
                ['value' => '16+', 'label' => 'Schulungsvideos im Supportbereich'],
                ['value' => '9.500+', 'label' => 'Vereine nutzen die Software'],
                ['value' => '30+', 'label' => 'Jahre Software-Erfahrung des Kunden'],
            ],

            'technologies' => [
                'WordPress',
                'PHP',
                'Custom Plugins',
                'Meta Box',
                'Custom Post Types',
                'Conditional Logic',
                'Responsive Design',
                'Wartungsvertrag',
            ],

            'timeline' => [
                [
                    'title' => 'Konzept & Design',
                    'description' => 'Analyse der Anforderungen eines Software-Anbieters und Gestaltung einer Plattform, die Vertrieb, Downloads und Support in einem stimmigen Auftritt vereint.',
                ],
                [
                    'title' => 'WordPress-Entwicklung',
                    'description' => 'Umsetzung des individuellen Themes und der Grundstruktur mit Custom Post Types und Meta-Box-Feldern als Fundament für die Selbstpflege.',
                ],
                [
                    'title' => 'Custom-Plugins',
                    'description' => 'Entwicklung der beiden Plugins für Roadmap und System-Status als gekapselte, wartbare Module mit eigenen Post-Types.',
                ],
                [
                    'title' => 'Bestellsystem & Support',
                    'description' => 'Aufbau des formularbasierten Bestellsystems mit Conditional Logic sowie des strukturierten Schulungs- und Supportbereichs.',
                ],
                [
                    'title' => 'Betrieb & Wartung',
                    'description' => 'Übergang in den laufenden Wartungsvertrag mit Updates, Security und kontinuierlicher Weiterentwicklung.',
                ],
            ],

            'cta' => [
                'title' => 'Sie bieten Software oder erklärungsbedürftige Produkte an?',
                'subtitle' => 'Ob Download-Auslieferung, Bestellstrecken mit Conditional Logic, Self-Service-Support oder maßgeschneiderte Plugins — wir bauen WordPress-Plattformen, die echte Aufgaben übernehmen und die Sie selbst pflegen können. Lassen Sie uns unverbindlich darüber sprechen.',
                'button_text' => 'Projekt besprechen',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function overviewEntry(int $position): array
    {
        return [
            'icon' => 'code',
            'number' => str_pad((string) $position, 2, '0', STR_PAD_LEFT),
            'title' => 'MTH Software — WordPress-Plattform mit Custom-Plugins',
            'client' => 'MTH Software GmbH & Co. KG',
            'detail_slug' => self::SLUG_DE,
            'tagline' => 'WordPress-Plattform für einen etablierten Anbieter von Vereinsverwaltungs-Software — mit Custom-Plugins für Roadmap und System-Status, einem formularbasierten Bestellsystem mit Conditional Logic und einem Schulungsvideo-Bereich. Alles über Custom Post Types selbst pflegbar, mit laufendem Wartungsvertrag.',
            'categories' => [
                'WordPress',
                'Custom-Plugins',
                'Vereinssoftware',
            ],
            'challenge' => [
                'title' => 'Die Ausgangssituation',
                'description' => 'Ein Software-Anbieter braucht mehr als eine Broschüren-Website: Demo-Downloads, Vollversionen und Updates je nach Produktlinie und Kundenstatus, ein entlasteter Support und Inhalte, die das Team selbst pflegen kann.',
                'items' => [
                    'Komplexe Download-Logik über zwei Produktlinien und mehrere Versionstypen',
                    'Support-Entlastung durch Self-Service',
                    'Redaktionelle Selbstpflege ohne Entwickler',
                    'Dauerhaft sicherer und gewarteter Betrieb',
                ],
            ],
            'solution' => [
                'title' => 'Die entwickelte Lösung',
                'description' => 'Eine maßgeschneiderte WordPress-Plattform mit zwei Custom-Plugins, einem formularbasierten Bestellsystem mit Conditional Logic und einem strukturierten Supportbereich — alles über Custom Post Types und Meta-Box-Felder selbst pflegbar.',
                'items' => [
                    'Custom-Plugins für Roadmap und System-Status',
                    'Bestellsystem mit Conditional Logic für Software-Downloads',
                    'Schulungs- & Supportbereich mit Video-Tutorials',
                    'Custom Post Types + Meta Box zur Selbststeuerung',
                    'Laufender Wartungsvertrag',
                ],
            ],
            'features' => [
                [
                    'title' => 'Custom-Plugins',
                    'items' => [
                        'Roadmap: geplante Funktionen transparent und selbst pflegbar',
                        'System-Status: Betriebsstatus und Wartungshinweise',
                    ],
                ],
                [
                    'title' => 'Bestellsystem & Support',
                    'items' => [
                        'Formularbasiertes Bestellsystem mit Conditional Logic',
                        'Schulungsvideos und Handbücher im Supportbereich',
                    ],
                ],
            ],
            'results' => [
                'Geführte Auslieferung komplexer Software-Downloads',
                'Spürbare Support-Entlastung durch Self-Service',
                'Redaktionelle Autonomie über Custom Post Types',
                'Dauerhafter Wartungsvertrag für sicheren Betrieb',
            ],
            'tech_stack' => [
                'CMS: WordPress',
                'Custom-Plugins: PHP (Roadmap & System-Status)',
                'CPTs & Felder: Meta Box',
                'Bestellsystem: formularbasiert · Conditional Logic',
                'Betrieb: laufender Wartungsvertrag',
            ],
        ];
    }
};
