<?php

namespace App\Filament\Resources\Quotes\ServiceCatalogResource\Pages;

use App\Filament\Resources\Quotes\ServiceCatalogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceCatalogs extends ListRecords
{
    protected static string $resource = ServiceCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
