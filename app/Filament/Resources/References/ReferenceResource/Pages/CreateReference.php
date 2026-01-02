<?php

namespace App\Filament\Resources\References\ReferenceResource\Pages;

use App\Filament\Resources\References\ReferenceResource;
use App\Models\Page;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateReference extends CreateRecord
{
    use Translatable;

    protected static string $resource = ReferenceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = Page::TYPE_REFERENCE_DETAIL;

        return $data;
    }
}
