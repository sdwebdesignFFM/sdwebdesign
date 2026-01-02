<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class SeoPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getWhenUsefulTab(),
            self::getFocusAreasTab(),
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
                        TextInput::make('content.hero.title')
                            ->label('Headline (H1)')
                            ->placeholder('z.B. Suchmaschinenoptimierung mit technischer Substanz')
                            ->required(),

                        Textarea::make('content.hero.intro')
                            ->label('Einleitungstext')
                            ->rows(6)
                            ->helperText('Ausfuehrlicher Introtext unter dem Titel'),

                        TextInput::make('content.hero.icon')
                            ->label('Icon')
                            ->placeholder('z.B. magnifying-glass, chart-bar')
                            ->helperText('Icon fuer die Uebersicht'),
                    ]),
            ]);
    }

    private static function getWhenUsefulTab(): Tab
    {
        return Tab::make('Wann sinnvoll')
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->schema([
                Section::make('Wann ist SEO sinnvoll?')
                    ->schema([
                        TextInput::make('content.when_useful.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Wann SEO sinnvoll ist'),

                        Repeater::make('content.when_useful.conditions')
                            ->label('Bedingungen')
                            ->simple(
                                TextInput::make('condition')
                                    ->placeholder('z.B. Ihre Website langfristig Leads generieren soll')
                            )
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getFocusAreasTab(): Tab
    {
        return Tab::make('Schwerpunkte')
            ->icon(Heroicon::OutlinedRectangleGroup)
            ->schema([
                Section::make('SEO-Schwerpunkte')
                    ->description('Die verschiedenen Bereiche der SEO-Arbeit')
                    ->schema([
                        Repeater::make('content.focus_areas')
                            ->label('Schwerpunktbereiche')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->placeholder('z.B. Technisches SEO')
                                    ->required(),

                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. cog, server'),

                                Repeater::make('items')
                                    ->label('Punkte')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Ladezeiten & Performance')
                                    )
                                    ->reorderable()
                                    ->defaultItems(0),

                                Textarea::make('link_note')
                                    ->label('Verlinkungshinweis')
                                    ->rows(2)
                                    ->placeholder('z.B. Besonders relevant fuer Unternehmenswebsites'),

                                Repeater::make('links')
                                    ->label('Verlinkungen')
                                    ->schema([
                                        TextInput::make('text')
                                            ->label('Link-Text')
                                            ->placeholder('z.B. Unternehmenswebsites'),

                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->placeholder('z.B. websites'),
                                    ])
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
                Section::make('Abgrenzung zu klassischen SEO-Agenturen')
                    ->schema([
                        TextInput::make('content.differentiation.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Abgrenzung zu klassischen SEO-Agenturen'),

                        Textarea::make('content.differentiation.intro')
                            ->label('Einleitung')
                            ->rows(3),

                        Repeater::make('content.differentiation.items')
                            ->label('Unsere Staerken')
                            ->simple(
                                TextInput::make('item')
                                    ->placeholder('z.B. sauberer Technik')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Textarea::make('content.differentiation.note')
                            ->label('Abschliessender Hinweis')
                            ->rows(3),
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
                            ->placeholder('z.B. SEO sinnvoll einschaetzen'),

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
                            ->placeholder('z.B. Nachhaltige Sichtbarkeit durch technische Substanz'),

                        Textarea::make('content.card.description')
                            ->label('Kurzbeschreibung')
                            ->rows(3),

                        Repeater::make('content.card.use_cases')
                            ->label('Typische Einsatzbereiche')
                            ->simple(
                                TextInput::make('use_case')
                                    ->placeholder('z.B. Technisches SEO & Performance')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Repeater::make('content.card.character')
                            ->label('Charakter')
                            ->simple(
                                TextInput::make('trait')
                                    ->placeholder('z.B. Fokus auf nachhaltige Ergebnisse')
                            )
                            ->reorderable()
                            ->defaultItems(0),
                    ]),
            ]);
    }
}
