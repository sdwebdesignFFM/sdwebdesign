<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class MaintenancePageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getWhenUsefulTab(),
            self::getApproachTab(),
            self::getInfrastructureTab(),
            self::getServicesTab(),
            self::getModelsTab(),
            self::getDifferentiationTab(),
            self::getCtaTab(),
            self::getCardTab(),
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
                            ->placeholder('z.B. 07'),

                        TextInput::make('content.hero.title')
                            ->label('Headline (H1)')
                            ->placeholder('z.B. Betrieb, Hosting & Wartung')
                            ->required(),

                        Textarea::make('content.hero.intro')
                            ->label('Einleitungstext')
                            ->rows(4)
                            ->helperText('Kurzer Introtext unter dem Titel'),

                        TextInput::make('content.hero.icon')
                            ->label('Icon')
                            ->placeholder('z.B. server-stack')
                            ->helperText('Heroicon-Name'),
                    ]),
            ]);
    }

    private static function getWhenUsefulTab(): Tab
    {
        return Tab::make('Wann sinnvoll')
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->schema([
                Section::make('Wann ist Betrieb & Wartung sinnvoll?')
                    ->schema([
                        TextInput::make('content.when_useful.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Wann Betrieb & Wartung sinnvoll sind'),

                        Repeater::make('content.when_useful.conditions')
                            ->label('Bedingungen')
                            ->simple(
                                TextInput::make('condition')
                                    ->placeholder('z.B. Ihre Website muss zuverlaessig erreichbar sein')
                            )
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getApproachTab(): Tab
    {
        return Tab::make('Vorgehen')
            ->icon(Heroicon::OutlinedListBullet)
            ->schema([
                Section::make('Wie wir Betrieb aufsetzen')
                    ->schema([
                        TextInput::make('content.approach.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Wie wir Betrieb aufsetzen'),

                        Repeater::make('content.approach.steps')
                            ->label('Schritte')
                            ->schema([
                                TextInput::make('number')
                                    ->label('Nummer')
                                    ->placeholder('z.B. 01'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->placeholder('z.B. Bestandsaufnahme')
                                    ->required(),

                                Textarea::make('text')
                                    ->label('Beschreibung')
                                    ->rows(2),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getInfrastructureTab(): Tab
    {
        return Tab::make('Infrastruktur')
            ->icon(Heroicon::OutlinedServerStack)
            ->schema([
                Section::make('Infrastruktur & Tooling')
                    ->schema([
                        TextInput::make('content.infrastructure.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Infrastruktur & Tooling'),

                        Repeater::make('content.infrastructure.items')
                            ->label('Tools / Dienste')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Name')
                                    ->placeholder('z.B. Hetzner / DigitalOcean')
                                    ->required(),

                                TextInput::make('text')
                                    ->label('Beschreibung')
                                    ->placeholder('z.B. Europaeische VPS mit hoher Verfuegbarkeit'),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getServicesTab(): Tab
    {
        return Tab::make('Leistungen')
            ->icon(Heroicon::OutlinedWrenchScrewdriver)
            ->schema([
                Section::make('Was wir im Betrieb uebernehmen')
                    ->schema([
                        TextInput::make('content.services.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Was wir im Betrieb uebernehmen'),

                        Repeater::make('content.services.categories')
                            ->label('Kategorien')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Kategorie-Titel')
                                    ->placeholder('z.B. Updates & Sicherheit')
                                    ->required(),

                                Repeater::make('items')
                                    ->label('Einzelne Leistungen')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Regelmaessige CMS-Updates')
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

    private static function getModelsTab(): Tab
    {
        return Tab::make('Modelle')
            ->icon(Heroicon::OutlinedBolt)
            ->schema([
                Section::make('Betriebsmodelle')
                    ->schema([
                        TextInput::make('content.models.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Betriebsmodelle'),

                        Repeater::make('content.models.items')
                            ->label('Modelle')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Modell-Name')
                                    ->placeholder('z.B. Basis')
                                    ->required(),

                                TextInput::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->placeholder('z.B. Fuer kleinere Websites'),

                                Repeater::make('features')
                                    ->label('Leistungen')
                                    ->simple(
                                        TextInput::make('feature')
                                            ->placeholder('z.B. Hosting auf managed VPS')
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
                Section::make('Kein Standard-Hosting')
                    ->schema([
                        TextInput::make('content.differentiation.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Kein Standard-Hosting'),

                        Textarea::make('content.differentiation.text')
                            ->label('Text')
                            ->rows(4),
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
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Lassen Sie uns ueber Ihren Betrieb sprechen'),

                        Textarea::make('content.cta.text')
                            ->label('Text')
                            ->rows(3),

                        TextInput::make('content.cta.button_text')
                            ->label('Button-Text')
                            ->placeholder('z.B. Projekt besprechen'),
                    ]),
            ]);
    }

    private static function getCardTab(): Tab
    {
        return Tab::make('Card')
            ->icon(Heroicon::OutlinedSquare2Stack)
            ->schema([
                Section::make('Darstellung in der Uebersicht')
                    ->description('Inhalte fuer die Loesungsuebersicht')
                    ->schema([
                        TextInput::make('content.card.subtitle')
                            ->label('Untertitel')
                            ->placeholder('z.B. Zuverlaessiger Betrieb'),

                        Textarea::make('content.card.description')
                            ->label('Kurzbeschreibung')
                            ->rows(3),

                        Repeater::make('content.card.use_cases')
                            ->label('Typische Einsatzbereiche')
                            ->simple(
                                TextInput::make('use_case')
                                    ->placeholder('z.B. Managed Hosting')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Repeater::make('content.card.character')
                            ->label('Charakter')
                            ->simple(
                                TextInput::make('trait')
                                    ->placeholder('z.B. Proaktiv statt reaktiv')
                            )
                            ->reorderable()
                            ->defaultItems(0),
                    ]),
            ]);
    }
}
