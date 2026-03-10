<?php

namespace App\Filament\Resources\Tasks\RelationManagers;

use App\Models\WorkLog;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'workLogs';

    protected static ?string $title = 'Arbeitseinträge';

    protected static \BackedEnum|string|null $icon = 'heroicon-o-clock';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
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

                RichEditor::make('description')
                    ->label('Beschreibung')
                    ->placeholder('Was wurde erledigt?')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'link',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('worked_on')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Titel')
                    ->limit(50),

                TextColumn::make('description')
                    ->label('Beschreibung')
                    ->html()
                    ->limit(80)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('duration_formatted')
                    ->label('Dauer')
                    ->alignEnd(),
            ])
            ->defaultSort('worked_on', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Arbeitseintrag hinzufügen')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['client_id'] = $this->getOwnerRecord()->client_id;
                        $data['is_billed'] = false;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (WorkLog $record) => ! $record->is_billed),
                DeleteAction::make()
                    ->visible(fn (WorkLog $record) => ! $record->is_billed),
            ])
            ->emptyStateHeading('Keine Arbeitseinträge')
            ->emptyStateDescription('Füge einen neuen Arbeitseintrag hinzu.')
            ->emptyStateIcon('heroicon-o-clock');
    }
}
