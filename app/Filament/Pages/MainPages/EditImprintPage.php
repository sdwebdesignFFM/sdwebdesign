<?php

namespace App\Filament\Pages\MainPages;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\LegalPageForm;
use App\Models\Page;

class EditImprintPage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Impressum';

    protected static ?string $title = 'Impressum bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Hauptseiten';

    protected static ?int $navigationSort = 4;

    protected static function getPageType(): string
    {
        return Page::TYPE_IMPRINT;
    }

    protected function getContentTabs(): array
    {
        return LegalPageForm::getTabs();
    }
}
