<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class LocalPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getLocalDataTab(),
            self::getIntroTab(),
            self::getSolutionsTab(),
            self::getWhyTab(),
            self::getLocalSignalTab(),
            self::getCtaTab(),
        ];
    }

    private static function getLocalDataTab(): Tab
    {
        return Tab::make('Lokale Daten')
            ->icon(Heroicon::OutlinedMapPin)
            ->schema([
                Section::make('Standort-Informationen')
                    ->description('Diese Variablen werden im Template verwendet')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.city')
                            ->label('Stadt')
                            ->required()
                            ->placeholder('z.B. Bad Homburg')
                            ->helperText('Wird als {CITY} im Template eingesetzt'),

                        TextInput::make('content.region')
                            ->label('Region')
                            ->placeholder('z.B. Rhein-Main-Gebiet')
                            ->helperText('Wird als {REGION} im Template eingesetzt'),
                    ]),
            ]);
    }

    private static function getIntroTab(): Tab
    {
        return Tab::make('Intro')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Local Intro')
                    ->description('Einfuehrungstext fuer die lokale Landingpage')
                    ->schema([
                        TextInput::make('content.intro.headline')
                            ->label('H1 Headline')
                            ->placeholder('Webagentur fuer {CITY} – Websites, Shops & digitale Systeme')
                            ->helperText('Leer lassen fuer Standard-Headline mit Stadt-Variable'),

                        Textarea::make('content.intro.text')
                            ->label('Intro-Text')
                            ->rows(4)
                            ->placeholder('Wir unterstuetzen Unternehmen aus {CITY} und dem {REGION}...')
                            ->helperText('Leer lassen fuer Standard-Text mit Stadt/Region-Variablen'),
                    ]),

                Section::make('Lokaler Bezug')
                    ->description('Kontext zu typischen Unternehmen und Anforderungen in der Region')
                    ->schema([
                        Textarea::make('content.local_context.text')
                            ->label('Lokaler Kontext')
                            ->rows(4)
                            ->placeholder('In {CITY} arbeiten wir haeufig mit mittelstaendischen Unternehmen...')
                            ->helperText('Beschreibt typische Kunden und Anforderungen in dieser Stadt/Region'),
                    ]),
            ]);
    }

    private static function getSolutionsTab(): Tab
    {
        return Tab::make('Loesungen')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                Section::make('Loesungs-Block')
                    ->description('Links zu den Loesungs-Hubs')
                    ->schema([
                        TextInput::make('content.solutions.headline')
                            ->label('Ueberschrift')
                            ->placeholder('Unsere Loesungen fuer Unternehmen aus {CITY}')
                            ->helperText('Leer lassen fuer Standard-Headline'),

                        Textarea::make('content.solutions.text')
                            ->label('Kurztext')
                            ->rows(2)
                            ->placeholder('Je nach Zielsetzung starten Projekte oft schlank...')
                            ->helperText('Leer lassen fuer Standard-Text'),
                    ]),
            ]);
    }

    private static function getWhyTab(): Tab
    {
        return Tab::make('Vorgehen')
            ->icon(Heroicon::OutlinedListBullet)
            ->schema([
                Section::make('So arbeiten wir')
                    ->description('Kurze Beschreibung des Vorgehens')
                    ->schema([
                        TextInput::make('content.why.headline')
                            ->label('Ueberschrift')
                            ->placeholder('So arbeiten wir')
                            ->helperText('Leer lassen fuer Standard-Headline'),

                        Textarea::make('content.why.text')
                            ->label('Text')
                            ->rows(3)
                            ->placeholder('Wir starten mit einer klaren Einordnung...')
                            ->helperText('Leer lassen fuer Standard-Text'),

                        Repeater::make('content.why.bullets')
                            ->label('Stichpunkte')
                            ->simple(
                                TextInput::make('bullet')
                                    ->placeholder('z.B. Saubere Technik & Performance als Grundlage')
                            )
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->helperText('Leer lassen fuer Standard-Stichpunkte'),
                    ]),
            ]);
    }

    private static function getLocalSignalTab(): Tab
    {
        return Tab::make('Lokales Signal')
            ->icon(Heroicon::OutlinedBuildingOffice2)
            ->schema([
                Section::make('Regional verankert')
                    ->description('Optionaler lokaler Bezug')
                    ->schema([
                        TextInput::make('content.local_signal.headline')
                            ->label('Ueberschrift')
                            ->placeholder('Regional verankert, ueberregional umsetzungsstark')
                            ->helperText('Leer lassen fuer Standard-Headline'),

                        Textarea::make('content.local_signal.text')
                            ->label('Text')
                            ->rows(3)
                            ->placeholder('Durch unsere Naehe zu {CITY} sind Abstimmungen schnell...')
                            ->helperText('Leer lassen fuer Standard-Text'),
                    ]),
            ]);
    }

    private static function getCtaTab(): Tab
    {
        return Tab::make('CTA')
            ->icon(Heroicon::OutlinedRocketLaunch)
            ->schema([
                Section::make('Call-to-Action')
                    ->schema([
                        TextInput::make('content.cta.button_text')
                            ->label('Button Text')
                            ->placeholder('Projekt besprechen')
                            ->helperText('Leer lassen fuer Standard-Button-Text'),
                    ]),
            ]);
    }
}
