<?php

namespace App\Filament\Resources\Services\ServiceCategoryResource\Pages;

use App\Filament\Resources\Services\ServiceCategoryResource;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditServiceCategory extends EditRecord
{
    use Translatable;

    protected static string $resource = ServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
