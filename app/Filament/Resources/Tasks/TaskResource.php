<?php

namespace App\Filament\Resources\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Models\Client;
use App\Models\Task;
use App\Models\WorkLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static \UnitEnum|string|null $navigationGroup = 'Zeiterfassung';

    protected static ?string $navigationLabel = 'Aufgaben';

    protected static ?int $navigationSort = 0;

    protected static ?string $modelLabel = 'Aufgabe';

    protected static ?string $pluralModelLabel = 'Aufgaben';

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
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->label('Titel')
                    ->placeholder('z.B. Website-Update durchführen')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                RichEditor::make('description')
                    ->label('Beschreibung')
                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                    ->columnSpanFull(),

                Select::make('estimated_minutes')
                    ->label('Geschätzte Zeit')
                    ->options(Task::getDurationOptions())
                    ->searchable()
                    ->placeholder('Keine Schätzung'),

                DatePicker::make('due_date')
                    ->label('Fälligkeitsdatum'),

                Select::make('priority')
                    ->label('Priorität')
                    ->options(TaskPriority::class)
                    ->default(TaskPriority::Normal)
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options(TaskStatus::class)
                    ->default(TaskStatus::Pending)
                    ->required(),

                Fieldset::make('Wiederkehrend')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_recurring')
                            ->label('Wiederkehrend')
                            ->live()
                            ->columnSpanFull(),

                        Select::make('recurrence_rule.interval')
                            ->label('Intervall')
                            ->options([
                                'daily' => 'Täglich',
                                'weekly' => 'Wöchentlich',
                                'monthly' => 'Monatlich',
                            ])
                            ->visible(fn (Get $get) => $get('is_recurring')),

                        Select::make('recurrence_rule.every')
                            ->label('Alle')
                            ->options([
                                1 => '1',
                                2 => '2',
                                3 => '3',
                                4 => '4',
                            ])
                            ->suffix(fn (Get $get) => match ($get('recurrence_rule.interval')) {
                                'daily' => 'Tage',
                                'weekly' => 'Wochen',
                                'monthly' => 'Monate',
                                default => '',
                            })
                            ->default(1)
                            ->visible(fn (Get $get) => $get('is_recurring')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('priority')
                    ->label('Priorität')
                    ->badge()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn (Task $record) => $record->title),

                TextColumn::make('client.display_name')
                    ->label('Kunde')
                    ->searchable(['clients.company', 'clients.last_name'])
                    ->placeholder('—'),

                TextColumn::make('due_date')
                    ->label('Fällig')
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(fn (Task $record) => match (true) {
                        $record->isOverdue() => 'danger',
                        $record->isDueToday() => 'warning',
                        default => null,
                    })
                    ->weight(fn (Task $record) => $record->isOverdue() || $record->isDueToday() ? 'bold' : null)
                    ->placeholder('—'),

                TextColumn::make('progress')
                    ->label('Fortschritt')
                    ->getStateUsing(function (Task $record) {
                        if ($record->estimated_minutes === null) {
                            return $record->total_logged_formatted;
                        }

                        return $record->total_logged_formatted.' / '.$record->estimated_formatted;
                    })
                    ->description(fn (Task $record) => $record->progress_percentage !== null
                        ? $record->progress_percentage.'%'
                        : null
                    )
                    ->alignEnd(),
            ])
            ->defaultSort('due_date', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(TaskStatus::class)
                    ->default(null),

                SelectFilter::make('priority')
                    ->label('Priorität')
                    ->options(TaskPriority::class),

                SelectFilter::make('client_id')
                    ->label('Kunde')
                    ->relationship('client', 'company')
                    ->getOptionLabelFromRecordUsing(fn (Client $record): string => $record->display_name)
                    ->searchable()
                    ->preload(),

                SelectFilter::make('due_filter')
                    ->label('Fälligkeit')
                    ->options([
                        'overdue' => 'Überfällig',
                        'today' => 'Heute',
                        'this_week' => 'Diese Woche',
                        'no_date' => 'Ohne Datum',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value']) {
                            'overdue' => $query->overdue(),
                            'today' => $query->dueToday(),
                            'this_week' => $query->dueSoon(7),
                            'no_date' => $query->whereNull('due_date'),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                Action::make('logTime')
                    ->label('Zeit erfassen')
                    ->icon('heroicon-o-clock')
                    ->color('success')
                    ->modalHeading('Zeit erfassen')
                    ->modalWidth('md')
                    ->form([
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
                            ->label('Beschreibung der Arbeit')
                            ->placeholder('Was wurde erledigt?')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (Task $record, array $data) {
                        WorkLog::create([
                            'client_id' => $record->client_id,
                            'task_id' => $record->id,
                            'worked_on' => $data['worked_on'],
                            'title' => $data['title'],
                            'duration_minutes' => $data['duration_minutes'],
                            'is_billed' => false,
                        ]);

                        // Auto-update status to in_progress if pending
                        $record->markAsInProgress();

                        Notification::make()
                            ->success()
                            ->title('Zeit erfasst')
                            ->body($data['title'])
                            ->send();
                    })
                    ->visible(fn (Task $record) => $record->status->isOpen()),

                Action::make('complete')
                    ->label('Erledigt')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aufgabe als erledigt markieren?')
                    ->action(function (Task $record) {
                        $record->markAsCompleted();

                        Notification::make()
                            ->success()
                            ->title('Aufgabe erledigt')
                            ->send();
                    })
                    ->visible(fn (Task $record) => $record->status->isOpen()),

                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (Task $record) => $record->status !== TaskStatus::Completed),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorkLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::open()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        // Check if there are overdue or due today tasks
        $urgentCount = static::getModel()::where(function ($query) {
            $query->whereNotNull('due_date')
                ->where('due_date', '<', now()->startOfDay())
                ->whereIn('status', [TaskStatus::Pending, TaskStatus::InProgress]);
        })->orWhere(function ($query) {
            $query->whereNotNull('due_date')
                ->whereDate('due_date', now()->toDateString())
                ->whereIn('status', [TaskStatus::Pending, TaskStatus::InProgress]);
        })->count();

        return $urgentCount > 0 ? 'danger' : 'info';
    }
}
