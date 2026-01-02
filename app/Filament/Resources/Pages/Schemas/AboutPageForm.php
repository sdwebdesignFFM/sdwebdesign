<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class AboutPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getTeamTab(),
            self::getPrinciplesTab(),
            self::getWorkApproachTab(),
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
                            ->placeholder('z.B. Über uns'),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required(),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),
                    ]),
            ]);
    }

    private static function getTeamTab(): Tab
    {
        return Tab::make('Team')
            ->icon(Heroicon::OutlinedUsers)
            ->schema([
                Section::make('Team-Sektion')
                    ->schema([
                        TextInput::make('content.team.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Ihr Ansprechpartner'),

                        Repeater::make('content.team.members')
                            ->label('Team-Mitglieder')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required(),

                                TextInput::make('role')
                                    ->label('Position/Rolle')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(3),

                                TextInput::make('experience')
                                    ->label('Erfahrung')
                                    ->placeholder('z.B. 10+ Jahre'),

                                Repeater::make('expertise')
                                    ->label('Expertise-Bereiche')
                                    ->simple(
                                        TextInput::make('area')
                                            ->label('Bereich')
                                            ->required()
                                    )
                                    ->addActionLabel('Expertise hinzufügen')
                                    ->maxItems(6),
                            ])
                            ->addActionLabel('Mitglied hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->maxItems(5),
                    ]),
            ]);
    }

    private static function getPrinciplesTab(): Tab
    {
        return Tab::make('Prinzipien')
            ->icon(Heroicon::OutlinedLightBulb)
            ->schema([
                Section::make('Unsere Prinzipien')
                    ->schema([
                        TextInput::make('content.principles.title')
                            ->label('Überschrift'),

                        Repeater::make('content.principles.items')
                            ->label('Prinzipien')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Prinzip hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(6),
                    ]),
            ]);
    }

    private static function getWorkApproachTab(): Tab
    {
        return Tab::make('Arbeitsweise')
            ->icon(Heroicon::OutlinedArrowPath)
            ->schema([
                Section::make('Wie wir arbeiten')
                    ->schema([
                        TextInput::make('content.approach.title')
                            ->label('Überschrift'),

                        Textarea::make('content.approach.description')
                            ->label('Einleitungstext')
                            ->rows(3),

                        Repeater::make('content.approach.items')
                            ->label('Arbeitsweise-Punkte')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Punkt hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(4),
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
