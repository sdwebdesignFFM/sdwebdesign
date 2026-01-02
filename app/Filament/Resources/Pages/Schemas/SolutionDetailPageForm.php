<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class SolutionDetailPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getWhenTab(),
            self::getFeaturesTab(),
            self::getScenariosTab(),
            self::getDifferentiationTab(),
            self::getIntegrationTab(),
            self::getGrowthTab(),
            self::getProcessTab(),
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
                        TextInput::make('content.hero.number')
                            ->label('Nummer')
                            ->placeholder('z.B. 01'),

                        TextInput::make('content.hero.tagline')
                            ->label('Tagline')
                            ->placeholder('z.B. Zentrale Plattformen für strukturierte Zusammenarbeit'),

                        Textarea::make('content.hero.description')
                            ->label('Beschreibung')
                            ->rows(4)
                            ->helperText('Ausführliche Einleitung unter der Tagline'),
                    ]),
            ]);
    }

    private static function getWhenTab(): Tab
    {
        return Tab::make('Wann sinnvoll')
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->schema([
                Section::make('Wann ist diese Lösung sinnvoll?')
                    ->schema([
                        TextInput::make('content.when.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Wann ein Kunden- oder Partnerportal sinnvoll ist'),

                        Textarea::make('content.when.intro')
                            ->label('Einleitungstext')
                            ->rows(2),

                        Repeater::make('content.when.conditions')
                            ->label('Bedingungen')
                            ->simple(
                                TextInput::make('condition')
                                    ->placeholder('z.B. regelmäßig Informationen mit Kunden geteilt werden')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Textarea::make('content.when.note')
                            ->label('Zusätzliche Anmerkung')
                            ->rows(2)
                            ->helperText('Optionaler Hinweis unter den Bedingungen'),
                    ]),
            ]);
    }

    private static function getFeaturesTab(): Tab
    {
        return Tab::make('Funktionen')
            ->icon(Heroicon::OutlinedRectangleGroup)
            ->schema([
                Section::make('Typische Funktionen')
                    ->schema([
                        TextInput::make('content.features.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Typische Funktionen eines Portals'),

                        Textarea::make('content.features.intro')
                            ->label('Einleitungstext')
                            ->rows(2),

                        Repeater::make('content.features.items')
                            ->label('Funktionen')
                            ->simple(
                                TextInput::make('item')
                                    ->placeholder('z.B. Benutzerkonten & Login-Bereiche')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Textarea::make('content.features.note')
                            ->label('Abschließende Anmerkung')
                            ->rows(2),
                    ]),
            ]);
    }

    private static function getScenariosTab(): Tab
    {
        return Tab::make('Szenarien')
            ->icon(Heroicon::OutlinedLightBulb)
            ->schema([
                Section::make('Typische Einsatzszenarien')
                    ->schema([
                        Repeater::make('content.scenario_categories')
                            ->label('Szenario-Kategorien')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Kategorie-Titel')
                                    ->placeholder('z.B. Kundenportale')
                                    ->required(),

                                Repeater::make('items')
                                    ->label('Beispiele')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Projektstatus & Fortschritt')
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

    private static function getDifferentiationTab(): Tab
    {
        return Tab::make('Abgrenzung')
            ->icon(Heroicon::OutlinedArrowsRightLeft)
            ->schema([
                Section::make('Abgrenzung zu anderen Lösungen')
                    ->schema([
                        TextInput::make('content.differentiation.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Abgrenzung: Portal vs. Website'),

                        Textarea::make('content.differentiation.text')
                            ->label('Text')
                            ->rows(4),

                        TextInput::make('content.differentiation.link_text')
                            ->label('Link-Text')
                            ->placeholder('z.B. Unternehmenswebsites ansehen'),

                        TextInput::make('content.differentiation.link_slug')
                            ->label('Link-Slug')
                            ->placeholder('z.B. websites')
                            ->helperText('Slug der verlinkten Seite'),
                    ]),
            ]);
    }

    private static function getIntegrationTab(): Tab
    {
        return Tab::make('Integration')
            ->icon(Heroicon::OutlinedLink)
            ->schema([
                Section::make('Integration in bestehende Systeme')
                    ->schema([
                        TextInput::make('content.integration.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Integration in bestehende Systeme'),

                        Textarea::make('content.integration.intro')
                            ->label('Einleitungstext')
                            ->rows(2),

                        Repeater::make('content.integration.items')
                            ->label('Integrationsmöglichkeiten')
                            ->simple(
                                TextInput::make('item')
                                    ->placeholder('z.B. CRM- oder ERP-Systeme')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Textarea::make('content.integration.note')
                            ->label('Abschließende Anmerkung')
                            ->rows(2),
                    ]),
            ]);
    }

    private static function getGrowthTab(): Tab
    {
        return Tab::make('Wachstum')
            ->icon(Heroicon::OutlinedArrowTrendingUp)
            ->schema([
                Section::make('Schrittweise Umsetzung')
                    ->schema([
                        TextInput::make('content.growth.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Schrittweise Umsetzung statt Komplettlösung'),

                        Textarea::make('content.growth.text')
                            ->label('Text')
                            ->rows(3),

                        Repeater::make('content.growth.items')
                            ->label('Beispiele für den Start')
                            ->simple(
                                TextInput::make('item')
                                    ->placeholder('z.B. Login & Projektübersicht')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Textarea::make('content.growth.note')
                            ->label('Abschließende Anmerkung')
                            ->rows(2),
                    ]),
            ]);
    }

    private static function getProcessTab(): Tab
    {
        return Tab::make('Vorgehen')
            ->icon(Heroicon::OutlinedListBullet)
            ->schema([
                Section::make('Unser Vorgehen')
                    ->schema([
                        TextInput::make('content.process.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Unser Vorgehen'),

                        Repeater::make('content.process.steps')
                            ->label('Prozess-Schritte')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Schritt-Titel')
                                    ->placeholder('z.B. Analyse & Zieldefinition')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->placeholder('z.B. Klärung von Nutzergruppen, Prozessen und Zielen.'),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getCtaTab(): Tab
    {
        return Tab::make('CTA')
            ->icon(Heroicon::OutlinedRocketLaunch)
            ->schema([
                Section::make('Call-to-Action')
                    ->schema([
                        TextInput::make('content.cta.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Projekt besprechen'),

                        Textarea::make('content.cta.text')
                            ->label('Text')
                            ->rows(4)
                            ->helperText('Kann Aufzählungspunkte mit • enthalten'),

                        TextInput::make('content.cta.button_text')
                            ->label('Button Text')
                            ->placeholder('z.B. Projekt besprechen'),
                    ]),
            ]);
    }
}
