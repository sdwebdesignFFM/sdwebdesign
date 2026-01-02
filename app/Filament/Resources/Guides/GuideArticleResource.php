<?php

namespace App\Filament\Resources\Guides;

use App\Filament\Resources\Guides\GuideArticleResource\Pages;
use App\Filament\Resources\Pages\Schemas\GuidePageForm;
use App\Models\GuideCategory;
use App\Models\Page;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class GuideArticleResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Artikel';

    protected static \UnitEnum|string|null $navigationGroup = 'Ratgeber';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Ratgeber-Artikel';

    protected static ?string $pluralModelLabel = 'Ratgeber-Artikel';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', Page::TYPE_GUIDE)
            ->orderBy('sort_order');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Allgemein')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Grunddaten')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Titel')
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('slug')
                                            ->label('URL-Slug')
                                            ->required(),
                                        Select::make('guide_category_id')
                                            ->label('Kategorie')
                                            ->options(GuideCategory::pluck('name', 'id'))
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('sort_order')
                                            ->label('Reihenfolge')
                                            ->numeric()
                                            ->default(0),
                                        Toggle::make('is_active')
                                            ->label('Veröffentlicht')
                                            ->default(true),
                                    ]),
                            ]),
                        ...GuidePageForm::getTabs(),
                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('Suchmaschinenoptimierung')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta-Titel')
                                            ->maxLength(60),
                                        Textarea::make('meta_description')
                                            ->label('Meta-Beschreibung')
                                            ->rows(3)
                                            ->maxLength(160),
                                    ]),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('guideCategory.name')
                    ->label('Kategorie')
                    ->badge()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('guide_category_id')
                    ->label('Kategorie')
                    ->relationship('guideCategory', 'name')
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuideArticles::route('/'),
            'create' => Pages\CreateGuideArticle::route('/create'),
            'edit' => Pages\EditGuideArticle::route('/{record}/edit'),
        ];
    }
}
