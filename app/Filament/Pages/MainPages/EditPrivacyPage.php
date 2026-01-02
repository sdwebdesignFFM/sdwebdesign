<?php

namespace App\Filament\Pages\MainPages;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\LegalPageForm;
use App\Models\Page;

class EditPrivacyPage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Datenschutz';

    protected static ?string $title = 'Datenschutz bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Hauptseiten';

    protected static ?int $navigationSort = 5;

    protected static function getPageType(): string
    {
        return Page::TYPE_PRIVACY;
    }

    protected function getContentTabs(): array
    {
        return LegalPageForm::getTabs();
    }
}
