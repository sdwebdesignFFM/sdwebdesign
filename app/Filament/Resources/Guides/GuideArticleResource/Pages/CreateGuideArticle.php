<?php

namespace App\Filament\Resources\Guides\GuideArticleResource\Pages;

use App\Filament\Resources\Guides\GuideArticleResource;
use App\Models\Page;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateGuideArticle extends CreateRecord
{
    use Translatable;

    protected static string $resource = GuideArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = Page::TYPE_GUIDE;

        return $data;
    }
}
