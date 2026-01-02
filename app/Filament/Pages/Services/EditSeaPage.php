<?php

namespace App\Filament\Pages\Services;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\SeaPageForm;
use App\Models\Page;

class EditSeaPage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'SEA';

    protected static ?string $title = 'Suchmaschinenwerbung bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Leistungen';

    protected static ?int $navigationSort = 6;

    protected static function getPageType(): string
    {
        return Page::TYPE_SEA;
    }

    protected function getContentTabs(): array
    {
        return SeaPageForm::getTabs();
    }
}
