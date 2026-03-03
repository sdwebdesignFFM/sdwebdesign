<?php

namespace App\Filament\Resources\WorkLogs;

use App\Filament\Resources\WorkLogs\Pages\ManageWorkLogs;
use App\Models\Client;
use App\Models\Task;
use App\Models\WorkLog;
use App\Services\WorkLog\WorkLogService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class WorkLogResource extends Resource
{
    protected static ?string $model = WorkLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static \UnitEnum|string|null $navigationGroup = 'Zeiterfassung';

    protected static ?string $navigationLabel = 'Arbeitszeiten';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Arbeitszeit';

    protected static ?string $pluralModelLabel = 'Arbeitszeiten';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('client_id')
                    ->label('Kunde')
                    ->relationship('client', 'company')
                    ->getOptionLabelFromRecordUsing(fn (Client $record): string => $record->display_name)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set) => $set('task_id', null)),

                Select::make('task_id')
                    ->label('Aufgabe')
                    ->placeholder('Keine Aufgabe')
                    ->options(function (Get $get) {
                        $clientId = $get('client_id');
                        if (! $clientId) {
                            return Task::query()
                                ->open()
                                ->whereNull('client_id')
                                ->pluck('title', 'id');
                        }

                        return Task::query()
                            ->open()
                            ->where(function ($query) use ($clientId) {
                                $query->where('client_id', $clientId)
                                    ->orWhereNull('client_id');
                            })
                            ->pluck('title', 'id');
                    })
                    ->searchable()
                    ->preload(),

                DatePicker::make('worked_on')
                    ->label('Datum')
                    ->default(now())
                    ->required(),

                Select::make('duration_minutes')
                    ->label('Dauer')
                    ->options(WorkLog::getDurationOptions())
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('Titel')
                    ->placeholder('z.B. Blogbeitrag angelegt')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Beschreibung')
                    ->placeholder('Optionale Details zur Arbeit...')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('worked_on')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('client.display_name')
                    ->label('Kunde')
                    ->searchable(['clients.company', 'clients.last_name'])
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn (WorkLog $record) => $record->description),

                TextColumn::make('task.title')
                    ->label('Aufgabe')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('duration_formatted')
                    ->label('Dauer')
                    ->alignEnd(),

                IconColumn::make('is_billed')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->tooltip(fn (WorkLog $record) => $record->is_billed ? 'Abgerechnet' : 'Offen'),
            ])
            ->defaultSort('worked_on', 'desc')
            ->filters([
                SelectFilter::make('client_id')
                    ->label('Kunde')
                    ->relationship('client', 'company')
                    ->getOptionLabelFromRecordUsing(fn (Client $record): string => $record->display_name)
                    ->searchable()
                    ->preload(),

                SelectFilter::make('task_id')
                    ->label('Aufgabe')
                    ->relationship('task', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('month')
                    ->label('Monat')
                    ->options(function () {
                        $months = [];
                        for ($i = 0; $i < 12; $i++) {
                            $date = now()->subMonths($i);
                            $key = $date->format('Y-m');
                            $months[$key] = $date->translatedFormat('F Y');
                        }

                        return $months;
                    })
                    ->query(function ($query, array $data) {
                        if (! $data['value']) {
                            return $query;
                        }
                        [$year, $month] = explode('-', $data['value']);

                        return $query->whereYear('worked_on', $year)
                            ->whereMonth('worked_on', $month);
                    }),

                TernaryFilter::make('is_billed')
                    ->label('Status')
                    ->placeholder('Alle')
                    ->trueLabel('Abgerechnet')
                    ->falseLabel('Offen'),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (WorkLog $record) => ! $record->is_billed),
                ViewAction::make()
                    ->visible(fn (WorkLog $record) => $record->is_billed),
                Action::make('mark_as_billed')
                    ->label('Als abgerechnet markieren')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Als abgerechnet markieren')
                    ->modalDescription('Soll dieser Eintrag als abgerechnet markiert werden (ohne Rechnungserstellung)?')
                    ->visible(fn (WorkLog $record) => ! $record->is_billed)
                    ->action(function (WorkLog $record) {
                        app(WorkLogService::class)->markAsBilled([$record->id]);

                        Notification::make()
                            ->title('Als abgerechnet markiert')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make()
                    ->visible(fn (WorkLog $record) => ! $record->is_billed),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_as_billed')
                        ->label('Als abgerechnet markieren')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Ausgewählte als abgerechnet markieren')
                        ->modalDescription('Sollen die ausgewählten Einträge als abgerechnet markiert werden (ohne Rechnungserstellung)?')
                        ->action(function (Collection $records) {
                            $unbilledIds = $records->filter(fn ($record) => ! $record->is_billed)->pluck('id')->toArray();

                            if (empty($unbilledIds)) {
                                Notification::make()
                                    ->title('Keine offenen Einträge ausgewählt')
                                    ->warning()
                                    ->send();

                                return;
                            }

                            $count = app(WorkLogService::class)->markAsBilled($unbilledIds);

                            Notification::make()
                                ->title("{$count} Einträge als abgerechnet markiert")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Only allow deleting unbilled entries
                            return $records->filter(fn ($record) => ! $record->is_billed);
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWorkLogs::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::unbilled()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
