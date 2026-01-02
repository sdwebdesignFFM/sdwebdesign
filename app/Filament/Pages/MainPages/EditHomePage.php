<?php

namespace App\Filament\Pages\MainPages;

use App\Filament\Pages\EditSinglePage;
use App\Filament\Resources\Pages\Schemas\HomePageForm;
use App\Models\Page;

class EditHomePage extends EditSinglePage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Startseite';

    protected static ?string $title = 'Startseite bearbeiten';

    protected static \UnitEnum|string|null $navigationGroup = 'Hauptseiten';

    protected static ?int $navigationSort = 1;

    protected static function getPageType(): string
    {
        return Page::TYPE_HOME;
    }

    protected function getContentTabs(): array
    {
        return HomePageForm::getTabs();
    }
}
