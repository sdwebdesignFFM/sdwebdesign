<?php

namespace App\Filament\Pages\LocalPages;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\LocalHubPageForm;
use App\Models\Page;

class EditLocalHub extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Übersicht';

    protected static ?string $title = 'Lokale Expertise bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Lokale Seiten';

    protected static ?int $navigationSort = 1;

    protected static function getPageType(): string
    {
        return Page::TYPE_LOCAL_HUB;
    }

    protected function getContentTabs(): array
    {
        return LocalHubPageForm::getTabs();
    }
}
