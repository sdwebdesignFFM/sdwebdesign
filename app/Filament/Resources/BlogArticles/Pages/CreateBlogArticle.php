<?php

namespace App\Filament\Resources\BlogArticles\Pages;

use App\Filament\Resources\BlogArticles\BlogArticleResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateBlogArticle extends CreateRecord
{
    use Translatable;

    protected static string $resource = BlogArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
