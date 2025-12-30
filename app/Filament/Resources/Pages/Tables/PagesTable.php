<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Page::getTypes()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'home' => 'success',
                        'solutions', 'solution-detail' => 'info',
                        'references' => 'warning',
                        'about' => 'primary',
                        'contact' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('slug')
                    ->label('URL')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Typ')
                    ->options(Page::getTypes()),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktiv',
                        '0' => 'Inaktiv',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('type');
    }
}
