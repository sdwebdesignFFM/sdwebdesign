<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class SeaPageForm
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
            self::getSynergyTab(),
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
                            ->placeholder('z.B. Suchmaschinenwerbung als skalierbares System')
                            ->required(),

                        Textarea::make('content.hero.intro')
                            ->label('Einleitungstext')
                            ->rows(6)
                            ->helperText('Ausfuehrlicher Introtext unter dem Titel'),

                        TextInput::make('content.hero.icon')
                            ->label('Icon')
                            ->placeholder('z.B. currency-euro, megaphone')
                            ->helperText('Icon fuer die Uebersicht'),
                    ]),
            ]);
    }

    private static function getWhenUsefulTab(): Tab
    {
        return Tab::make('Wann sinnvoll')
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->schema([
                Section::make('Wann ist SEA sinnvoll?')
                    ->schema([
                        TextInput::make('content.when_useful.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Wann SEA sinnvoll ist'),

                        Repeater::make('content.when_useful.conditions')
                            ->label('Bedingungen')
                            ->simple(
                                TextInput::make('condition')
                                    ->placeholder('z.B. fuer den gezielten Einstieg oder Wachstum')
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
                Section::make('SEA-Schwerpunkte')
                    ->description('Die verschiedenen Bereiche der SEA-Arbeit')
                    ->schema([
                        Repeater::make('content.focus_areas')
                            ->label('Schwerpunktbereiche')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->placeholder('z.B. Kampagnen-Setup & Struktur')
                                    ->required(),

                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. cog, chart-bar'),

                                Repeater::make('items')
                                    ->label('Punkte')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. saubere Kampagnenarchitektur')
                                    )
                                    ->reorderable()
                                    ->defaultItems(0),

                                Textarea::make('link_note')
                                    ->label('Verlinkungshinweis')
                                    ->rows(2)
                                    ->placeholder('z.B. Besonders relevant fuer Shops'),

                                Repeater::make('links')
                                    ->label('Verlinkungen')
                                    ->schema([
                                        TextInput::make('text')
                                            ->label('Link-Text')
                                            ->placeholder('z.B. E-Commerce'),

                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->placeholder('z.B. e-commerce'),
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

    private static function getSynergyTab(): Tab
    {
        return Tab::make('SEO & SEA')
            ->icon(Heroicon::OutlinedLink)
            ->schema([
                Section::make('SEO & SEA gemeinsam denken')
                    ->schema([
                        TextInput::make('content.synergy.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. SEO & SEA gemeinsam denken'),

                        Textarea::make('content.synergy.intro')
                            ->label('Einleitungstext')
                            ->rows(3),

                        Repeater::make('content.synergy.items')
                            ->label('Zusammenspiel')
                            ->simple(
                                TextInput::make('item')
                                    ->placeholder('z.B. SEO baut nachhaltige Sichtbarkeit auf')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Textarea::make('content.synergy.note')
                            ->label('Abschliessender Hinweis')
                            ->rows(2),
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
                            ->placeholder('z.B. SEA sinnvoll einsetzen'),

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
                            ->placeholder('z.B. Gezielte Reichweite durch strukturierte Kampagnen'),

                        Textarea::make('content.card.description')
                            ->label('Kurzbeschreibung')
                            ->rows(3),

                        Repeater::make('content.card.use_cases')
                            ->label('Typische Einsatzbereiche')
                            ->simple(
                                TextInput::make('use_case')
                                    ->placeholder('z.B. Performance-Kampagnen')
                            )
                            ->reorderable()
                            ->defaultItems(0),

                        Repeater::make('content.card.character')
                            ->label('Charakter')
                            ->simple(
                                TextInput::make('trait')
                                    ->placeholder('z.B. Skalierbar & messbar')
                            )
                            ->reorderable()
                            ->defaultItems(0),
                    ]),
            ]);
    }
}
