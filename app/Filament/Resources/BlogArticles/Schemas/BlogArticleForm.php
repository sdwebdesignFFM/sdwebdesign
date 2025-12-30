<?php

namespace App\Filament\Resources\BlogArticles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Artikel-Informationen')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Titel')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        TextInput::make('slug')
                            ->label('URL-Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('category')
                            ->label('Kategorie')
                            ->required()
                            ->options([
                                'Digitale Systeme' => 'Digitale Systeme',
                                'Prozessautomatisierung' => 'Prozessautomatisierung',
                                'API-Integration' => 'API-Integration',
                                'E-Commerce' => 'E-Commerce',
                                'WordPress' => 'WordPress',
                                'Technologie' => 'Technologie',
                            ]),

                        TextInput::make('read_time')
                            ->label('Lesezeit (Minuten)')
                            ->numeric()
                            ->default(5)
                            ->required(),
                    ]),

                Section::make('Inhalt')
                    ->schema([
                        Textarea::make('excerpt')
                            ->label('Kurzbeschreibung')
                            ->required()
                            ->rows(3)
                            ->helperText('Wird in der Artikelübersicht angezeigt'),

                        Textarea::make('intro')
                            ->label('Einleitung')
                            ->required()
                            ->rows(5),

                        Repeater::make('sections')
                            ->label('Abschnitte')
                            ->schema([
                                TextInput::make('heading')
                                    ->label('Überschrift')
                                    ->required(),
                                Textarea::make('content')
                                    ->label('Inhalt')
                                    ->required()
                                    ->rows(6),
                            ])
                            ->addActionLabel('Abschnitt hinzufügen')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? null),

                        Textarea::make('conclusion')
                            ->label('Fazit')
                            ->required()
                            ->rows(5),
                    ]),

                Section::make('SEO')
                    ->columns(1)
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta-Titel')
                            ->maxLength(60)
                            ->helperText('Leer lassen für automatische Übernahme des Titels'),

                        Textarea::make('meta_description')
                            ->label('Meta-Beschreibung')
                            ->rows(2)
                            ->maxLength(160)
                            ->helperText('Leer lassen für automatische Übernahme der Kurzbeschreibung'),
                    ]),

                Section::make('Veröffentlichung')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Veröffentlicht')
                            ->default(false),

                        DateTimePicker::make('published_at')
                            ->label('Veröffentlichungsdatum')
                            ->native(false),
                    ]),
            ]);
    }
}
