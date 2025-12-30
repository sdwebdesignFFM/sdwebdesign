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
            self::getFeaturesTab(),
            self::getUseCasesTab(),
            self::getTechStackTab(),
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
                            ->placeholder('z.B. Digitale Plattformen'),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required(),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),

                        TextInput::make('content.hero.icon')
                            ->label('Icon')
                            ->placeholder('z.B. globe, settings')
                            ->helperText('Icon für die Lösungs-Übersicht'),
                    ]),
            ]);
    }

    private static function getFeaturesTab(): Tab
    {
        return Tab::make('Features')
            ->icon(Heroicon::OutlinedRectangleGroup)
            ->schema([
                Section::make('Leistungsmerkmale')
                    ->schema([
                        TextInput::make('content.features.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Funktionen & Möglichkeiten'),

                        Repeater::make('content.features.items')
                            ->label('Features')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('check, arrow-right'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Feature hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(8),
                    ]),
            ]);
    }

    private static function getUseCasesTab(): Tab
    {
        return Tab::make('Use Cases')
            ->icon(Heroicon::OutlinedLightBulb)
            ->schema([
                Section::make('Anwendungsfälle')
                    ->schema([
                        TextInput::make('content.use_cases.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Typische Anwendungsfälle'),

                        Repeater::make('content.use_cases.items')
                            ->label('Use Cases')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Use Case hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(6),
                    ]),
            ]);
    }

    private static function getTechStackTab(): Tab
    {
        return Tab::make('Technologien')
            ->icon(Heroicon::OutlinedCodeBracket)
            ->schema([
                Section::make('Technologie-Stack')
                    ->schema([
                        TextInput::make('content.tech.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Eingesetzte Technologien'),

                        Repeater::make('content.tech.items')
                            ->label('Technologien')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->placeholder('z.B. Laravel, React, PostgreSQL'),
                            ])
                            ->addActionLabel('Technologie hinzufügen')
                            ->simple()
                            ->maxItems(10),
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
