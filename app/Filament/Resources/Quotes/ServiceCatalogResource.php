<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\BillingCycle;
use App\Enums\ServiceType;
use App\Filament\Resources\Quotes\ServiceCatalogResource\Pages;
use App\Models\Service;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class ServiceCatalogResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static \UnitEnum|string|null $navigationGroup = 'Angebote & Verträge';

    protected static ?string $navigationLabel = 'Dienstleistungen';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Dienstleistung';

    protected static ?string $pluralModelLabel = 'Dienstleistungen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Dienstleistung')
                    ->tabs([
                        Tab::make('Allgemein')
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                Section::make('Grunddaten')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('category')
                                            ->label('Kategorie')
                                            ->options([
                                                'entwicklung' => 'Entwicklung',
                                                'hosting' => 'Hosting',
                                                'wartung' => 'Wartung',
                                                'beratung' => 'Beratung',
                                                'seo' => 'SEO',
                                                'design' => 'Design',
                                            ])
                                            ->searchable(),

                                        RichEditor::make('description')
                                            ->label('Beschreibung')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'bulletList',
                                                'orderedList',
                                                'link',
                                            ])
                                            ->columnSpanFull(),

                                        Select::make('type')
                                            ->label('Typ')
                                            ->options(ServiceType::class)
                                            ->default(ServiceType::OneTime)
                                            ->required()
                                            ->live(),

                                        Select::make('default_unit')
                                            ->label('Einheit')
                                            ->options([
                                                'pauschal' => 'Pauschal',
                                                'stunde' => 'Stunde',
                                                'tag' => 'Tag',
                                                'stueck' => 'Stück',
                                            ])
                                            ->default('pauschal')
                                            ->hidden(fn (Get $get) => $get('type') === ServiceType::Recurring->value),

                                        Select::make('default_billing_cycle')
                                            ->label('Zahlungsweise')
                                            ->options(BillingCycle::class)
                                            ->default(BillingCycle::Monthly)
                                            ->required(fn (Get $get) => $get('type') === ServiceType::Recurring->value)
                                            ->hidden(fn (Get $get) => $get('type') !== ServiceType::Recurring->value),
                                    ]),

                                Section::make('Preise')
                                    ->schema([
                                        TextInput::make('default_price')
                                            ->label('Standardpreis')
                                            ->numeric()
                                            ->prefix('€')
                                            ->helperText(fn (Get $get) => $get('type') === ServiceType::Recurring->value
                                                ? 'Preis pro Abrechnungszeitraum'
                                                : null),
                                    ]),

                                Section::make('Einstellungen')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('sort_order')
                                            ->label('Sortierung')
                                            ->numeric()
                                            ->default(0),

                                        Toggle::make('is_active')
                                            ->label('Aktiv')
                                            ->default(true),
                                    ]),
                            ]),

                        Tab::make('Leistungsvereinbarung')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                Section::make('Detaillierte Leistungsvereinbarung')
                                    ->description('Ausführliche Leistungsbedingungen für diese Dienstleistung. Diese werden dem Kunden in einem Modal angezeigt und können als separates PDF bereitgestellt werden.')
                                    ->schema([
                                        RichEditor::make('detailed_terms')
                                            ->label('Leistungsvereinbarung')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'underline',
                                                'strike',
                                                'h2',
                                                'h3',
                                                'bulletList',
                                                'orderedList',
                                                'blockquote',
                                                'link',
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Zahlungsbedingungen')
                                    ->description('Optionale Zahlungsbedingungen für diese Dienstleistung (z.B. "50% bei Annahme, 50% nach Fertigstellung").')
                                    ->schema([
                                        Textarea::make('payment_terms')
                                            ->label('Zahlungsbedingungen')
                                            ->placeholder('z.B. 50% bei Angebotsannahme, 50% nach Fertigstellung')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
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

                TextColumn::make('category')
                    ->label('Kategorie')
                    ->badge()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->sortable(),

                TextColumn::make('default_price')
                    ->label('Preis')
                    ->money('EUR')
                    ->sortable()
                    ->description(fn (Service $record) => $record->default_billing_cycle?->getPeriodLabel()),

                TextColumn::make('default_billing_cycle')
                    ->label('Zyklus')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                SelectFilter::make('type')
                    ->label('Typ')
                    ->options(ServiceType::class),

                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->options([
                        'entwicklung' => 'Entwicklung',
                        'hosting' => 'Hosting',
                        'wartung' => 'Wartung',
                        'beratung' => 'Beratung',
                        'seo' => 'SEO',
                        'design' => 'Design',
                    ]),
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
            'index' => Pages\ListServiceCatalogs::route('/'),
            'create' => Pages\CreateServiceCatalog::route('/create'),
            'edit' => Pages\EditServiceCatalog::route('/{record}/edit'),
        ];
    }
}
