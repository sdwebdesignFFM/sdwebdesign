<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class SolutionsPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getSolutionsTab(),
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
                            ->placeholder('z.B. Lösungen'),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required(),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),
                    ]),
            ]);
    }

    private static function getSolutionsTab(): Tab
    {
        return Tab::make('Lösungen')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                Section::make('Lösungs-Übersicht')
                    ->description('Diese Lösungen werden auf der Übersichtsseite angezeigt')
                    ->schema([
                        Repeater::make('content.solutions')
                            ->label('Lösungen')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. globe, settings, git-branch')
                                    ->helperText('Verfügbar: globe, settings, git-branch, shopping-cart, smartphone, code'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->rows(2)
                                    ->required(),

                                TextInput::make('slug')
                                    ->label('URL-Slug')
                                    ->placeholder('digitale-plattformen')
                                    ->helperText('Für die Detail-Seite: /loesungen/{slug}'),

                                Repeater::make('features')
                                    ->label('Features')
                                    ->simple(
                                        TextInput::make('text')
                                            ->label('Feature')
                                            ->required()
                                    )
                                    ->addActionLabel('Feature hinzufügen')
                                    ->maxItems(5),
                            ])
                            ->addActionLabel('Lösung hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(8),
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
                            ->label('Überschrift')
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
