<?php

namespace App\Filament\Resources\Quotes\ServiceCatalogResource\Pages;

use App\Filament\Resources\Quotes\ServiceCatalogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceCatalog extends CreateRecord
{
    protected static string $resource = ServiceCatalogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
