<?php

namespace App\Filament\Resources\BlogArticles;

use App\Filament\Resources\BlogArticles\Pages\CreateBlogArticle;
use App\Filament\Resources\BlogArticles\Pages\EditBlogArticle;
use App\Filament\Resources\BlogArticles\Pages\ListBlogArticles;
use App\Filament\Resources\BlogArticles\Schemas\BlogArticleForm;
use App\Filament\Resources\BlogArticles\Tables\BlogArticlesTable;
use App\Models\BlogArticle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class BlogArticleResource extends Resource
{
    use Translatable;

    protected static ?string $model = BlogArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static UnitEnum|string|null $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Artikel';

    protected static ?string $modelLabel = 'Blog-Artikel';

    protected static ?string $pluralModelLabel = 'Blog-Artikel';

    public static function form(Schema $schema): Schema
    {
        return BlogArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogArticlesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogArticles::route('/'),
            'create' => CreateBlogArticle::route('/create'),
            'edit' => EditBlogArticle::route('/{record}/edit'),
        ];
    }
}
