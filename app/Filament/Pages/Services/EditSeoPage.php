<?php

namespace App\Filament\Pages\Services;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\SeoPageForm;
use App\Models\Page;

class EditSeoPage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'SEO';

    protected static ?string $title = 'Suchmaschinenoptimierung bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Leistungen';

    protected static ?int $navigationSort = 5;

    protected static function getPageType(): string
    {
        return Page::TYPE_SEO;
    }

    protected function getContentTabs(): array
    {
        return SeoPageForm::getTabs();
    }
}
