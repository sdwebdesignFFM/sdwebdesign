<?php

namespace App\Filament\Resources\Guides;

use App\Filament\Resources\Guides\GuideCategoryResource\Pages;
use App\Models\GuideCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class GuideCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = GuideCategory::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Kategorien';

    protected static \UnitEnum|string|null $navigationGroup = 'Ratgeber';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kategorie';

    protected static ?string $pluralModelLabel = 'Kategorien';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kategorie')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        TextInput::make('slug')
                            ->label('URL-Slug')
                            ->required(),
                        Textarea::make('description')
                            ->label('Beschreibung')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Reihenfolge')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug'),
                TextColumn::make('guides_count')
                    ->label('Artikel')
                    ->counts('guides'),
                TextColumn::make('sort_order')
                    ->label('Reihenfolge')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuideCategories::route('/'),
            'create' => Pages\CreateGuideCategory::route('/create'),
            'edit' => Pages\EditGuideCategory::route('/{record}/edit'),
        ];
    }
}
