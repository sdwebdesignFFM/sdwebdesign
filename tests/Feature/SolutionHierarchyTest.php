<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolutionHierarchyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create solutions overview page
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTIONS,
            'slug' => ['de' => 'loesungen-overview', 'en' => 'solutions-overview'],
            'title' => ['de' => 'Lösungen', 'en' => 'Solutions'],
            'is_active' => true,
        ]);
    }

    public function test_hub_page_accessible_via_hierarchical_url(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => true,
            'parent_id' => null,
            'content' => [
                'de' => ['hero' => ['title' => 'Websites']],
                'en' => ['hero' => ['title' => 'Websites']],
            ],
        ]);

        $response = $this->get('/loesungen/websites');
        $response->assertStatus(200);
        $response->assertSee('Websites');
    }

    public function test_detail_page_accessible_via_nested_url(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => true,
            'parent_id' => null,
        ]);

        $detailPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'slug' => ['de' => 'starter-website', 'en' => 'starter-website'],
            'title' => ['de' => 'Starter-Website', 'en' => 'Starter Website'],
            'is_active' => true,
            'parent_id' => $hubPage->id,
            'content' => [
                'de' => ['hero' => ['title' => 'Starter-Website']],
                'en' => ['hero' => ['title' => 'Starter Website']],
            ],
        ]);

        $response = $this->get('/loesungen/websites/starter-website');
        $response->assertStatus(200);
        $response->assertSee('Starter-Website');
    }

    public function test_invalid_hierarchy_returns_404(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => true,
            'parent_id' => null,
        ]);

        // Try to access a non-existent nested page
        $response = $this->get('/loesungen/websites/nonexistent');
        $response->assertStatus(404);
    }

    public function test_page_model_returns_correct_full_slug(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => true,
            'parent_id' => null,
        ]);

        $detailPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'slug' => ['de' => 'starter-website', 'en' => 'starter-website'],
            'title' => ['de' => 'Starter-Website', 'en' => 'Starter Website'],
            'is_active' => true,
            'parent_id' => $hubPage->id,
        ]);

        app()->setLocale('de');
        $this->assertEquals('websites/starter-website', $detailPage->full_slug);
    }

    public function test_page_model_returns_correct_ancestors(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => true,
            'parent_id' => null,
        ]);

        $detailPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'slug' => ['de' => 'starter-website', 'en' => 'starter-website'],
            'title' => ['de' => 'Starter-Website', 'en' => 'Starter Website'],
            'is_active' => true,
            'parent_id' => $hubPage->id,
        ]);

        $ancestors = $detailPage->ancestors();
        $this->assertCount(1, $ancestors);
        $this->assertEquals($hubPage->id, $ancestors->first()->id);
    }

    public function test_page_model_returns_correct_breadcrumbs(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => true,
            'parent_id' => null,
        ]);

        $detailPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'slug' => ['de' => 'starter-website', 'en' => 'starter-website'],
            'title' => ['de' => 'Starter-Website', 'en' => 'Starter Website'],
            'is_active' => true,
            'parent_id' => $hubPage->id,
        ]);

        app()->setLocale('de');
        $breadcrumbs = $detailPage->getBreadcrumbs();

        $this->assertCount(2, $breadcrumbs);
        $this->assertContains('Websites', $breadcrumbs);
        $this->assertContains('Starter-Website', $breadcrumbs);
    }

    public function test_guide_overview_page_accessible(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_GUIDE_OVERVIEW,
            'slug' => ['de' => 'ratgeber-overview', 'en' => 'guides-overview'],
            'title' => ['de' => 'Ratgeber', 'en' => 'Guides'],
            'is_active' => true,
            'content' => [
                'de' => ['hero' => ['title' => 'Ratgeber']],
                'en' => ['hero' => ['title' => 'Guides']],
            ],
        ]);

        $response = $this->get('/ratgeber');
        $response->assertStatus(200);
    }

    public function test_guide_page_accessible(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_GUIDE_OVERVIEW,
            'slug' => ['de' => 'ratgeber-overview', 'en' => 'guides-overview'],
            'title' => ['de' => 'Ratgeber', 'en' => 'Guides'],
            'is_active' => true,
        ]);

        Page::factory()->create([
            'type' => Page::TYPE_GUIDE,
            'slug' => ['de' => 'welche-website-ist-die-richtige', 'en' => 'which-website-is-right'],
            'title' => ['de' => 'Welche Website ist die richtige?', 'en' => 'Which website is right?'],
            'is_active' => true,
            'content' => [
                'de' => ['hero' => ['title' => 'Welche Website ist die richtige?']],
                'en' => ['hero' => ['title' => 'Which website is right?']],
            ],
        ]);

        $response = $this->get('/ratgeber/welche-website-ist-die-richtige');
        $response->assertStatus(200);
        $response->assertSee('Welche Website ist die richtige?');
    }

    public function test_inactive_page_returns_404(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => false,
            'parent_id' => null,
        ]);

        $response = $this->get('/loesungen/websites');
        $response->assertStatus(404);
    }

    public function test_english_routes_work_correctly(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'is_active' => true,
            'parent_id' => null,
            'content' => [
                'de' => ['hero' => ['title' => 'Websites DE']],
                'en' => ['hero' => ['title' => 'Websites EN']],
            ],
        ]);

        $response = $this->get('/en/solutions/websites');
        $response->assertStatus(200);
    }

    public function test_platforms_hub_page_displays_all_sections(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'plattformen', 'en' => 'platforms'],
            'title' => ['de' => 'Digitale Plattformen & Webanwendungen', 'en' => 'Digital Platforms & Web Applications'],
            'is_active' => true,
            'parent_id' => null,
            'content' => [
                'de' => [
                    'hero' => [
                        'icon' => 'layout-dashboard',
                        'badge' => 'Plattformen',
                        'subtitle' => 'Individuelle Systeme für Prozesse, Daten und Zusammenarbeit',
                    ],
                    'intro' => [
                        'text' => 'Standardsoftware stößt schnell an Grenzen.',
                    ],
                    'when_useful' => [
                        'title' => 'Wann eine digitale Plattform sinnvoll ist',
                        'intro' => 'Eine individuelle Plattform ist besonders dann sinnvoll, wenn:',
                        'conditions' => [
                            'Prozesse nicht mehr sauber mit Standardtools abbildbar sind',
                            'Mehrere Abteilungen zusammenarbeiten',
                        ],
                    ],
                    'use_case_categories' => [
                        [
                            'title' => 'Kunden- & Partnerportale',
                            'description' => 'Zentrale Plattformen für externe Nutzer',
                            'items' => ['Kundenbereiche', 'Partnerzugänge'],
                        ],
                    ],
                    'process' => [
                        'title' => 'Wie wir Plattformen entwickeln',
                        'intro' => 'Digitale Plattformen entstehen schrittweise.',
                        'steps' => [
                            ['title' => 'Analyse & Einordnung', 'description' => 'Verständnis für Prozesse'],
                            ['title' => 'Struktur & Architektur', 'description' => 'Klare Trennung'],
                        ],
                    ],
                    'capabilities' => [
                        'title' => 'Was digitale Plattformen leisten können',
                        'items' => ['Zentrale Abbildung von Geschäftslogik', 'Digitale Workflows'],
                    ],
                    'differentiation' => [
                        'title' => 'Abgrenzung: Plattform oder klassische Website?',
                        'text' => 'Nicht jedes Unternehmen braucht sofort eine Plattform.',
                    ],
                    'growth' => [
                        'title' => 'Schrittweise statt überdimensioniert',
                        'text' => 'Viele Plattform-Projekte starten klein.',
                    ],
                    'cta' => [
                        'title' => 'Projekt besprechen',
                        'subtitle' => 'In einem unverbindlichen Gespräch klären wir Ihre Anforderungen.',
                        'button_text' => 'Projekt besprechen',
                    ],
                ],
                'en' => [
                    'hero' => [
                        'icon' => 'layout-dashboard',
                        'badge' => 'Platforms',
                        'subtitle' => 'Custom systems for processes, data and collaboration',
                    ],
                ],
            ],
        ]);

        $response = $this->get('/loesungen/plattformen');

        $response->assertStatus(200);
        $response->assertSee('Digitale Plattformen & Webanwendungen');
        $response->assertSee('Individuelle Systeme für Prozesse, Daten und Zusammenarbeit');
        $response->assertSee('Wann eine digitale Plattform sinnvoll ist');
        $response->assertSee('Kunden- & Partnerportale');
        $response->assertSee('Wie wir Plattformen entwickeln');
        $response->assertSee('Was digitale Plattformen leisten können');
        $response->assertSee('Abgrenzung: Plattform oder klassische Website?');
        $response->assertSee('Schrittweise statt überdimensioniert');
        $response->assertSee('Projekt besprechen');
    }

    public function test_ecommerce_hub_page_displays_all_sections(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'e-commerce', 'en' => 'e-commerce'],
            'title' => ['de' => 'E-Commerce & Online-Shops', 'en' => 'E-Commerce & Online Shops'],
            'is_active' => true,
            'parent_id' => null,
            'content' => [
                'de' => [
                    'hero' => [
                        'icon' => 'shopping-cart',
                        'badge' => 'E-Commerce',
                        'subtitle' => 'Online-Shops, die heute verkaufen – und morgen mitwachsen',
                    ],
                    'intro' => [
                        'text' => 'Ein Online-Shop ist mehr als eine Produktliste.',
                    ],
                    'when_useful' => [
                        'title' => 'Wann eine individuelle Shop-Lösung sinnvoll ist',
                        'intro' => 'Nicht jeder Online-Shop ist gleich.',
                        'conditions' => [
                            'Produkte nicht nur präsentiert, sondern aktiv verkauft werden',
                            'Bestellungen strukturiert ablaufen sollen',
                        ],
                    ],
                    'cards_intro' => [
                        'title' => 'Unsere E-Commerce-Lösungen im Überblick',
                        'text' => 'Wir unterscheiden drei grundlegende Shop-Typen.',
                    ],
                    'use_case_categories' => [
                        [
                            'title' => 'Einfacher Online-Shop',
                            'description' => 'Übersichtlich, funktional, schnell startklar',
                            'items' => ['Kleinere Sortimente', 'Produktdarstellung & Warenkorb'],
                        ],
                        [
                            'title' => 'Erweiterbarer Online-Shop',
                            'description' => 'Für Wachstum und Automatisierung',
                            'items' => ['Wachsende Sortimente', 'Warenwirtschafts-Anbindung'],
                        ],
                        [
                            'title' => 'Individuelle Shop-Lösung',
                            'description' => 'Maßgeschneidert für komplexe Anforderungen',
                            'items' => ['B2B-Shops', 'Komplexe Integrationen'],
                        ],
                    ],
                    'capabilities' => [
                        'title' => 'Integration statt Insellösung',
                        'intro' => 'Ein Online-Shop sollte kein isoliertes System sein.',
                        'items' => ['Warenwirtschaft & Lagerverwaltung', 'Buchhaltung & Zahlungsanbieter'],
                    ],
                    'growth' => [
                        'title' => 'Schrittweise wachsen statt überdimensioniert starten',
                        'text' => 'Viele Shop-Projekte beginnen bewusst schlank.',
                    ],
                    'cta' => [
                        'title' => 'Projekt besprechen',
                        'subtitle' => 'Wir helfen Ihnen bei der Einordnung.',
                        'button_text' => 'Projekt besprechen',
                    ],
                ],
                'en' => [
                    'hero' => [
                        'icon' => 'shopping-cart',
                        'badge' => 'E-Commerce',
                        'subtitle' => 'Online shops that sell today and grow tomorrow',
                    ],
                ],
            ],
        ]);

        $response = $this->get('/loesungen/e-commerce');

        $response->assertStatus(200);
        $response->assertSee('E-Commerce & Online-Shops');
        $response->assertSee('Online-Shops, die heute verkaufen');
        $response->assertSee('Wann eine individuelle Shop-Lösung sinnvoll ist');
        $response->assertSee('Unsere E-Commerce-Lösungen im Überblick');
        $response->assertSee('Einfacher Online-Shop');
        $response->assertSee('Erweiterbarer Online-Shop');
        $response->assertSee('Individuelle Shop-Lösung');
        $response->assertSee('Integration statt Insellösung');
        $response->assertSee('Schrittweise wachsen');
        $response->assertSee('Projekt besprechen');
    }

    public function test_hub_page_displays_child_page_teasers(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'plattformen', 'en' => 'platforms'],
            'title' => ['de' => 'Digitale Plattformen', 'en' => 'Digital Platforms'],
            'is_active' => true,
            'parent_id' => null,
            'content' => [
                'de' => ['hero' => ['subtitle' => 'Plattformen Subtitle']],
                'en' => ['hero' => ['subtitle' => 'Platforms Subtitle']],
            ],
        ]);

        $childPage1 = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'slug' => ['de' => 'kundenportale', 'en' => 'customer-portals'],
            'title' => ['de' => 'Kundenportale', 'en' => 'Customer Portals'],
            'is_active' => true,
            'parent_id' => $hubPage->id,
            'sort_order' => 1,
            'content' => [
                'de' => ['hero' => ['tagline' => 'Portale Tagline DE']],
                'en' => ['hero' => ['tagline' => 'Portals Tagline EN']],
            ],
        ]);

        $childPage2 = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'slug' => ['de' => 'interne-tools', 'en' => 'internal-tools'],
            'title' => ['de' => 'Interne Tools', 'en' => 'Internal Tools'],
            'is_active' => true,
            'parent_id' => $hubPage->id,
            'sort_order' => 2,
            'content' => [
                'de' => ['hero' => ['tagline' => 'Tools Tagline DE']],
                'en' => ['hero' => ['tagline' => 'Tools Tagline EN']],
            ],
        ]);

        $response = $this->get('/loesungen/plattformen');

        $response->assertStatus(200);
        $response->assertSee('Digitale Plattformen');
        $response->assertSee('Kundenportale');
        $response->assertSee('Portale Tagline DE');
        $response->assertSee('Interne Tools');
        $response->assertSee('Tools Tagline DE');
    }

    public function test_detail_page_displays_all_content_sections(): void
    {
        $hubPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'plattformen', 'en' => 'platforms'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'is_active' => true,
            'parent_id' => null,
        ]);

        $detailPage = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'slug' => ['de' => 'kundenportale', 'en' => 'customer-portals'],
            'title' => ['de' => 'Kunden- & Partnerportale', 'en' => 'Customer & Partner Portals'],
            'is_active' => true,
            'parent_id' => $hubPage->id,
            'content' => [
                'de' => [
                    'hero' => [
                        'number' => '01',
                        'tagline' => 'Zentrale Plattformen',
                        'description' => 'Beschreibung hier',
                    ],
                    'when' => [
                        'title' => 'Wann sinnvoll',
                        'intro' => 'Ein Portal lohnt sich...',
                        'conditions' => ['Condition 1', 'Condition 2'],
                    ],
                    'features' => [
                        'title' => 'Typische Funktionen',
                        'items' => ['Feature 1', 'Feature 2'],
                    ],
                    'process' => [
                        'title' => 'Unser Vorgehen',
                        'steps' => [
                            ['title' => 'Schritt 1', 'description' => 'Beschreibung 1'],
                        ],
                    ],
                    'cta' => [
                        'title' => 'Projekt besprechen',
                        'button_text' => 'Kontakt aufnehmen',
                    ],
                ],
                'en' => [
                    'hero' => ['tagline' => 'Central Platforms'],
                ],
            ],
        ]);

        $response = $this->get('/loesungen/plattformen/kundenportale');

        $response->assertStatus(200);
        $response->assertSee('Kunden- & Partnerportale');
        $response->assertSee('Zentrale Plattformen');
        $response->assertSee('Wann sinnvoll');
        $response->assertSee('Typische Funktionen');
        $response->assertSee('Feature 1');
        $response->assertSee('Unser Vorgehen');
        $response->assertSee('Schritt 1');
        $response->assertSee('Kontakt aufnehmen');
    }

    public function test_solution_hub_renders_package_contents_when_set(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'gruenderpaket-frankfurt', 'en' => 'gruenderpaket-frankfurt'],
            'title' => ['de' => 'Gründerpaket Frankfurt', 'en' => 'Founder Package Frankfurt'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Gründerpaket Frankfurt'],
                    'package' => [
                        'headline' => 'Was Sie im Gründerpaket bekommen',
                        'intro' => 'Alles aus einer Hand für einen professionellen Start.',
                        'items' => [
                            ['name' => 'Individuelle Website', 'description' => 'Responsive, DSGVO-konform'],
                            ['name' => 'Logo & Corporate Identity', 'description' => 'Markenstart in 2 Wochen'],
                            ['name' => 'E-Mail & Domain Setup', 'description' => 'Mit eigener Domain'],
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->get('/loesungen/gruenderpaket-frankfurt');

        $response->assertStatus(200);
        $response->assertSee('Was Sie im Gründerpaket bekommen');
        $response->assertSee('Alles aus einer Hand für einen professionellen Start.');
        $response->assertSee('Individuelle Website');
        $response->assertSee('Logo &amp; Corporate Identity', false);
        $response->assertSee('E-Mail &amp; Domain Setup', false);
    }

    public function test_solution_hub_renders_pricing_and_timeline_when_set(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'gruenderpaket-frankfurt', 'en' => 'gruenderpaket-frankfurt'],
            'title' => ['de' => 'Gründerpaket Frankfurt', 'en' => 'Founder Package Frankfurt'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Gründerpaket Frankfurt'],
                    'pricing' => [
                        'label' => 'Gründerpaket ab 4.500 €',
                        'note' => 'Transparent kalkuliert',
                    ],
                    'timeline' => [
                        'label' => '4–6 Wochen bis Launch',
                        'note' => 'Von Briefing bis Go-Live',
                    ],
                ],
            ],
        ]);

        $response = $this->get('/loesungen/gruenderpaket-frankfurt');

        $response->assertStatus(200);
        $response->assertSee('Gründerpaket ab 4.500 €');
        $response->assertSee('Transparent kalkuliert');
        $response->assertSee('4–6 Wochen bis Launch');
        $response->assertSee('Von Briefing bis Go-Live');
    }

    public function test_solution_hub_hides_package_block_when_not_set(): void
    {
        // Standard solution hubs (e.g. /loesungen/websites) should not show
        // the Paket-Inhalt UI — it's scoped to bundle/package hubs only.
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'plain-hub', 'en' => 'plain-hub'],
            'title' => ['de' => 'Plain Hub', 'en' => 'Plain Hub'],
            'is_active' => true,
            'content' => [
                'de' => ['hero' => ['title' => 'Plain Hub']],
            ],
        ]);

        $response = $this->get('/loesungen/plain-hub');

        $response->assertStatus(200);
        // The pricing/timeline label microcopy must not appear on standard hubs
        $response->assertDontSee('uppercase tracking-wider text-muted-foreground mb-2">Preis<', false);
        $response->assertDontSee('uppercase tracking-wider text-muted-foreground mb-2">Zeitrahmen<', false);
    }
}
