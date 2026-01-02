<?php

namespace App\Filament\Pages\Services;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\MaintenancePageForm;
use App\Models\Page;

class EditMaintenancePage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $navigationLabel = 'Betrieb & Wartung';

    protected static ?string $title = 'Betrieb & Wartung bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Leistungen';

    protected static ?int $navigationSort = 7;

    protected static function getPageType(): string
    {
        return Page::TYPE_MAINTENANCE;
    }

    protected function getContentTabs(): array
    {
        return MaintenancePageForm::getTabs();
    }
}
