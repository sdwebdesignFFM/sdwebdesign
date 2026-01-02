<?php

namespace App\Filament\Resources\Services\ServiceDetailResource\Pages;

use App\Filament\Resources\Services\ServiceDetailResource;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListServiceDetails extends ListRecords
{
    use Translatable;

    protected static string $resource = ServiceDetailResource::class;
}
