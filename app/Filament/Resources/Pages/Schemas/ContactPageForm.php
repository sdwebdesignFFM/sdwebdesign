<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ContactPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getFormTab(),
            self::getContactInfoTab(),
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
                            ->placeholder('z.B. Kontakt'),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required(),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),
                    ]),
            ]);
    }

    private static function getFormTab(): Tab
    {
        return Tab::make('Formular')
            ->icon(Heroicon::OutlinedEnvelope)
            ->schema([
                Section::make('Formular-Einstellungen')
                    ->schema([
                        TextInput::make('content.form.title')
                            ->label('Formular-Überschrift')
                            ->placeholder('z.B. Projekt anfragen'),

                        Textarea::make('content.form.description')
                            ->label('Beschreibung')
                            ->rows(2),

                        TextInput::make('content.form.submit_text')
                            ->label('Absenden-Button Text')
                            ->placeholder('z.B. Anfrage senden'),

                        TextInput::make('content.form.success_message')
                            ->label('Erfolgsmeldung')
                            ->placeholder('Vielen Dank für Ihre Anfrage!'),
                    ]),

                Section::make('Projekt-Typen')
                    ->description('Auswahloptionen für "Art des Projekts"')
                    ->schema([
                        Repeater::make('content.form.project_types')
                            ->label('Projekt-Typen')
                            ->schema([
                                TextInput::make('value')
                                    ->label('Wert')
                                    ->required()
                                    ->placeholder('webapp'),

                                TextInput::make('label')
                                    ->label('Anzeige-Text')
                                    ->required()
                                    ->placeholder('Digitale Plattform / Webanwendung'),
                            ])
                            ->addActionLabel('Typ hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                    ]),
            ]);
    }

    private static function getContactInfoTab(): Tab
    {
        return Tab::make('Kontaktdaten')
            ->icon(Heroicon::OutlinedMapPin)
            ->schema([
                Section::make('Kontaktinformationen')
                    ->schema([
                        TextInput::make('content.contact.title')
                            ->label('Überschrift')
                            ->placeholder('z.B. Kontaktinformationen'),
                    ]),

                Section::make('E-Mail')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.contact.email')
                            ->label('E-Mail-Adresse')
                            ->email()
                            ->required(),
                    ]),

                Section::make('Telefon')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.contact.phone')
                            ->label('Telefonnummer')
                            ->tel(),

                        TextInput::make('content.contact.phone_hours')
                            ->label('Erreichbarkeit')
                            ->placeholder('Mo–Fr, 9:00–18:00 Uhr'),
                    ]),

                Section::make('Standort')
                    ->schema([
                        TextInput::make('content.contact.location_city')
                            ->label('Stadt'),

                        TextInput::make('content.contact.location_country')
                            ->label('Land'),
                    ]),

                Section::make('Zusätzliche Info-Box')
                    ->schema([
                        TextInput::make('content.contact.info_title')
                            ->label('Titel')
                            ->placeholder('z.B. Direkter Kontakt bevorzugt?'),

                        Textarea::make('content.contact.info_text')
                            ->label('Text')
                            ->rows(3),

                        TextInput::make('content.contact.response_time')
                            ->label('Reaktionszeit')
                            ->placeholder('z.B. Innerhalb von 24 Stunden'),
                    ]),
            ]);
    }
}
