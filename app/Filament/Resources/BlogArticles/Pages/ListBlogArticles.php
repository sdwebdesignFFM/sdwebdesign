<?php

namespace App\Filament\Resources\BlogArticles\Pages;

use App\Filament\Resources\BlogArticles\BlogArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListBlogArticles extends ListRecords
{
    use Translatable;

    protected static string $resource = BlogArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
