<?php

namespace App\Filament\Resources\Quotes\ServiceCatalogResource\Pages;

use App\Filament\Resources\Quotes\ServiceCatalogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceCatalog extends EditRecord
{
    protected static string $resource = ServiceCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
