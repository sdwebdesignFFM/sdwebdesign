<?php

namespace App\Filament\Resources\WhitepaperLeads;

use App\Filament\Resources\WhitepaperLeads\Pages\ListWhitepaperLeads;
use App\Models\WhitepaperLead;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WhitepaperLeadResource extends Resource
{
    protected static ?string $model = WhitepaperLead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static \UnitEnum|string|null $navigationGroup = 'Leads';

    protected static ?string $navigationLabel = 'Whitepaper-Leads';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Whitepaper-Lead';

    protected static ?string $pluralModelLabel = 'Whitepaper-Leads';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Eingegangen')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable()
                    ->copyable()
                    ->weight('medium'),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('company')
                    ->label('Unternehmen')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('role')
                    ->label('Rolle')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('whitepaper_slug')
                    ->label('Whitepaper')
                    ->badge(),
                IconColumn::make('newsletter_opt_in')
                    ->label('Newsletter')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedMinus),
                TextColumn::make('sent_at')
                    ->label('PDF versendet')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('whitepaper_slug')
                    ->label('Whitepaper')
                    ->options(fn () => WhitepaperLead::query()
                        ->distinct()
                        ->pluck('whitepaper_slug', 'whitepaper_slug')
                        ->toArray()),
                Filter::make('newsletter_opt_in')
                    ->label('Nur Newsletter-Opt-Ins')
                    ->query(fn (Builder $q) => $q->where('newsletter_opt_in', true))
                    ->toggle(),
            ])
            ->recordActions([
                Action::make('reply')
                    ->label('Antworten')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('gray')
                    ->url(fn (WhitepaperLead $record) => 'mailto:'.$record->email),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWhitepaperLeads::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
