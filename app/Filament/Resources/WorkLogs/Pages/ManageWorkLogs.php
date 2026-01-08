<?php

namespace App\Filament\Resources\WorkLogs\Pages;

use App\Filament\Resources\WorkLogs\WorkLogResource;
use App\Filament\Widgets\UpcomingTasksWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWorkLogs extends ManageRecords
{
    protected static string $resource = WorkLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UpcomingTasksWidget::class,
        ];
    }
}
