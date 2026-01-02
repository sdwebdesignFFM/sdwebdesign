<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\ServiceType;
use App\Filament\Resources\Quotes\QuoteTemplateResource\Pages;
use App\Models\QuoteTemplate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuoteTemplateResource extends Resource
{
    protected static ?string $model = QuoteTemplate::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static \UnitEnum|string|null $navigationGroup = 'Angebote & Verträge';

    protected static ?string $navigationLabel = 'Vorlagen';

    protected static ?int $navigationSort = 11;

    protected static ?string $modelLabel = 'Vorlage';

    protected static ?string $pluralModelLabel = 'Vorlagen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Grunddaten')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Vorlagenname')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('type')
                                            ->label('Typ')
                                            ->options(ServiceType::class)
                                            ->default(ServiceType::OneTime)
                                            ->required()
                                            ->live(),

                                        TextInput::make('default_validity_days')
                                            ->label('Gültigkeit (Tage)')
                                            ->numeric()
                                            ->default(30)
                                            ->required(),

                                        Toggle::make('is_active')
                                            ->label('Aktiv')
                                            ->default(true),
                                    ]),
                            ]),

                        Tab::make('Laufzeit')
                            ->icon('heroicon-o-clock')
                            ->visible(fn (Get $get) => $get('type') === ServiceType::Recurring->value)
                            ->schema([
                                Section::make('Vertragsbedingungen')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('default_billing_cycle')
                                            ->label('Abrechnungszyklus')
                                            ->options([
                                                'monthly' => 'Monatlich',
                                                'quarterly' => 'Vierteljährlich',
                                                'yearly' => 'Jährlich',
                                            ]),

                                        TextInput::make('default_min_term_months')
                                            ->label('Mindestlaufzeit (Monate)')
                                            ->numeric()
                                            ->default(12),

                                        TextInput::make('default_notice_period_days')
                                            ->label('Kündigungsfrist (Tage)')
                                            ->numeric()
                                            ->default(30),

                                        Toggle::make('default_auto_renewal')
                                            ->label('Automatische Verlängerung')
                                            ->default(false),
                                    ]),
                            ]),

                        Tab::make('Texte')
                            ->icon('heroicon-o-document')
                            ->schema([
                                Section::make('Einleitungstext')
                                    ->schema([
                                        RichEditor::make('intro_text')
                                            ->label('Einleitung')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'bulletList',
                                                'orderedList',
                                            ]),
                                    ]),

                                Section::make('Fußzeile')
                                    ->schema([
                                        RichEditor::make('footer_text')
                                            ->label('Fußzeile')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                            ]),
                                    ]),
                            ]),

                        Tab::make('AGB')
                            ->icon('heroicon-o-scale')
                            ->schema([
                                RichEditor::make('terms_text')
                                    ->label('Allgemeine Geschäftsbedingungen')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'bulletList',
                                        'orderedList',
                                        'h2',
                                        'h3',
                                    ])
                                    ->columnSpanFull(),
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
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->sortable(),

                TextColumn::make('default_validity_days')
                    ->label('Gültigkeit')
                    ->suffix(' Tage')
                    ->sortable(),

                TextColumn::make('default_min_term_months')
                    ->label('Mindestlaufzeit')
                    ->suffix(' Monate')
                    ->sortable()
                    ->placeholder('-'),

                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                TextColumn::make('quotes_count')
                    ->label('Angebote')
                    ->counts('quotes')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('type')
                    ->label('Typ')
                    ->options(ServiceType::class),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuoteTemplates::route('/'),
            'create' => Pages\CreateQuoteTemplate::route('/create'),
            'edit' => Pages\EditQuoteTemplate::route('/{record}/edit'),
        ];
    }
}
