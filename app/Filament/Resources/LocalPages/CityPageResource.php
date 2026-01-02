<?php

namespace App\Filament\Resources\LocalPages;

use App\Filament\Resources\LocalPages\CityPageResource\Pages;
use App\Filament\Resources\Pages\Schemas\LocalPageForm;
use App\Models\Page;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class CityPageResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Städteseiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Lokale Seiten';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Städteseite';

    protected static ?string $pluralModelLabel = 'Städteseiten';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', Page::TYPE_LOCAL)
            ->orderBy('title');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs(LocalPageForm::getTabs())
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Stadt')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('content.city')
                    ->label('City-Variable'),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->defaultSort('title')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCityPages::route('/'),
            'create' => Pages\CreateCityPage::route('/create'),
            'edit' => Pages\EditCityPage::route('/{record}/edit'),
        ];
    }
}
