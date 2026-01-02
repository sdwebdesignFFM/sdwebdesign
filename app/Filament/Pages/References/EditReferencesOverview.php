<?php

namespace App\Filament\Pages\References;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\ReferencesPageForm;
use App\Models\Page;

class EditReferencesOverview extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Übersicht';

    protected static ?string $title = 'Referenzen-Übersicht bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Referenzen';

    protected static ?int $navigationSort = 1;

    protected static function getPageType(): string
    {
        return Page::TYPE_REFERENCES;
    }

    protected function getContentTabs(): array
    {
        return ReferencesPageForm::getTabs();
    }
}
