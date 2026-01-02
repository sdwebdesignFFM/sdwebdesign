<?php

namespace App\Filament\Resources\LocalPages\CityPageResource\Pages;

use App\Filament\Resources\LocalPages\CityPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListCityPages extends ListRecords
{
    use Translatable;

    protected static string $resource = CityPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
