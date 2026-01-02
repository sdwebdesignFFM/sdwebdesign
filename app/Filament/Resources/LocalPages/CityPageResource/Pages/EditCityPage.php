<?php

namespace App\Filament\Resources\LocalPages\CityPageResource\Pages;

use App\Filament\Resources\LocalPages\CityPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditCityPage extends EditRecord
{
    use Translatable;

    protected static string $resource = CityPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
