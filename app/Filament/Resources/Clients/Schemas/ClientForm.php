<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kontaktdaten')
                    ->columns(2)
                    ->schema([
                        Select::make('salutation')
                            ->label('Anrede')
                            ->options([
                                'Herr' => 'Herr',
                                'Frau' => 'Frau',
                                'Divers' => 'Divers',
                            ])
                            ->placeholder('Bitte wählen'),

                        TextInput::make('title')
                            ->label('Titel')
                            ->placeholder('z.B. Dr., Prof.')
                            ->maxLength(50),

                        TextInput::make('first_name')
                            ->label('Vorname')
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->label('Nachname')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('company')
                            ->label('Unternehmen')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('email')
                            ->label('E-Mail')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('street')
                            ->label('Straße')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Grid::make(4)
                            ->schema([
                                TextInput::make('zip')
                                    ->label('PLZ')
                                    ->maxLength(20),

                                TextInput::make('city')
                                    ->label('Stadt')
                                    ->maxLength(255)
                                    ->columnSpan(2),

                                TextInput::make('country')
                                    ->label('Land')
                                    ->maxLength(255)
                                    ->default('Deutschland'),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Zusätzliche Informationen')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Verknüpfter Benutzer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Optional: Ermöglicht dem Kunden Login-Zugang'),

                        TextInput::make('default_hourly_rate')
                            ->label('Standard-Stundensatz')
                            ->numeric()
                            ->prefix('€')
                            ->placeholder('85.00')
                            ->helperText('Überschreibt den globalen Stundensatz für diesen Kunden'),

                        Textarea::make('notes')
                            ->label('Interne Notizen')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
