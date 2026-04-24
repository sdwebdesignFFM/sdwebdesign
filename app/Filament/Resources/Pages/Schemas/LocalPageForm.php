<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
            self::getTrustTab(),
            self::getIntroTab(),
            self::getCityUspTab(),
            self::getCasesTab(),
            self::getTechStackTab(),
            self::getSolutionsTab(),
            self::getWhyTab(),
            self::getLocalSignalTab(),
            self::getCtaTab(),
            self::getSeoTab(),
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

    private static function getTrustTab(): Tab
    {
        return Tab::make('Trust & Preise')
            ->icon(Heroicon::OutlinedShieldCheck)
            ->schema([
                Section::make('Trust-Bar (Hero-Bereich)')
                    ->description('Konkrete Zahlen erhoehen die Glaubwuerdigkeit und werden von Google als E-E-A-T-Signal gewertet. Leer lassen = Block wird ausgeblendet.')
                    ->columns(3)
                    ->schema([
                        TextInput::make('content.trust.project_count')
                            ->label('Projektzahl')
                            ->placeholder('z.B. 50+')
                            ->helperText('Anzahl umgesetzter Projekte'),
                        TextInput::make('content.trust.years_in_business')
                            ->label('Jahre am Markt')
                            ->placeholder('z.B. seit 2015')
                            ->helperText('Gruendungsjahr oder "seit X Jahren"'),
                        TextInput::make('content.trust.rating_label')
                            ->label('Bewertung (optional)')
                            ->placeholder('z.B. 4,9/5 auf Google (23 Bewertungen)')
                            ->helperText('Nur wenn echte Bewertungen vorliegen — leer lassen wenn nicht'),
                    ]),

                Section::make('Preisanker')
                    ->description('Ein sichtbarer Preisanker senkt Bounce Rate und filtert Budget-inkompatible Nutzer vor.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('content.trust.price_anchor_label')
                            ->label('Preis-Anker')
                            ->placeholder('z.B. Websites ab 3.000 €')
                            ->helperText('Prominenter Preis-Claim'),
                        TextInput::make('content.trust.price_anchor_note')
                            ->label('Preis-Zusatz')
                            ->placeholder('z.B. Transparent kalkuliert — keine Ueberraschungen')
                            ->helperText('Kurzer Nebensatz zur Preispolitik'),
                    ]),
            ]);
    }

    private static function getCityUspTab(): Tab
    {
        return Tab::make('Stadt-USP')
            ->icon(Heroicon::OutlinedStar)
            ->schema([
                Section::make('Warum gerade {CITY}')
                    ->description('Der stadt-spezifische Winkel — was macht Kunden aus dieser Stadt fuer uns besonders, und umgekehrt. Ohne diesen Block wirken alle Stadt-Seiten wie Kopien.')
                    ->schema([
                        TextInput::make('content.city_usp.headline')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Erfahrung im Frankfurter Finanzsektor'),
                        Textarea::make('content.city_usp.text')
                            ->label('Text (2-4 Saetze)')
                            ->rows(4)
                            ->placeholder('z.B. Viele unserer Frankfurter Kunden kommen aus dem Finanzumfeld oder B2B-Beratung. Wir kennen die Anforderungen an Compliance, Mehrsprachigkeit und skalierbare Systeme...'),
                        Repeater::make('content.city_usp.bullets')
                            ->label('Bullet Points (optional)')
                            ->simple(
                                TextInput::make('bullet')
                                    ->placeholder('z.B. Fokus auf B2B-Finanzdienstleister')
                            )
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    private static function getCasesTab(): Tab
    {
        return Tab::make('Cases / Referenzen')
            ->icon(Heroicon::OutlinedBriefcase)
            ->schema([
                Section::make('Lokale Referenzen')
                    ->description('Named Clients aus dieser Region. Top-3-Ranker zeigen alle 3-12 benannte Cases. Ohne named references wirkt die Seite template-artig.')
                    ->schema([
                        Repeater::make('content.cases.items')
                            ->label('Kunden / Cases')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Kundenname')
                                    ->required()
                                    ->placeholder('z.B. Mustermann GmbH'),
                                TextInput::make('industry')
                                    ->label('Branche')
                                    ->placeholder('z.B. Finanzberatung')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->rows(2)
                                    ->placeholder('z.B. Unternehmenswebsite mit Kundenportal, mehrsprachig'),
                                TextInput::make('link')
                                    ->label('Link (optional)')
                                    ->placeholder('/referenzen/mustermann-gmbh oder https://...'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Case hinzufuegen'),
                    ]),
            ]);
    }

    private static function getTechStackTab(): Tab
    {
        return Tab::make('Technologie')
            ->icon(Heroicon::OutlinedCodeBracket)
            ->schema([
                Section::make('Technologie-Stack')
                    ->description('Welche Technologien setzt du ein? Tech-Stack-Sichtbarkeit ist ein Trust-Signal gegenueber technisch versierten Entscheidern.')
                    ->schema([
                        TextInput::make('content.tech_stack.headline')
                            ->label('Ueberschrift')
                            ->placeholder('z.B. Unser Technologie-Stack'),
                        Repeater::make('content.tech_stack.items')
                            ->label('Technologien')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->placeholder('z.B. Laravel'),
                                TextInput::make('description')
                                    ->label('Kurzbeschreibung')
                                    ->placeholder('z.B. Backend & Webanwendungen'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Technologie hinzufuegen'),
                    ]),
            ]);
    }

    private static function getSeoTab(): Tab
    {
        return Tab::make('SEO / Indexierung')
            ->icon(Heroicon::OutlinedMagnifyingGlass)
            ->schema([
                Section::make('Indexierung steuern')
                    ->description('Mit diesem Toggle wird die Seite zwar weiter erreichbar, aber aus Googles Index ausgeschlossen. Nutze das fuer Staedte mit sehr duennem Content, damit sie die besser gepflegten Stadt-Seiten nicht verwaessern.')
                    ->schema([
                        Toggle::make('content.meta.noindex')
                            ->label('Aus Google-Index ausschliessen (noindex)')
                            ->helperText('Empfohlen fuer Stadt-Seiten ohne eigenen, einzigartigen Content. Frankfurt und Bad Homburg sollten AUS bleiben.')
                            ->default(false),
                    ]),
            ]);
    }
}
