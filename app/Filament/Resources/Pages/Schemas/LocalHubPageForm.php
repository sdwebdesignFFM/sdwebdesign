<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class LocalHubPageForm
{
    /**
     * @return array<Tab>
     */
    public static function getTabs(): array
    {
        return [
            self::getHeroTab(),
            self::getIntroTab(),
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
                        TextInput::make('content.hero.title')
                            ->label('H1 Headline')
                            ->placeholder('Webagentur im Rhein-Main-Gebiet – regionale Expertise & digitale Systeme'),
                    ]),
            ]);
    }

    private static function getIntroTab(): Tab
    {
        return Tab::make('Intro')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Einfuehrungstext')
                    ->schema([
                        Textarea::make('content.intro.text')
                            ->label('Erster Absatz')
                            ->rows(4),

                        Textarea::make('content.intro.text2')
                            ->label('Zweiter Absatz')
                            ->rows(4),
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
                        TextInput::make('content.cta.title')
                            ->label('Ueberschrift'),

                        Textarea::make('content.cta.text')
                            ->label('Text')
                            ->rows(2),

                        TextInput::make('content.cta.button_text')
                            ->label('Button Text'),
                    ]),
            ]);
    }
}
