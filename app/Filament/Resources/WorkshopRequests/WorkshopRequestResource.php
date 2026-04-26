<?php

namespace App\Filament\Resources\WorkshopRequests;

use App\Filament\Resources\WorkshopRequests\Pages\ListWorkshopRequests;
use App\Filament\Resources\WorkshopRequests\Pages\ViewWorkshopRequest;
use App\Models\WorkshopRequest;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorkshopRequestResource extends Resource
{
    protected static ?string $model = WorkshopRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static \UnitEnum|string|null $navigationGroup = 'Leads';

    protected static ?string $navigationLabel = 'Workshop-Anfragen';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Workshop-Anfrage';

    protected static ?string $pluralModelLabel = 'Workshop-Anfragen';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Eingegangen')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('company')
                    ->label('Unternehmen')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->placeholder('—')
                    ->copyable(),
                TextColumn::make('procurement_stage')
                    ->label('Stand')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('budget_indication')
                    ->label('Budget')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('preferred_timing')
                    ->label('Termin')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('admin_notified_at')
                    ->label('Admin-Mail')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('— nicht versendet —')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('procurement_stage')
                    ->label('Stand der Recherche')
                    ->options(fn () => WorkshopRequest::query()
                        ->whereNotNull('procurement_stage')
                        ->distinct()
                        ->pluck('procurement_stage', 'procurement_stage')
                        ->toArray()),
                SelectFilter::make('budget_indication')
                    ->label('Budget')
                    ->options(fn () => WorkshopRequest::query()
                        ->whereNotNull('budget_indication')
                        ->distinct()
                        ->pluck('budget_indication', 'budget_indication')
                        ->toArray()),
                SelectFilter::make('workshop_format')
                    ->label('Format')
                    ->options(fn () => WorkshopRequest::query()
                        ->whereNotNull('workshop_format')
                        ->distinct()
                        ->pluck('workshop_format', 'workshop_format')
                        ->toArray()),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('reply')
                    ->label('Antworten')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('gray')
                    ->url(fn (WorkshopRequest $record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Re: Ihre Workshop-Anfrage Plattform-Discovery')),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Anfrager')
                ->columns(2)
                ->schema([
                    TextEntry::make('name')->label('Name'),
                    TextEntry::make('role')->label('Rolle')->placeholder('—'),
                    TextEntry::make('email')->label('E-Mail')->copyable(),
                    TextEntry::make('phone')->label('Telefon')->copyable()->placeholder('—'),
                    TextEntry::make('company')->label('Unternehmen')->placeholder('—'),
                    TextEntry::make('company_size')->label('Unternehmensgröße')->placeholder('—'),
                ]),
            Section::make('Vorhaben')
                ->columns(2)
                ->schema([
                    TextEntry::make('industry')->label('Branche')->placeholder('—'),
                    TextEntry::make('workflow_areas')
                        ->label('Workflow-Bereiche')
                        ->badge()
                        ->placeholder('—'),
                    TextEntry::make('trigger_question')
                        ->label('Anlass / Ausgangsfrage')
                        ->columnSpanFull()
                        ->placeholder('—'),
                ]),
            Section::make('Stand & Bestand')
                ->columns(2)
                ->schema([
                    TextEntry::make('existing_systems')
                        ->label('Bestandssysteme')
                        ->badge()
                        ->placeholder('—'),
                    TextEntry::make('procurement_stage')->label('Stand der Recherche')->badge()->placeholder('—'),
                    TextEntry::make('budget_indication')->label('Budget')->badge()->placeholder('—'),
                    TextEntry::make('go_live_timeline')->label('Wann produktiv')->placeholder('—'),
                ]),
            Section::make('Workshop-Format')
                ->columns(3)
                ->schema([
                    TextEntry::make('workshop_format')->label('Format')->badge()->placeholder('—'),
                    TextEntry::make('preferred_timing')->label('Termin-Wunsch')->placeholder('—'),
                    TextEntry::make('preferred_daytime')->label('Tageszeit')->placeholder('—'),
                ]),
            Section::make('Vorab-Briefing')
                ->visible(fn (WorkshopRequest $record) => filled($record->briefing_notes))
                ->schema([
                    TextEntry::make('briefing_notes')->label('Notizen')->columnSpanFull(),
                ]),
            Section::make('Meta')
                ->columns(3)
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Eingegangen')->dateTime('d.m.Y H:i'),
                    TextEntry::make('admin_notified_at')->label('Admin-Mail versendet')->dateTime('d.m.Y H:i')->placeholder('—'),
                    TextEntry::make('confirmation_sent_at')->label('Bestätigung versendet')->dateTime('d.m.Y H:i')->placeholder('—'),
                    TextEntry::make('ip')->label('IP-Adresse')->placeholder('—'),
                    TextEntry::make('locale')->label('Sprache'),
                    TextEntry::make('workshop_slug')->label('Workshop'),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkshopRequests::route('/'),
            'view' => ViewWorkshopRequest::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) WorkshopRequest::query()->whereNull('admin_notified_at')->count() ?: null;
    }
}
