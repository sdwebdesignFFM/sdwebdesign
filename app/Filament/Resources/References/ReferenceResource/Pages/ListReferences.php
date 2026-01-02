<?php

namespace App\Filament\Resources\References\ReferenceResource\Pages;

use App\Filament\Resources\References\ReferenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListReferences extends ListRecords
{
    use Translatable;

    protected static string $resource = ReferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
