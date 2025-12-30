<?php

namespace App\Filament\Resources\BlogArticles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BlogArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('category')
                    ->label('Kategorie')
                    ->badge()
                    ->sortable(),

                TextColumn::make('read_time')
                    ->label('Lesezeit')
                    ->suffix(' Min.')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Veröffentlicht')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->options([
                        'Digitale Systeme' => 'Digitale Systeme',
                        'Prozessautomatisierung' => 'Prozessautomatisierung',
                        'API-Integration' => 'API-Integration',
                        'E-Commerce' => 'E-Commerce',
                        'WordPress' => 'WordPress',
                        'Technologie' => 'Technologie',
                    ]),

                TernaryFilter::make('is_published')
                    ->label('Veröffentlicht'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
