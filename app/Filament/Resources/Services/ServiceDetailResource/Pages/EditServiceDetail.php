<?php

namespace App\Filament\Resources\Services\ServiceDetailResource\Pages;

use App\Filament\Resources\Services\ServiceDetailResource;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditServiceDetail extends EditRecord
{
    use Translatable;

    protected static string $resource = ServiceDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
