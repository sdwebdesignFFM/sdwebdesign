<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class PageFormResolver
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Seiten-Tabs')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs(fn (Get $get) => self::getTabsForType($get('type'))),
            ]);
    }

    /**
     * @return array<Tab>
     */
    private static function getTabsForType(?string $type): array
    {
        $baseTabs = [
            self::getGeneralTab(),
        ];

        $contentTabs = match ($type) {
            Page::TYPE_HOME => HomePageForm::getTabs(),
            Page::TYPE_SOLUTIONS => SolutionsPageForm::getTabs(),
            Page::TYPE_SOLUTION_HUB => HubPageForm::getTabs(),
            Page::TYPE_SOLUTION_DETAIL => SolutionDetailPageForm::getTabs(),
            Page::TYPE_REFERENCES => ReferencesPageForm::getTabs(),
            Page::TYPE_ABOUT => AboutPageForm::getTabs(),
            Page::TYPE_CONTACT => ContactPageForm::getTabs(),
            Page::TYPE_IMPRINT, Page::TYPE_PRIVACY => LegalPageForm::getTabs(),
            Page::TYPE_GUIDE_OVERVIEW => GuideOverviewPageForm::getTabs(),
            Page::TYPE_GUIDE => GuidePageForm::getTabs(),
            Page::TYPE_SEO => SeoPageForm::getTabs(),
            Page::TYPE_SEA => SeaPageForm::getTabs(),
            Page::TYPE_LOCAL => LocalPageForm::getTabs(),
            Page::TYPE_LOCAL_HUB => LocalHubPageForm::getTabs(),
            Page::TYPE_MAINTENANCE => MaintenancePageForm::getTabs(),
            default => [],
        };

        return array_merge($baseTabs, $contentTabs, [
            self::getSeoTab(),
            self::getSettingsTab(),
        ]);
    }

    private static function getGeneralTab(): Tab
    {
        return Tab::make('Allgemein')
            ->icon(Heroicon::OutlinedCog6Tooth)
            ->schema([
                Section::make('Grundeinstellungen')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Seitentitel')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state, Get $get) {
                                if (! $get('slug')) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('URL-Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('z.B. "digitale-plattformen" für /loesungen/digitale-plattformen'),

                        Select::make('type')
                            ->label('Seitentyp')
                            ->required()
                            ->options(Page::getTypes())
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('content', []))
                            ->helperText('Der Seitentyp bestimmt die verfügbaren Inhaltsfelder'),

                        Select::make('parent_id')
                            ->label('Uebergeordnete Seite')
                            ->options(fn () => Page::active()
                                ->whereIn('type', [Page::TYPE_SOLUTIONS, Page::TYPE_SOLUTION_HUB])
                                ->pluck('title', 'id'))
                            ->searchable()
                            ->nullable()
                            ->helperText('Fuer hierarchische Struktur: waehle die uebergeordnete Seite')
                            ->visible(fn (Get $get) => in_array($get('type'), [
                                Page::TYPE_SOLUTION_HUB,
                                Page::TYPE_SOLUTION_DETAIL,
                            ])),

                        TextInput::make('sort_order')
                            ->label('Sortierung')
                            ->numeric()
                            ->default(0)
                            ->helperText('Niedrigere Zahlen werden zuerst angezeigt'),
                    ]),
            ]);
    }

    private static function getSeoTab(): Tab
    {
        return Tab::make('SEO')
            ->icon(Heroicon::OutlinedMagnifyingGlass)
            ->schema([
                Section::make('Suchmaschinenoptimierung')
                    ->columnSpanFull()
                    ->description('Diese Daten werden für Google und Social Media verwendet')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta-Titel')
                            ->maxLength(60)
                            ->helperText('Leer lassen für automatische Übernahme des Seitentitels (max. 60 Zeichen)'),

                        Textarea::make('meta_description')
                            ->label('Meta-Beschreibung')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Kurze Beschreibung für Suchergebnisse (max. 160 Zeichen)'),
                    ]),
            ]);
    }

    private static function getSettingsTab(): Tab
    {
        return Tab::make('Einstellungen')
            ->icon(Heroicon::OutlinedWrenchScrewdriver)
            ->schema([
                Section::make('Veröffentlichung')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Seite aktiv')
                            ->default(true)
                            ->helperText('Inaktive Seiten sind nicht öffentlich sichtbar'),
                    ]),
            ]);
    }
}
