<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Tasks\TaskResource;
use App\Models\Task;
use App\Models\WorkLog;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingTasksWidget extends TableWidget
{
    protected static ?string $heading = 'Anstehende Aufgaben';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Task::query()
                ->open()
                ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
                ->orderBy('due_date')
                ->limit(5)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('priority')
                    ->label('')
                    ->badge()
                    ->grow(false),

                TextColumn::make('title')
                    ->label('Aufgabe')
                    ->limit(40)
                    ->url(fn (Task $record): string => TaskResource::getUrl('edit', ['record' => $record])),

                TextColumn::make('client.display_name')
                    ->label('Kunde')
                    ->placeholder('—'),

                TextColumn::make('due_date')
                    ->label('Fällig')
                    ->date('d.m.Y')
                    ->color(fn (Task $record) => match (true) {
                        $record->isOverdue() => 'danger',
                        $record->isDueToday() => 'warning',
                        default => null,
                    })
                    ->weight(fn (Task $record) => $record->isOverdue() || $record->isDueToday() ? 'bold' : null)
                    ->placeholder('—'),

                TextColumn::make('progress')
                    ->label('Fortschritt')
                    ->getStateUsing(fn (Task $record) => $record->estimated_minutes !== null
                        ? $record->total_logged_formatted.' / '.$record->estimated_formatted
                        : $record->total_logged_formatted
                    )
                    ->alignEnd(),
            ])
            ->recordActions([
                Action::make('logTime')
                    ->label('Zeit erfassen')
                    ->icon('heroicon-o-clock')
                    ->color('success')
                    ->size('sm')
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
                            ->label('Beschreibung')
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

                        $record->markAsInProgress();

                        Notification::make()
                            ->success()
                            ->title('Zeit erfasst')
                            ->body($data['title'])
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Keine offenen Aufgaben')
            ->emptyStateDescription('Erstelle eine neue Aufgabe um loszulegen.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}
