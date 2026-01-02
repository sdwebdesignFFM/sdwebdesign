<?php

namespace App\Filament\Resources\BlogArticles\Pages;

use App\Filament\Resources\BlogArticles\BlogArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditBlogArticle extends EditRecord
{
    use Translatable;

    protected static string $resource = BlogArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
