<?php

namespace App\Filament\Pages\Guides;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\GuideOverviewPageForm;
use App\Models\Page;

class EditGuidesOverview extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Übersicht';

    protected static ?string $title = 'Ratgeber-Übersicht bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Ratgeber';

    protected static ?int $navigationSort = 1;

    protected static function getPageType(): string
    {
        return Page::TYPE_GUIDE_OVERVIEW;
    }

    protected function getContentTabs(): array
    {
        return GuideOverviewPageForm::getTabs();
    }
}
