<?php

namespace App\Filament\Resources\Services\ServiceCategoryResource\Pages;

use App\Filament\Resources\Services\ServiceCategoryResource;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListServiceCategories extends ListRecords
{
    use Translatable;

    protected static string $resource = ServiceCategoryResource::class;
}
