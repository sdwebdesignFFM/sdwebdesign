<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class ImprintPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getGeneralTab(),
            self::getContentTab(),
            self::getSeoTab(),
        ];
    }

    private static function getGeneralTab(): Tab
    {
        return Tab::make('Allgemein')
            ->icon(Heroicon::OutlinedCog6Tooth)
            ->schema([
                Section::make('Seiteneinstellungen')
                    ->schema([
                        TextInput::make('title')
                            ->label('Seitentitel')
                            ->required()
                            ->helperText('Wechseln Sie die Sprache oben rechts, um die englische Version zu bearbeiten.'),

                        Placeholder::make('info')
                            ->label('')
                            ->content('Die Firmendaten (Adresse, Kontakt, USt-ID) werden aus den zentralen Einstellungen geladen und automatisch angezeigt.')
                            ->columnSpanFull(),

                        Placeholder::make('settings_link')
                            ->label('')
                            ->content(fn (): HtmlString => new HtmlString(
                                '<a href="'.route('filament.admin.pages.settings').'" class="text-primary-600 hover:underline font-medium">→ Firmendaten in den Einstellungen bearbeiten</a>'
                            ))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function getContentTab(): Tab
    {
        return Tab::make('Inhalt')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Zusätzliche Abschnitte')
                    ->description('Hier können Sie optionale zusätzliche Abschnitte hinzufügen, die nach den Standard-Inhalten (Angaben gemäß TMG, Kontakt, etc.) angezeigt werden. Wechseln Sie die Sprache oben rechts, um die englische Version zu bearbeiten.')
                    ->schema([
                        Repeater::make('content.sections')
                            ->label('Abschnitte')
                            ->schema([
                                TextInput::make('heading')
                                    ->label('Überschrift')
                                    ->required(),

                                RichEditor::make('content')
                                    ->label('Inhalt')
                                    ->required()
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                    ]),
                            ])
                            ->addActionLabel('Abschnitt hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? null)
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getSeoTab(): Tab
    {
        return Tab::make('SEO')
            ->icon(Heroicon::OutlinedMagnifyingGlass)
            ->schema([
                Section::make('Suchmaschinenoptimierung')
                    ->description('Wechseln Sie die Sprache oben rechts, um die englische Version zu bearbeiten.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta-Titel')
                            ->placeholder('Wird aus dem Seitentitel generiert, falls leer')
                            ->maxLength(70),

                        TextInput::make('meta_description')
                            ->label('Meta-Beschreibung')
                            ->placeholder('Kurze Beschreibung für Suchmaschinen')
                            ->maxLength(160),
                    ]),
            ]);
    }
}
