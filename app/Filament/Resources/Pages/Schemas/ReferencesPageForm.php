<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ReferencesPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getProjectsTab(),
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
                            ->placeholder('z.B. Referenzen'),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required(),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),
                    ]),
            ]);
    }

    private static function getProjectsTab(): Tab
    {
        return Tab::make('Projekte')
            ->icon(Heroicon::OutlinedBriefcase)
            ->schema([
                Section::make('Referenz-Projekte')
                    ->description('Zeigen Sie Ihre besten Projekte')
                    ->schema([
                        Repeater::make('content.projects')
                            ->label('Projekte')
                            ->schema([
                                TextInput::make('client')
                                    ->label('Kunde/Branche')
                                    ->required()
                                    ->placeholder('z.B. Mittelständischer Maschinenbauer'),

                                TextInput::make('title')
                                    ->label('Projekt-Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->rows(2)
                                    ->required(),

                                Section::make('Herausforderung')
                                    ->collapsed()
                                    ->schema([
                                        TextInput::make('challenge.title')
                                            ->label('Titel'),

                                        Repeater::make('challenge.items')
                                            ->label('Punkte')
                                            ->simple(
                                                TextInput::make('text')
                                                    ->label('Punkt')
                                                    ->required()
                                            )
                                            ->addActionLabel('Punkt hinzufügen'),
                                    ]),

                                Section::make('Lösung')
                                    ->collapsed()
                                    ->schema([
                                        TextInput::make('solution.title')
                                            ->label('Titel'),

                                        Repeater::make('solution.items')
                                            ->label('Punkte')
                                            ->simple(
                                                TextInput::make('text')
                                                    ->label('Punkt')
                                                    ->required()
                                            )
                                            ->addActionLabel('Punkt hinzufügen'),
                                    ]),

                                Section::make('Ergebnis')
                                    ->collapsed()
                                    ->schema([
                                        TextInput::make('result.title')
                                            ->label('Titel'),

                                        Repeater::make('result.items')
                                            ->label('Punkte')
                                            ->simple(
                                                TextInput::make('text')
                                                    ->label('Punkt')
                                                    ->required()
                                            )
                                            ->addActionLabel('Punkt hinzufügen'),
                                    ]),

                                Section::make('Technologie-Stack')
                                    ->collapsed()
                                    ->schema([
                                        Repeater::make('tech_stack')
                                            ->label('Technologien')
                                            ->simple(
                                                TextInput::make('name')
                                                    ->label('Technologie')
                                                    ->required()
                                            )
                                            ->addActionLabel('Technologie hinzufügen'),
                                    ]),
                            ])
                            ->addActionLabel('Projekt hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
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
