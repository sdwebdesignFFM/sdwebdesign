<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class LegalPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getGeneralTab(),
            self::getAdditionalSectionsTab(),
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
                            ->required(),

                        Placeholder::make('info')
                            ->label('')
                            ->content('Die rechtlichen Inhalte (Impressum / Datenschutz) werden automatisch aus dem Template generiert. Die Firmendaten werden aus den zentralen Einstellungen geladen.')
                            ->columnSpanFull(),

                        Placeholder::make('settings_link')
                            ->label('')
                            ->content(fn () => new \Illuminate\Support\HtmlString(
                                '<a href="'.route('filament.admin.pages.settings').'" class="text-primary-600 hover:underline font-medium">→ Firmendaten in den Einstellungen bearbeiten</a>'
                            ))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function getAdditionalSectionsTab(): Tab
    {
        return Tab::make('Zusatzinhalte')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Zusaetzliche Abschnitte')
                    ->description('Hier koennen Sie optionale zusaetzliche Abschnitte hinzufuegen, die nach den Standard-Inhalten angezeigt werden.')
                    ->schema([
                        Repeater::make('content.sections')
                            ->label('Abschnitte')
                            ->schema([
                                TextInput::make('heading')
                                    ->label('Ueberschrift')
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
                            ->addActionLabel('Abschnitt hinzufuegen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? null),
                    ]),
            ]);
    }

    private static function getSeoTab(): Tab
    {
        return Tab::make('SEO')
            ->icon(Heroicon::OutlinedMagnifyingGlass)
            ->schema([
                Section::make('Suchmaschinenoptimierung')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta-Titel')
                            ->placeholder('Wird aus dem Seitentitel generiert, falls leer')
                            ->maxLength(70),

                        TextInput::make('meta_description')
                            ->label('Meta-Beschreibung')
                            ->placeholder('Kurze Beschreibung fuer Suchmaschinen')
                            ->maxLength(160),
                    ]),
            ]);
    }
}
