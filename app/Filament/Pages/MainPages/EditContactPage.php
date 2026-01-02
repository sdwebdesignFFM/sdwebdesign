<?php

namespace App\Filament\Pages\MainPages;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\ContactPageForm;
use App\Models\Page;

class EditContactPage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Kontakt';

    protected static ?string $title = 'Kontakt bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Hauptseiten';

    protected static ?int $navigationSort = 3;

    protected static function getPageType(): string
    {
        return Page::TYPE_CONTACT;
    }

    protected function getContentTabs(): array
    {
        return ContactPageForm::getTabs();
    }
}
