<?php

namespace App\Filament\Pages\Services;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\SolutionsPageForm;
use App\Models\Page;

class EditSolutionsOverview extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationLabel = 'Lösungsübersicht';

    protected static ?string $title = 'Lösungsübersicht bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Leistungen';

    protected static ?int $navigationSort = 1;

    protected static function getPageType(): string
    {
        return Page::TYPE_SOLUTIONS;
    }

    protected function getContentTabs(): array
    {
        return SolutionsPageForm::getTabs();
    }
}
