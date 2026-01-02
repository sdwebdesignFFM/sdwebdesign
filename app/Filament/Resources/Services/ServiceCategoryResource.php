<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Pages\Schemas\HubPageForm;
use App\Filament\Resources\Services\ServiceCategoryResource\Pages;
use App\Models\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class ServiceCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Leistungskategorien';

    protected static \UnitEnum|string|null $navigationGroup = 'Leistungen';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Leistungskategorie';

    protected static ?string $pluralModelLabel = 'Leistungskategorien';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', Page::TYPE_SOLUTION_HUB)
            ->orderBy('sort_order');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs(HubPageForm::getTabs())
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Reihenfolge')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCategories::route('/'),
            'edit' => Pages\EditServiceCategory::route('/{record}/edit'),
        ];
    }
}
