<?php

namespace App\Filament\Resources\Guides\GuideCategoryResource\Pages;

use App\Filament\Resources\Guides\GuideCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateGuideCategory extends CreateRecord
{
    use Translatable;

    protected static string $resource = GuideCategoryResource::class;
}
