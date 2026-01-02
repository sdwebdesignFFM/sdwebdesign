<?php

namespace App\Filament\Pages\MainPages;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\AboutPageForm;
use App\Models\Page;

class EditAboutPage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Über uns';

    protected static ?string $title = 'Über uns bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Hauptseiten';

    protected static ?int $navigationSort = 2;

    protected static function getPageType(): string
    {
        return Page::TYPE_ABOUT;
    }

    protected function getContentTabs(): array
    {
        return AboutPageForm::getTabs();
    }
}
