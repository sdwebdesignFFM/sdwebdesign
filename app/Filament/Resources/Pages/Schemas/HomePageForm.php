<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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
            self::getServicesTab(),
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
                            ->placeholder('z.B. Digitale Lösungen')
                            ->maxLength(50),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3)
                            ->maxLength(500),
                    ]),

                Section::make('Call-to-Action Buttons')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.hero.cta_primary_text')
                            ->label('Primärer Button Text')
                            ->placeholder('z.B. Projekt besprechen'),

                        TextInput::make('content.hero.cta_primary_link')
                            ->label('Primärer Button Link')
                            ->placeholder('/kontakt'),

                        TextInput::make('content.hero.cta_secondary_text')
                            ->label('Sekundärer Button Text')
                            ->placeholder('z.B. Lösungen ansehen'),

                        TextInput::make('content.hero.cta_secondary_link')
                            ->label('Sekundärer Button Link')
                            ->placeholder('/loesungen'),
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
                            ->label('Überschrift')
                            ->required()
                            ->maxLength(255),

                        Repeater::make('content.problem.items')
                            ->label('Problem-Punkte')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Problem hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(4),
                    ]),
            ]);
    }

    private static function getServicesTab(): Tab
    {
        return Tab::make('Leistungen')
            ->icon(Heroicon::OutlinedRectangleGroup)
            ->schema([
                Section::make('Leistungen-Übersicht')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('content.services.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. KERNKOMPETENZEN'),

                        TextInput::make('content.services.title')
                            ->label('Überschrift')
                            ->required(),

                        Textarea::make('content.services.subtitle')
                            ->label('Untertitel')
                            ->rows(2),
                    ]),

                Section::make('Button')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.services.button_text')
                            ->label('Button Text')
                            ->placeholder('z.B. Alle Lösungen im Detail'),

                        TextInput::make('content.services.button_link')
                            ->label('Button Link')
                            ->placeholder('/loesungen'),
                    ]),

                Section::make('Leistungs-Karten (Accordion)')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('content.services.items')
                            ->label('Leistungen')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. code, workflow, git-branch')
                                    ->helperText('Verfügbar: code, workflow, git-branch, layers, globe, database'),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->rows(2)
                                    ->required(),

                                TextInput::make('link')
                                    ->label('Detail-Link')
                                    ->placeholder('/loesungen/digitale-plattformen'),

                                Repeater::make('capabilities')
                                    ->label('Systemfähigkeiten')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Geschäftslogik & Regelwerke')
                                    )
                                    ->addActionLabel('Fähigkeit hinzufügen')
                                    ->reorderable()
                                    ->maxItems(6),

                                Repeater::make('technical_focus')
                                    ->label('Technischer Fokus')
                                    ->simple(
                                        TextInput::make('item')
                                            ->placeholder('z.B. Saubere Trennung von Oberfläche, Logik und Daten')
                                    )
                                    ->addActionLabel('Fokus hinzufügen')
                                    ->reorderable()
                                    ->maxItems(6),
                            ])
                            ->addActionLabel('Leistung hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(6),
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
                            ->label('Überschrift')
                            ->required(),

                        Textarea::make('content.principles.subtitle')
                            ->label('Untertitel')
                            ->rows(2)
                            ->placeholder('z.B. Technische Entscheidungen bestimmen, ob ein System nach 2 Jahren noch erweiterbar ist...'),

                        Repeater::make('content.principles.items')
                            ->label('Prinzipien')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('z.B. layers, git-branch, refresh-cw, wrench')
                                    ->helperText('Verfügbar: layers, git-branch, refresh-cw, wrench, code, database'),

                                TextInput::make('number')
                                    ->label('Nummer')
                                    ->placeholder('01')
                                    ->maxLength(2),

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
                            ->maxItems(4),
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
                            ->label('Überschrift')
                            ->required(),

                        Repeater::make('content.why_us.items')
                            ->label('Argumente')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Argument hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(4),
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
                            ->label('Überschrift')
                            ->required(),

                        Repeater::make('content.process.steps')
                            ->label('Prozess-Schritte')
                            ->schema([
                                TextInput::make('number')
                                    ->label('Schritt-Nummer')
                                    ->placeholder('01')
                                    ->maxLength(2),

                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required(),

                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->addActionLabel('Schritt hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->maxItems(5),
                    ]),
            ]);
    }

    private static function getCtaTab(): Tab
    {
        return Tab::make('CTA')
            ->icon(Heroicon::OutlinedRocketLaunch)
            ->schema([
                Section::make('Call-to-Action Sektion')
                    ->description('Abschließender Aufruf zur Kontaktaufnahme')
                    ->schema([
                        TextInput::make('content.cta.title')
                            ->label('Überschrift')
                            ->required(),

                        Textarea::make('content.cta.subtitle')
                            ->label('Untertitel')
                            ->rows(2),
                    ]),

                Section::make('Buttons')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.cta.button_text')
                            ->label('Button Text')
                            ->placeholder('z.B. Projekt besprechen'),

                        TextInput::make('content.cta.button_link')
                            ->label('Button Link')
                            ->placeholder('/kontakt'),
                    ]),
            ]);
    }
}
