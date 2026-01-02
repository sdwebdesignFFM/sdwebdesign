<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class GuidePageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getContentTab(),
            self::getComparisonTab(),
            self::getRecommendationsTab(),
            self::getCtaTab(),
        ];
    }

    private static function getHeroTab(): Tab
    {
        return Tab::make('Hero')
            ->icon(Heroicon::OutlinedSparkles)
            ->schema([
                Section::make('Kopfbereich')
                    ->description('Die Headline wird aus dem Titel im "Allgemein"-Tab übernommen.')
                    ->schema([
                        TextInput::make('content.hero.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. Ratgeber'),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),
                    ]),
            ]);
    }

    private static function getContentTab(): Tab
    {
        return Tab::make('Inhalt')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Einleitung')
                    ->schema([
                        Textarea::make('content.intro.text')
                            ->label('Einleitungstext')
                            ->rows(4),
                    ]),

                Section::make('Inhaltsabschnitte')
                    ->schema([
                        Repeater::make('content.sections')
                            ->label('Abschnitte')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Ueberschrift')
                                    ->required(),

                                RichEditor::make('content')
                                    ->label('Inhalt')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'bulletList',
                                        'orderedList',
                                        'h2',
                                        'h3',
                                    ])
                                    ->required(),
                            ])
                            ->addActionLabel('Abschnitt hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(10),
                    ]),
            ]);
    }

    private static function getComparisonTab(): Tab
    {
        return Tab::make('Vergleich')
            ->icon(Heroicon::OutlinedTableCells)
            ->schema([
                Section::make('Vergleichstabelle')
                    ->schema([
                        TextInput::make('content.comparison.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Vor- & Nachteile im Ueberblick'),

                        Repeater::make('content.comparison.items')
                            ->label('Vergleichspunkte')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Loesung')
                                    ->required()
                                    ->placeholder('z.B. Starter-Website'),

                                Textarea::make('pros')
                                    ->label('Vorteile')
                                    ->rows(3)
                                    ->placeholder('Ein Vorteil pro Zeile'),

                                Textarea::make('cons')
                                    ->label('Grenzen')
                                    ->rows(3)
                                    ->placeholder('Eine Grenze pro Zeile'),

                                TextInput::make('link')
                                    ->label('Link zur Detailseite')
                                    ->placeholder('/loesungen/websites/starter-website/'),
                            ])
                            ->addActionLabel('Loesung hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->maxItems(5),
                    ]),
            ]);
    }

    private static function getRecommendationsTab(): Tab
    {
        return Tab::make('Empfehlungen')
            ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
            ->schema([
                Section::make('Verknuepfte Loesungen')
                    ->schema([
                        TextInput::make('content.recommendations.title')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Unsere Loesungen im Detail'),

                        Repeater::make('content.related_solutions')
                            ->label('Verknuepfte Seiten (Slugs)')
                            ->simple(
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->placeholder('z.B. starter-website')
                            )
                            ->addActionLabel('Seite hinzufuegen')
                            ->maxItems(6),
                    ]),
            ]);
    }

    private static function getCtaTab(): Tab
    {
        return Tab::make('CTA')
            ->icon(Heroicon::OutlinedRocketLaunch)
            ->schema([
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
