<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class HubPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getIntroTab(),
            self::getPackageContentsTab(),
            self::getPricingTimelineTab(),
            self::getWhenUsefulTab(),
            self::getUseCaseCategoriesTab(),
            self::getCardsIntroTab(),
            self::getProcessTab(),
            self::getCapabilitiesTab(),
            self::getDifferentiationTab(),
            self::getGrowthTab(),
            self::getCtaTab(),
        ];
    }

    private static function getHeroTab(): Tab
    {
        return Tab::make('Hero')
            ->icon(Heroicon::OutlinedSparkles)
            ->schema([
                Section::make('Kopfbereich')
                    ->schema([
                        TextInput::make('content.hero.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. Websites'),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required(),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),

                        TextInput::make('content.hero.icon')
                            ->label('Icon')
                            ->placeholder('z.B. globe, code-bracket')
                            ->helperText('Icon fuer die Uebersicht'),
                    ]),
            ]);
    }

    private static function getIntroTab(): Tab
    {
        return Tab::make('Intro')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Einfuehrungstext')
                    ->schema([
                        Textarea::make('content.intro.text')
                            ->label('Einleitungstext')
                            ->rows(6)
                            ->helperText('Kurze Einfuehrung direkt unter dem Hero-Bereich'),
                    ]),
            ]);
    }

    private static function getCardsIntroTab(): Tab
    {
        return Tab::make('Kacheln-Intro')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                Section::make('Einfuehrung vor den Kacheln')
                    ->schema([
                        TextInput::make('content.cards_intro.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Welche Website passt zu Ihrem Unternehmen?'),

                        Textarea::make('content.cards_intro.text')
                            ->label('Text')
                            ->rows(4)
                            ->helperText('Wird direkt ueber den Loesungs-Kacheln angezeigt'),
                    ]),
            ]);
    }

    private static function getWhenUsefulTab(): Tab
    {
        return Tab::make('Wann sinnvoll')
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->schema([
                Section::make('Wann ist diese Loesung sinnvoll?')
                    ->description('Bedingungen und Beispiele')
                    ->schema([
                        TextInput::make('content.when_useful.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Wann eine digitale Plattform sinnvoll ist'),

                        Textarea::make('content.when_useful.intro')
                            ->label('Einleitungstext')
                            ->rows(2),

                        Repeater::make('content.when_useful.conditions')
                            ->label('Bedingungen')
                            ->simple(
                                TextInput::make('condition')
                                    ->placeholder('z.B. Prozesse nicht mehr mit Standardtools abbildbar')
                            )
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),

                        Textarea::make('content.when_useful.note')
                            ->label('Zusaetzliche Anmerkung')
                            ->rows(2)
                            ->helperText('Optionaler Hinweis unter den Bedingungen'),
                    ]),
            ]);
    }

    private static function getUseCaseCategoriesTab(): Tab
    {
        return Tab::make('Einsatzbereiche')
            ->icon(Heroicon::OutlinedRectangleGroup)
            ->schema([
                Section::make('Typische Einsatzbereiche')
                    ->description('Kategorisierte Anwendungsbeispiele')
                    ->schema([
                        Repeater::make('content.use_case_categories')
                            ->label('Kategorien')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Kategorie-Titel')
                                    ->placeholder('z.B. Kunden- & Partnerportale')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->rows(2)
                                    ->placeholder('z.B. Zentrale Plattformen für externe Nutzer'),

                                Repeater::make('items')
                                    ->label('Beispiele')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Kundenbereiche')
                                    )
                                    ->reorderable()
                                    ->defaultItems(0),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getProcessTab(): Tab
    {
        return Tab::make('Vorgehen')
            ->icon(Heroicon::OutlinedListBullet)
            ->schema([
                Section::make('Wie wir entwickeln')
                    ->description('Schritt-fuer-Schritt Prozess')
                    ->schema([
                        TextInput::make('content.process.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Wie wir Plattformen entwickeln'),

                        Textarea::make('content.process.intro')
                            ->label('Einleitungstext')
                            ->rows(2),

                        Repeater::make('content.process.steps')
                            ->label('Prozess-Schritte')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Schritt-Titel')
                                    ->placeholder('z.B. Analyse & Einordnung')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->placeholder('z.B. Verständnis für Prozesse, Nutzerrollen und bestehende Systeme.'),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getCapabilitiesTab(): Tab
    {
        return Tab::make('Faehigkeiten')
            ->icon(Heroicon::OutlinedCog6Tooth)
            ->schema([
                Section::make('Was diese Loesung leisten kann')
                    ->schema([
                        TextInput::make('content.capabilities.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Was digitale Plattformen leisten können'),

                        Textarea::make('content.capabilities.intro')
                            ->label('Einleitungstext')
                            ->rows(2),

                        Repeater::make('content.capabilities.items')
                            ->label('Faehigkeiten')
                            ->simple(
                                TextInput::make('item')
                                    ->placeholder('z.B. Zentrale Abbildung von Geschäftslogik')
                            )
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),

                        Textarea::make('content.capabilities.note')
                            ->label('Abschliessende Anmerkung')
                            ->rows(2),
                    ]),
            ]);
    }

    private static function getDifferentiationTab(): Tab
    {
        return Tab::make('Abgrenzung')
            ->icon(Heroicon::OutlinedArrowsRightLeft)
            ->schema([
                Section::make('Abgrenzung zu anderen Loesungen')
                    ->description('Verweis auf alternative Loesungen')
                    ->schema([
                        TextInput::make('content.differentiation.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Abgrenzung: Plattform oder klassische Website?'),

                        Textarea::make('content.differentiation.text')
                            ->label('Text')
                            ->rows(3),

                        TextInput::make('content.differentiation.link_text')
                            ->label('Link-Text')
                            ->placeholder('z.B. Unternehmenswebsites ansehen'),

                        TextInput::make('content.differentiation.link_slug')
                            ->label('Link-Slug')
                            ->placeholder('z.B. websites')
                            ->helperText('Slug der verlinkten Hub-Seite'),
                    ]),
            ]);
    }

    private static function getGrowthTab(): Tab
    {
        return Tab::make('Wachstum')
            ->icon(Heroicon::OutlinedArrowTrendingUp)
            ->schema([
                Section::make('Schrittweise wachsen')
                    ->description('Optionaler Abschnitt nach den Kacheln')
                    ->schema([
                        TextInput::make('content.growth.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Schrittweise wachsen – statt sich festzulegen'),

                        Textarea::make('content.growth.text')
                            ->label('Text')
                            ->rows(4),
                    ]),
            ]);
    }

    /**
     * Optional tab used on bundle/package hubs (e.g. Gründerpaket) —
     * renders a checklist of what's included in the offering. Empty on
     * standard solution hubs so nothing shows up there.
     */
    private static function getPackageContentsTab(): Tab
    {
        return Tab::make('Paket-Inhalt')
            ->icon(Heroicon::OutlinedCheckBadge)
            ->schema([
                Section::make('Was ist im Paket enthalten')
                    ->description('Optional — nur für Paket-/Bundle-Hubs (z.B. Gründerpaket). Checkliste der enthaltenen Leistungen. Leer lassen bei normalen Solution-Hubs.')
                    ->schema([
                        TextInput::make('content.package.headline')
                            ->label('Überschrift')
                            ->placeholder('z.B. Was Sie im Gründerpaket bekommen'),

                        Textarea::make('content.package.intro')
                            ->label('Einleitungstext')
                            ->rows(2)
                            ->placeholder('z.B. Alle Leistungen, die Sie für einen professionellen Start brauchen — aus einer Hand, mit einem Ansprechpartner.'),

                        Repeater::make('content.package.items')
                            ->label('Enthaltene Leistungen')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Leistung')
                                    ->required()
                                    ->placeholder('z.B. Individuelle Website (5 Seiten)'),
                                Textarea::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->rows(2)
                                    ->placeholder('z.B. Responsive, DSGVO-konform, Impressum & Datenschutz inklusive'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Leistung hinzufügen'),
                    ]),
            ]);
    }

    /**
     * Optional tab — renders a price range + timeline signal block. For
     * founder/package pages this is the #1 bounce-reduction block: shows
     * buyers what they're in for before they have to contact anyone.
     */
    private static function getPricingTimelineTab(): Tab
    {
        return Tab::make('Preis & Timeline')
            ->icon(Heroicon::OutlinedCurrencyEuro)
            ->schema([
                Section::make('Preissignal')
                    ->description('Optional — ein sichtbarer Preisanker reduziert Bounce-Rate erheblich. Nutze einen Rahmen ("ab X €") oder eine Range, keine Fixpreise.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.pricing.label')
                            ->label('Preis-Anker')
                            ->placeholder('z.B. Gründerpaket ab 4.500 €'),
                        TextInput::make('content.pricing.note')
                            ->label('Preis-Zusatz')
                            ->placeholder('z.B. Transparent kalkuliert — Abhängig von Umfang & Anforderungen'),
                    ]),

                Section::make('Timeline')
                    ->description('Wie lange dauert so ein Projekt? Gründer hassen Unsicherheit — eine klare Zeitspanne senkt den Einstiegswiderstand.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.timeline.label')
                            ->label('Projektdauer')
                            ->placeholder('z.B. 4–6 Wochen bis Launch'),
                        TextInput::make('content.timeline.note')
                            ->label('Zusatz')
                            ->placeholder('z.B. Von Briefing bis Go-Live'),
                    ]),
            ]);
    }

    private static function getCtaTab(): Tab
    {
        return Tab::make('CTA')
            ->icon(Heroicon::OutlinedRocketLaunch)
            ->schema([
                Section::make('Vergleichs-Link')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.comparison.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Welche Website ist die richtige?')
                            ->columnSpanFull(),

                        TextInput::make('content.comparison.link_text')
                            ->label('Link-Text')
                            ->placeholder('z.B. Zum Vergleich'),

                        TextInput::make('content.related_guide_slug')
                            ->label('Verknuepfter Ratgeber (Slug)')
                            ->placeholder('z.B. welche-website-ist-die-richtige')
                            ->helperText('Slug der Ratgeber-Seite fuer den Vergleichs-CTA'),
                    ]),

                Section::make('Call-to-Action')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.cta.title')
                            ->label('Ueberschrift')
                            ->columnSpanFull(),

                        Textarea::make('content.cta.subtitle')
                            ->label('Untertitel')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('content.cta.button_text')
                            ->label('Button Text'),

                        TextInput::make('content.cta.button_link')
                            ->label('Button Link'),
                    ]),
            ]);
    }
}
