<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
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
            self::getContentTab(),
            self::getCompanyInfoTab(),
        ];
    }

    private static function getContentTab(): Tab
    {
        return Tab::make('Inhalt')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Seiteninhalt')
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
                                        'h2',
                                        'h3',
                                    ]),
                            ])
                            ->addActionLabel('Abschnitt hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? null),
                    ]),
            ]);
    }

    private static function getCompanyInfoTab(): Tab
    {
        return Tab::make('Firmendaten')
            ->icon(Heroicon::OutlinedBuildingOffice)
            ->schema([
                Section::make('Unternehmensinformationen')
                    ->description('Diese Daten werden im Impressum und der Datenschutzerklärung verwendet')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.company.name')
                            ->label('Firmenname')
                            ->columnSpanFull(),

                        TextInput::make('content.company.owner')
                            ->label('Inhaber/Geschäftsführer'),

                        TextInput::make('content.company.street')
                            ->label('Straße'),

                        TextInput::make('content.company.zip')
                            ->label('PLZ'),

                        TextInput::make('content.company.city')
                            ->label('Stadt'),

                        TextInput::make('content.company.email')
                            ->label('E-Mail')
                            ->email(),

                        TextInput::make('content.company.phone')
                            ->label('Telefon')
                            ->tel(),

                        TextInput::make('content.company.vat_id')
                            ->label('USt-IdNr.')
                            ->placeholder('DE XXX XXX XXX'),
                    ]),
            ]);
    }
}
