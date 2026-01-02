<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Pages\Schemas\SolutionDetailPageForm;
use App\Filament\Resources\Services\ServiceDetailResource\Pages;
use App\Models\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class ServiceDetailResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Leistungsseiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Leistungen';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Leistungsseite';

    protected static ?string $pluralModelLabel = 'Leistungsseiten';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->orderBy('parent_id')
            ->orderBy('sort_order');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs(SolutionDetailPageForm::getTabs())
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parent.title')
                    ->label('Kategorie')
                    ->sortable()
                    ->badge(),
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
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Kategorie')
                    ->relationship('parent', 'title', fn (Builder $query) => $query->where('type', Page::TYPE_SOLUTION_HUB))
                    ->preload(),
            ])
            ->defaultSort('parent_id')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceDetails::route('/'),
            'edit' => Pages\EditServiceDetail::route('/{record}/edit'),
        ];
    }
}
