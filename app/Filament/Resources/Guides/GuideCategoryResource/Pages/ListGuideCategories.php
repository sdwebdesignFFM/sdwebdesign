<?php

namespace App\Filament\Resources\Guides\GuideCategoryResource\Pages;

use App\Filament\Resources\Guides\GuideCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListGuideCategories extends ListRecords
{
    use Translatable;

    protected static string $resource = GuideCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
