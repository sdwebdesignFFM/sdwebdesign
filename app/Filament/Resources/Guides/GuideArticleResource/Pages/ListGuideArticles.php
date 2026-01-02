<?php

namespace App\Filament\Resources\Guides\GuideArticleResource\Pages;

use App\Filament\Resources\Guides\GuideArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListGuideArticles extends ListRecords
{
    use Translatable;

    protected static string $resource = GuideArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
