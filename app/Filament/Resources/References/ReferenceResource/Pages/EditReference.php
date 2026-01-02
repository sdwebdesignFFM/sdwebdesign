<?php

namespace App\Filament\Resources\References\ReferenceResource\Pages;

use App\Filament\Resources\References\ReferenceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditReference extends EditRecord
{
    use Translatable;

    protected static string $resource = ReferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
