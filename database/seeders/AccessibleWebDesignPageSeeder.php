<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class AccessibleWebDesignPageSeeder extends Seeder
{
    /**
     * Seed the "Barrierefreies Webdesign" (BFSG/WCAG) solution page as a child
     * of the "websites" hub.
     *
     * Background: the legacy WordPress site ranked with
     * /loesungen/barrierefreies-webdesign, and the BFSG keyword cluster
     * (~1,300 searches/month, low competition) has no landing page on the
     * current site. Run on production via:
     *
     *   php artisan db:seed --class=AccessibleWebDesignPageSeeder
     *
     * Idempotent: keyed on the German slug, so re-running updates the page
     * in place (and overwrites manual edits made in Filament).
     */
    public function run(): void
    {
        $hub = Page::query()
            ->where('type', Page::TYPE_SOLUTION_HUB)
            ->where('slug->de', 'websites')
            ->first();

        if (! $hub) {
            $this->command?->warn('Websites hub page not found - skipping AccessibleWebDesignPageSeeder.');

            return;
        }

        $page = Page::query()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->where('slug->de', 'barrierefreies-webdesign')
            ->first() ?? new Page;

        $page->fill([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $hub->id,
            'is_active' => true,
            'sort_order' => 4,
            'slug' => [
                'de' => 'barrierefreies-webdesign',
                'en' => 'accessible-web-design',
            ],
            'title' => [
                'de' => 'Barrierefreies Webdesign',
                'en' => 'Accessible Web Design',
            ],
            'meta_title' => [
                'de' => 'Barrierefreies Webdesign nach BFSG & WCAG 2.1',
                'en' => 'Accessible Web Design (WCAG 2.1 & EAA Compliance)',
            ],
            'meta_description' => [
                'de' => 'Barrierefreies Webdesign aus Frankfurt: Wir machen Ihre Website barrierefrei nach WCAG 2.1 und BFSG – vom Barriere-Check über die Umsetzung bis zur Erklärung zur Barrierefreiheit.',
                'en' => 'Accessible web design from Frankfurt: we make your website WCAG 2.1 compliant and ready for the European Accessibility Act – from audit to implementation.',
            ],
            'content' => [
                'de' => $this->germanContent(),
                'en' => $this->englishContent(),
            ],
        ]);

        $page->save();
    }

    /**
     * @return array<string, mixed>
     */
    private function germanContent(): array
    {
        return [
            'hero' => [
                'icon' => 'shield',
                'tagline' => 'Websites, die alle erreichen – und das BFSG erfüllen',
                'description' => 'Seit dem 28. Juni 2025 gilt das Barrierefreiheitsstärkungsgesetz (BFSG): Viele Websites mit Verbraucherkontakt müssen barrierefrei sein. Wir prüfen, was für Sie gilt, und setzen Barrierefreiheit technisch sauber um – ohne Overlay-Tricks, direkt im Code.',
            ],
            'ideal_for' => 'Unternehmen mit Online-Shop, Terminbuchung oder B2C-Angebot, die unter das BFSG fallen – und alle, die keine Kunden ausschließen wollen.',
            'when' => [
                'title' => 'Wann Barrierefreiheit Pflicht ist',
                'intro' => 'Das BFSG betrifft digitale Dienstleistungen für Verbraucher. Ob Ihre Website darunter fällt, hängt von Angebot und Unternehmensgröße ab:',
                'conditions' => [
                    'Sie betreiben einen Online-Shop oder verkaufen Dienstleistungen online an Verbraucher',
                    'Ihre Website bietet Terminbuchung, Vertragsabschluss oder Kundenkonten für Privatkunden',
                    'Ihr Unternehmen hat mehr als 10 Mitarbeitende oder über 2 Mio. € Jahresumsatz (Kleinstunternehmen sind bei Dienstleistungen ausgenommen)',
                    'Sie arbeiten für öffentliche Auftraggeber, die Barrierefreiheit vertraglich voraussetzen',
                ],
                'note' => 'Auch ohne gesetzliche Pflicht lohnt sich Barrierefreiheit: Rund jeder zehnte Mensch in Deutschland lebt mit einer Behinderung – dazu kommen ältere Nutzer, Menschen mit temporären Einschränkungen und alle, die Ihre Website mobil in der Sonne bedienen.',
            ],
            'features' => [
                'title' => 'Was eine barrierefreie Website umfasst',
                'intro' => 'Grundlage ist die WCAG 2.1 (Stufe AA) – der Standard, auf den auch das BFSG über die EN 301 549 verweist:',
                'items' => [
                    'Semantisches HTML mit korrekter Überschriften- und Landmarkenstruktur',
                    'Vollständige Bedienbarkeit per Tastatur – ohne Maus',
                    'Ausreichende Farbkontraste für Text und Bedienelemente',
                    'Alternativtexte für Bilder und aussagekräftige Linktexte',
                    'Barrierefreie Formulare mit klaren Beschriftungen und Fehlermeldungen',
                    'Screenreader-Unterstützung durch korrekte ARIA-Attribute',
                    'Zoombare Inhalte bis 200 % ohne Funktionsverlust',
                    'Verständliche Sprache und nachvollziehbare Navigation',
                ],
            ],
            'process' => [
                'title' => 'Wie wir Barrierefreiheit umsetzen',
                'steps' => [
                    [
                        'title' => 'Barriere-Check',
                        'description' => 'Wir prüfen Ihre Website gegen die WCAG 2.1 AA – automatisiert und manuell mit Tastatur und Screenreader. Sie erhalten einen priorisierten Maßnahmenkatalog mit Aufwandsschätzung.',
                    ],
                    [
                        'title' => 'Priorisierung & Entscheidung',
                        'description' => 'Nicht jede Barriere ist gleich kritisch. Wir zeigen, was rechtlich notwendig, was wirtschaftlich sinnvoll und was bei einer alten Website-Basis ein Fall für den Relaunch ist.',
                    ],
                    [
                        'title' => 'Umsetzung im Code',
                        'description' => 'Wir beheben Barrieren direkt im Quellcode – Struktur, Kontraste, Formulare, ARIA. Keine Overlay-Widgets, die Barrieren nur überdecken und rechtlich nicht ausreichen.',
                    ],
                    [
                        'title' => 'Test & Erklärung zur Barrierefreiheit',
                        'description' => 'Nach dem Test mit echten Hilfstechnologien erstellen wir die Erklärung zur Barrierefreiheit für Ihre Website und dokumentieren den Konformitätsstand.',
                    ],
                ],
            ],
            'benefits' => [
                'Rechtssicherheit gegenüber BFSG und Marktüberwachung',
                'Größere Zielgruppe: Sie schließen keine Kunden mehr aus',
                'Bessere Suchmaschinen-Rankings durch saubere semantische Struktur',
                'Bessere Bedienbarkeit für alle Nutzer – auch mobil und bei schlechtem Licht',
            ],
            'scenarios' => [
                'Bestehende Website auf BFSG-Konformität prüfen und nachrüsten',
                'Neue Website von Anfang an barrierefrei entwickeln',
                'Online-Shop barrierefrei machen (Checkout, Formulare, Produktseiten)',
                'Erklärung zur Barrierefreiheit erstellen und aktuell halten',
            ],
            'limitations' => [
                'title' => 'Wo wir ehrlich sind',
                'note' => 'Barrierefreiheit ist ein Zustand, den man pflegt – kein Siegel, das man einmal kauft.',
                'items' => [
                    'Jede neue Seite und jedes neue Feature kann neue Barrieren einführen – Barrierefreiheit gehört in den laufenden Betrieb, nicht nur ins Projekt',
                    'Eingebettete Drittinhalte (Karten, Videos, Buchungstools) können wir nur begrenzt barrierefrei machen',
                    'Bei alten Baukasten-Websites ist ein barrierefreier Neuaufbau oft wirtschaftlicher als das Nachrüsten',
                ],
            ],
            'differentiation' => [
                'title' => 'Kein Overlay, kein Plugin – echter barrierefreier Code',
                'text' => "Accessibility-Overlays versprechen Barrierefreiheit per Skript-Einbindung. In der Praxis überdecken sie Barrieren, statt sie zu beheben – und erfüllen die Anforderungen des BFSG nicht.\nWir arbeiten anders: Barrierefreiheit entsteht bei uns im HTML, CSS und JavaScript selbst. Das ist die einzige Umsetzung, die dauerhaft funktioniert – für Nutzer wie für Prüfstellen.",
                'link_slug' => 'individuelle-website',
                'link_text' => 'Mehr zur individuellen Webentwicklung',
            ],
            'next_steps' => [
                'title' => 'Passende nächste Schritte',
                'text' => 'Barrierefreiheit denken wir am besten von Anfang an mit – oder verankern sie dauerhaft im Betrieb Ihrer Website.',
                'links' => [
                    ['slug' => 'starter-website', 'label' => 'Neue Website barrierefrei starten: Starter-Website'],
                    ['slug' => 'betrieb-hosting-wartung', 'label' => 'Dauerhaft barrierefrei bleiben: Betrieb & Wartung'],
                ],
            ],
            'cta' => [
                'text' => 'Unsicher, ob Ihre Website unter das BFSG fällt? Ich prüfe das kostenlos und sage Ihnen ehrlich, welcher Aufwand realistisch ist – und welcher nicht nötig ist.',
                'button_text' => 'Barriere-Check anfragen',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function englishContent(): array
    {
        return [
            'hero' => [
                'icon' => 'shield',
                'tagline' => 'Websites that everyone can use – and that meet the EAA',
                'description' => 'Since 28 June 2025, the European Accessibility Act (implemented in Germany as the BFSG) requires many consumer-facing websites to be accessible. We assess what applies to you and implement accessibility properly in the code – no overlay shortcuts.',
            ],
            'ideal_for' => 'Businesses with an online shop, booking system or B2C services that fall under the accessibility act – and everyone who does not want to exclude customers.',
            'when' => [
                'title' => 'When accessibility is mandatory',
                'intro' => 'The act covers digital services offered to consumers. Whether your website is affected depends on your offering and company size:',
                'conditions' => [
                    'You run an online shop or sell services online to consumers',
                    'Your website offers booking, contract conclusion or customer accounts for private customers',
                    'Your company has more than 10 employees or over €2 million annual turnover (micro-enterprises are exempt for services)',
                    'You work for public-sector clients that require accessibility contractually',
                ],
                'note' => 'Accessibility pays off even without a legal obligation: roughly one in ten people in Germany lives with a disability – plus older users, people with temporary impairments and everyone using your site on a phone in bright sunlight.',
            ],
            'features' => [
                'title' => 'What an accessible website includes',
                'intro' => 'The basis is WCAG 2.1 (level AA) – the standard the law references via EN 301 549:',
                'items' => [
                    'Semantic HTML with a correct heading and landmark structure',
                    'Full keyboard operability – no mouse required',
                    'Sufficient colour contrast for text and controls',
                    'Alternative texts for images and meaningful link labels',
                    'Accessible forms with clear labels and error messages',
                    'Screen reader support through correct ARIA attributes',
                    'Content that zooms to 200% without loss of function',
                    'Clear language and a comprehensible navigation',
                ],
            ],
            'process' => [
                'title' => 'How we implement accessibility',
                'steps' => [
                    [
                        'title' => 'Accessibility audit',
                        'description' => 'We test your website against WCAG 2.1 AA – automated and manually with keyboard and screen reader. You receive a prioritised list of issues with effort estimates.',
                    ],
                    [
                        'title' => 'Prioritisation & decision',
                        'description' => 'Not every barrier is equally critical. We show what is legally required, what makes economic sense, and when a rebuild beats retrofitting an old website.',
                    ],
                    [
                        'title' => 'Implementation in code',
                        'description' => 'We fix barriers directly in the source code – structure, contrast, forms, ARIA. No overlay widgets that merely mask barriers and do not satisfy the law.',
                    ],
                    [
                        'title' => 'Testing & accessibility statement',
                        'description' => 'After testing with real assistive technology, we produce the accessibility statement for your website and document its conformance status.',
                    ],
                ],
            ],
            'benefits' => [
                'Legal certainty regarding the accessibility act and market surveillance',
                'A larger audience: you stop excluding customers',
                'Better search rankings thanks to clean semantic structure',
                'Better usability for all users – on mobile and in poor lighting too',
            ],
            'scenarios' => [
                'Audit an existing website for compliance and retrofit it',
                'Build a new website accessible from day one',
                'Make an online shop accessible (checkout, forms, product pages)',
                'Create and maintain the accessibility statement',
            ],
            'limitations' => [
                'title' => 'Where we are honest',
                'note' => 'Accessibility is a state you maintain – not a badge you buy once.',
                'items' => [
                    'Every new page and feature can introduce new barriers – accessibility belongs in ongoing operations, not just in the project',
                    'Embedded third-party content (maps, videos, booking tools) can only be made accessible to a limited extent',
                    'For old website-builder sites, an accessible rebuild is often more economical than retrofitting',
                ],
            ],
            'differentiation' => [
                'title' => 'No overlay, no plugin – genuinely accessible code',
                'text' => "Accessibility overlays promise compliance via a single script tag. In practice they mask barriers instead of removing them – and do not meet the legal requirements.\nWe work differently: accessibility is built into the HTML, CSS and JavaScript itself. That is the only implementation that lasts – for users and for auditors alike.",
                'link_slug' => 'individuelle-website',
                'link_text' => 'More about custom web development',
            ],
            'next_steps' => [
                'title' => 'Suitable next steps',
                'text' => 'Accessibility works best when considered from the start – or anchored permanently in the operation of your website.',
                'links' => [
                    ['slug' => 'starter-website', 'label' => 'Start a new website accessible from day one: Starter Website'],
                    ['slug' => 'betrieb-hosting-wartung', 'label' => 'Stay accessible over time: Hosting & Maintenance'],
                ],
            ],
            'cta' => [
                'text' => 'Not sure whether your website falls under the accessibility act? I will check free of charge and tell you honestly which effort is realistic – and which is unnecessary.',
                'button_text' => 'Request an accessibility check',
            ],
        ];
    }
}
