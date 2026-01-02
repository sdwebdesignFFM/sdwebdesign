<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class GuideOverviewPageForm
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
                        TextInput::make('content.hero.badge')
                            ->label('Badge-Text')
                            ->placeholder('z.B. Ratgeber'),

                        TextInput::make('content.hero.title')
                            ->label('Headline')
                            ->required(),

                        Textarea::make('content.hero.subtitle')
                            ->label('Subline')
                            ->rows(3),
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
                            ->label('Einleitungstext')
                            ->rows(4)
                            ->helperText('Kurze Einfuehrung zur Ratgeber-Uebersicht'),
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
                            ->label('Ueberschrift')
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
