<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class HomePageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getProblemTab(),
            self::getSolutionsTab(),
            self::getPrinciplesTab(),
            self::getWhyUsTab(),
            self::getProcessTab(),
            self::getCtaTab(),
        ];
    }

    private static function getHeroTab(): Tab
    {
        return Tab::make('Hero')
            ->icon(Heroicon::OutlinedSparkles)
            ->schema([
                Section::make('Hauptinhalt')
                    ->schema([
                        TextInput::make('content.hero.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. System- & Lösungsplattform')
                            ->maxLength(50),

                        TextInput::make('content.hero.title')
                            ->label('Headline (H1)')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3)
                            ->maxLength(500),

                        Repeater::make('content.hero.tags')
                            ->label('Tags')
                            ->simple(
                                TextInput::make('tag')
                                    ->placeholder('z.B. Architektur')
                            )
                            ->reorderable()
                            ->defaultItems(0)
                            ->maxItems(6),
                    ]),

                Section::make('Call-to-Action Buttons')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.hero.cta_primary_text')
                            ->label('Primaerer Button Text')
                            ->placeholder('z.B. Projekt besprechen'),

                        TextInput::make('content.hero.cta_secondary_text')
                            ->label('Sekundaerer Button Text')
                            ->placeholder('z.B. Loesungen entdecken'),
                    ]),

                Section::make('Technische Visualisierung (rechte Seite)')
                    ->description('Die 4 Layer-Boxen auf der rechten Seite')
                    ->schema([
                        Repeater::make('content.hero.layers')
                            ->label('Layer')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. globe, code, layers, database'),

                                TextInput::make('label')
                                    ->label('Label')
                                    ->placeholder('z.B. Frontend Layer')
                                    ->required(),

                                TextInput::make('desc')
                                    ->label('Beschreibung')
                                    ->placeholder('z.B. React, TypeScript'),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->defaultItems(0)
                            ->maxItems(4),
                    ]),
            ]);
    }

    private static function getProblemTab(): Tab
    {
        return Tab::make('Problem')
            ->icon(Heroicon::OutlinedExclamationTriangle)
            ->schema([
                Section::make('Problem-Sektion')
                    ->description('Zeigt die typischen Probleme der Zielgruppe')
                    ->schema([
                        TextInput::make('content.problem.title')
                            ->label('Ueberschrift')
                            ->required()
                            ->maxLength(255),

                        Repeater::make('content.problem.items')
                            ->label('Typische Ausgangssituation')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Text')
                                    ->placeholder('z.B. 6-10 verschiedene Tools')
                                    ->required(),

                                TextInput::make('value')
                                    ->label('Wert')
                                    ->placeholder('z.B. 8, ++, ∞')
                                    ->required(),
                            ])
                            ->addActionLabel('Problem hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->maxItems(6),

                        Repeater::make('content.problem.results')
                            ->label('Ergebnisse (rote Box)')
                            ->simple(
                                TextInput::make('result')
                                    ->placeholder('z.B. Medienbrueche & Doppelerfassung')
                            )
                            ->addActionLabel('Ergebnis hinzufuegen')
                            ->reorderable()
                            ->maxItems(6),

                        Textarea::make('content.problem.approach')
                            ->label('Unser Ansatz (Box unten)')
                            ->rows(2)
                            ->placeholder('z.B. Genau hier setzen wir an...'),
                    ]),
            ]);
    }

    private static function getSolutionsTab(): Tab
    {
        return Tab::make('Loesungen')
            ->icon(Heroicon::OutlinedRectangleGroup)
            ->schema([
                Section::make('Loesungen-Uebersicht')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('content.solutions.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. Loesungsuebersicht'),

                        TextInput::make('content.solutions.title')
                            ->label('Ueberschrift')
                            ->required(),

                        Textarea::make('content.solutions.subtitle')
                            ->label('Untertitel')
                            ->rows(3),
                    ]),

                Section::make('Loesungs-Akkordeons')
                    ->columnSpanFull()
                    ->description('Die 4 aufklappbaren Loesungskarten')
                    ->schema([
                        Repeater::make('content.solutions.accordions')
                            ->label('Akkordeons')
                            ->schema([
                                TextInput::make('number')
                                    ->label('Nummer')
                                    ->placeholder('01'),

                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. globe, layout-dashboard, shopping-cart'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                TextInput::make('subtitle')
                                    ->label('Untertitel'),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(3),

                                Repeater::make('suitable_for')
                                    ->label('Typisch geeignet fuer')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Unternehmenswebsites')
                                    )
                                    ->reorderable()
                                    ->defaultItems(0)
                                    ->maxItems(5),

                                Repeater::make('character')
                                    ->label('Charakter')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Klarer Einstieg')
                                    )
                                    ->reorderable()
                                    ->defaultItems(0)
                                    ->maxItems(5),

                                TextInput::make('link')
                                    ->label('Link')
                                    ->placeholder('/loesungen/websites'),
                            ])
                            ->addActionLabel('Akkordeon hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(6),
                    ]),

                Section::make('Wachstum & Sichtbarkeit')
                    ->columnSpanFull()
                    ->description('SEO/SEA Block')
                    ->schema([
                        TextInput::make('content.solutions.growth_title')
                            ->label('Ueberschrift')
                            ->placeholder('Wachstum & Sichtbarkeit'),

                        Textarea::make('content.solutions.growth_text')
                            ->label('Text')
                            ->rows(2),
                    ]),

                Section::make('Microcopy CTA')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('content.solutions.microcopy')
                            ->label('Text')
                            ->rows(2)
                            ->placeholder('Unsicher, welcher Einstieg sinnvoll ist?'),

                        TextInput::make('content.solutions.microcopy_button')
                            ->label('Button-Text')
                            ->placeholder('Projekt besprechen'),
                    ]),
            ]);
    }

    private static function getPrinciplesTab(): Tab
    {
        return Tab::make('Prinzipien')
            ->icon(Heroicon::OutlinedLightBulb)
            ->schema([
                Section::make('Technische Prinzipien')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('content.principles.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. TECHNISCHE PRINZIPIEN'),

                        TextInput::make('content.principles.title')
                            ->label('Ueberschrift')
                            ->required(),

                        Textarea::make('content.principles.subtitle')
                            ->label('Untertitel')
                            ->rows(2)
                            ->placeholder('z.B. Technische Entscheidungen bestimmen...'),

                        Repeater::make('content.principles.items')
                            ->label('Prinzipien')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. layers, git-branch'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Prinzip hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(4),
                    ]),

                Section::make('Tech Stack')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('content.principles.tech_stack')
                            ->label('Technologie-Kategorien')
                            ->schema([
                                TextInput::make('category')
                                    ->label('Kategorie')
                                    ->placeholder('z.B. Frontend')
                                    ->required(),

                                Repeater::make('items')
                                    ->label('Technologien')
                                    ->simple(
                                        TextInput::make('name')
                                            ->placeholder('z.B. React')
                                    )
                                    ->reorderable()
                                    ->defaultItems(0),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['category'] ?? null)
                            ->maxItems(4),

                        Textarea::make('content.principles.additional_tools')
                            ->label('Weitere Technologien & Tools')
                            ->rows(2),
                    ]),
            ]);
    }

    private static function getWhyUsTab(): Tab
    {
        return Tab::make('Warum wir')
            ->icon(Heroicon::OutlinedCheckBadge)
            ->schema([
                Section::make('Warum sdWebdesign')
                    ->schema([
                        TextInput::make('content.why_us.title')
                            ->label('Ueberschrift')
                            ->required(),

                        Repeater::make('content.why_us.items')
                            ->label('Argumente')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. code, shield, clock'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Argument hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(4),

                        Textarea::make('content.why_us.promise')
                            ->label('Unser Versprechen (Box)')
                            ->rows(3),
                    ]),
            ]);
    }

    private static function getProcessTab(): Tab
    {
        return Tab::make('Prozess')
            ->icon(Heroicon::OutlinedArrowPath)
            ->schema([
                Section::make('Arbeitsablauf')
                    ->schema([
                        TextInput::make('content.process.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. So arbeiten wir'),

                        TextInput::make('content.process.title')
                            ->label('Ueberschrift')
                            ->required(),

                        Textarea::make('content.process.subtitle')
                            ->label('Untertitel')
                            ->rows(2),

                        Repeater::make('content.process.steps')
                            ->label('Prozess-Schritte')
                            ->schema([
                                TextInput::make('number')
                                    ->label('Schritt-Nummer')
                                    ->placeholder('01')
                                    ->maxLength(2),

                                TextInput::make('icon')
                                    ->label('Icon (optional)')
                                    ->placeholder('z.B. message-circle'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),

                                Repeater::make('details')
                                    ->label('Details (Stichpunkte)')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Analyse bestehender Systeme')
                                    )
                                    ->reorderable()
                                    ->defaultItems(0)
                                    ->maxItems(5),

                                TextInput::make('goal')
                                    ->label('Ziel (kursiv)')
                                    ->placeholder('z.B. Klarheit ueber Anforderungen'),
                            ])
                            ->addActionLabel('Schritt hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(6),
                    ]),

                Section::make('Fazit')
                    ->schema([
                        TextInput::make('content.process.conclusion.title')
                            ->label('Fazit Titel'),

                        Textarea::make('content.process.conclusion.text')
                            ->label('Fazit Text')
                            ->rows(2),
                    ]),
            ]);
    }

    private static function getCtaTab(): Tab
    {
        return Tab::make('CTA')
            ->icon(Heroicon::OutlinedRocketLaunch)
            ->schema([
                Section::make('Call-to-Action Sektion')
                    ->description('Abschliessender Aufruf zur Kontaktaufnahme')
                    ->schema([
                        TextInput::make('content.cta.title')
                            ->label('Ueberschrift')
                            ->required(),

                        Textarea::make('content.cta.subtitle')
                            ->label('Untertitel')
                            ->rows(2),

                        TextInput::make('content.cta.button_text')
                            ->label('Button Text')
                            ->placeholder('z.B. Projekt besprechen'),
                    ]),
            ]);
    }
}
