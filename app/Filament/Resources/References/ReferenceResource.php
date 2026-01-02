<?php

namespace App\Filament\Resources\References;

use App\Filament\Resources\References\ReferenceResource\Pages;
use App\Models\Page;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class ReferenceResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Projekte';

    protected static \UnitEnum|string|null $navigationGroup = 'Referenzen';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Referenz';

    protected static ?string $pluralModelLabel = 'Referenzen';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', Page::TYPE_REFERENCE_DETAIL)
            ->orderBy('sort_order');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Allgemein')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Grunddaten')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Projekttitel')
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('slug')
                                            ->label('URL-Slug')
                                            ->required(),
                                        TextInput::make('sort_order')
                                            ->label('Reihenfolge')
                                            ->numeric()
                                            ->default(0),
                                        Toggle::make('is_active')
                                            ->label('Veröffentlicht')
                                            ->default(true),
                                    ]),
                            ]),
                        Tab::make('Inhalt')
                            ->icon('heroicon-o-pencil-square')
                            ->schema([
                                Section::make('Projektbeschreibung')
                                    ->schema([
                                        Textarea::make('content.excerpt')
                                            ->label('Kurzbeschreibung')
                                            ->rows(3),
                                        TextInput::make('content.description.title')
                                            ->label('Überschrift'),
                                        Textarea::make('content.description.text')
                                            ->label('Beschreibungstext')
                                            ->rows(5),
                                    ]),
                                Section::make('Projektdetails')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('content.client')
                                            ->label('Kunde'),
                                        TextInput::make('content.industry')
                                            ->label('Branche'),
                                        TextInput::make('content.year')
                                            ->label('Jahr'),
                                        TextInput::make('content.url')
                                            ->label('Projekt-URL')
                                            ->url(),
                                    ]),
                            ]),
                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('Suchmaschinenoptimierung')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta-Titel')
                                            ->maxLength(60),
                                        Textarea::make('meta_description')
                                            ->label('Meta-Beschreibung')
                                            ->rows(3)
                                            ->maxLength(160),
                                    ]),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Projekt')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('content.client')
                    ->label('Kunde')
                    ->searchable(),
                TextColumn::make('content.year')
                    ->label('Jahr'),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferences::route('/'),
            'create' => Pages\CreateReference::route('/create'),
            'edit' => Pages\EditReference::route('/{record}/edit'),
        ];
    }
}
