<?php

namespace App\Filament\Resources\Guides\GuideCategoryResource\Pages;

use App\Filament\Resources\Guides\GuideCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditGuideCategory extends EditRecord
{
    use Translatable;

    protected static string $resource = GuideCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
