<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\BillingCycle;
use App\Enums\QuoteStatus;
use App\Enums\ServiceType;
use App\Filament\Resources\Quotes\QuoteResource\Pages;
use App\Filament\Resources\Quotes\QuoteResource\RelationManagers\ItemsRelationManager;
use App\Models\Quote;
use App\Models\QuoteTemplate;
use App\Models\Service;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
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
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static \UnitEnum|string|null $navigationGroup = 'Angebote & Verträge';

    protected static ?string $navigationLabel = 'Angebote';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Angebot';

    protected static ?string $pluralModelLabel = 'Angebote';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Kunde')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Vorlage')
                                    ->schema([
                                        Select::make('template_id')
                                            ->label('Vorlage verwenden')
                                            ->relationship('template', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, ?int $state) {
                                                if ($state) {
                                                    $template = QuoteTemplate::find($state);
                                                    if ($template) {
                                                        $set('type', $template->type);
                                                        $set('intro_text', $template->intro_text);
                                                        $set('terms_text', $template->terms_text);
                                                        $set('footer_text', $template->footer_text);
                                                        $set('billing_cycle', $template->default_billing_cycle);
                                                        $set('min_term_months', $template->default_min_term_months);
                                                        $set('auto_renewal', $template->default_auto_renewal);
                                                        $set('notice_period_days', $template->default_notice_period_days);
                                                    }
                                                }
                                            }),
                                    ]),

                                Section::make('Kundendaten')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('client_name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('client_company')
                                            ->label('Unternehmen')
                                            ->maxLength(255),

                                        TextInput::make('client_email')
                                            ->label('E-Mail')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('client_phone')
                                            ->label('Telefon')
                                            ->tel()
                                            ->maxLength(50),

                                        Textarea::make('client_address')
                                            ->label('Adresse')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Angebot')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Titel')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Select::make('type')
                                            ->label('Typ')
                                            ->options(ServiceType::class)
                                            ->default(ServiceType::OneTime)
                                            ->required()
                                            ->live(),

                                        DatePicker::make('valid_until')
                                            ->label('Gültig bis')
                                            ->default(now()->addDays(30)),

                                        Textarea::make('subject')
                                            ->label('Vertragsgegenstand')
                                            ->placeholder('z.B. Domain: example.com')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Einleitung')
                                    ->schema([
                                        RichEditor::make('intro_text')
                                            ->label('')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'bulletList',
                                                'orderedList',
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Positionen')
                            ->icon('heroicon-o-list-bullet')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('items')
                                            ->relationship()
                                            ->label('')
                                            ->schema([
                                                Select::make('service_id')
                                                    ->label('Dienstleistung')
                                                    ->options(Service::active()->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, ?int $state) {
                                                        if ($state) {
                                                            $service = Service::find($state);
                                                            if ($service) {
                                                                $set('name', $service->name);
                                                                $set('description', $service->description);
                                                                $set('detailed_terms', $service->detailed_terms);
                                                                $set('unit', $service->default_unit);
                                                                $set('unit_price', $service->default_price);
                                                                $set('billing_cycle', $service->default_billing_cycle?->value);
                                                            }
                                                        }
                                                    }),

                                                TextInput::make('name')
                                                    ->label('Bezeichnung')
                                                    ->required()
                                                    ->maxLength(255),

                                                Textarea::make('description')
                                                    ->label('Beschreibung')
                                                    ->rows(2),

                                                TextInput::make('quantity')
                                                    ->label('Menge')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required(),

                                                Select::make('unit')
                                                    ->label('Einheit')
                                                    ->options([
                                                        'pauschal' => 'Pauschal',
                                                        'stunde' => 'Stunde',
                                                        'tag' => 'Tag',
                                                        'stueck' => 'Stück',
                                                    ])
                                                    ->placeholder('Einheit wählen'),

                                                Select::make('billing_cycle')
                                                    ->label('Abrechnungszyklus')
                                                    ->options(BillingCycle::class)
                                                    ->placeholder('Einmalig'),

                                                TextInput::make('unit_price')
                                                    ->label('Einzelpreis')
                                                    ->numeric()
                                                    ->prefix('€')
                                                    ->required(),

                                                Toggle::make('is_optional')
                                                    ->label('Optional')
                                                    ->default(false)
                                                    ->live(),

                                                Toggle::make('is_selected')
                                                    ->label('Ausgewählt')
                                                    ->default(true)
                                                    ->visible(fn (Get $get) => $get('is_optional')),

                                                TextInput::make('option_group')
                                                    ->label('Optionsgruppe')
                                                    ->placeholder('z.B. option_a, option_b')
                                                    ->visible(fn (Get $get) => $get('is_optional')),

                                                Hidden::make('sort_order'),
                                                Hidden::make('detailed_terms'),
                                            ])
                                            ->columns(3)
                                            ->reorderableWithButtons()
                                            ->orderColumn('sort_order')
                                            ->addActionLabel('Position hinzufügen')
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Neue Position')
                                            ->collapsible()
                                            ->cloneable(),
                                    ]),

                                Section::make('Preise')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('tax_rate')
                                            ->label('MwSt.-Satz')
                                            ->numeric()
                                            ->suffix('%')
                                            ->default(19),

                                        TextInput::make('subtotal')
                                            ->label('Netto')
                                            ->numeric()
                                            ->prefix('€')
                                            ->disabled()
                                            ->dehydrated(),

                                        TextInput::make('total')
                                            ->label('Brutto')
                                            ->numeric()
                                            ->prefix('€')
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),
                            ]),

                        Tab::make('Laufzeit')
                            ->icon('heroicon-o-clock')
                            ->visible(fn (Get $get) => $get('type') === ServiceType::Recurring->value)
                            ->schema([
                                Section::make('Vertragsbedingungen')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('billing_cycle')
                                            ->label('Abrechnungszyklus')
                                            ->options(BillingCycle::class),

                                        TextInput::make('min_term_months')
                                            ->label('Mindestlaufzeit (Monate)')
                                            ->numeric()
                                            ->default(12),

                                        TextInput::make('notice_period_days')
                                            ->label('Kündigungsfrist (Tage)')
                                            ->numeric()
                                            ->default(30),

                                        Toggle::make('auto_renewal')
                                            ->label('Automatische Verlängerung')
                                            ->default(false),

                                        DatePicker::make('contract_start_date')
                                            ->label('Vertragsbeginn')
                                            ->default(now()),
                                    ]),
                            ]),

                        Tab::make('AGB')
                            ->icon('heroicon-o-scale')
                            ->schema([
                                RichEditor::make('terms_text')
                                    ->label('')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'bulletList',
                                        'orderedList',
                                        'h2',
                                        'h3',
                                    ]),
                            ]),

                        Tab::make('Intern')
                            ->icon('heroicon-o-lock-closed')
                            ->schema([
                                Textarea::make('internal_notes')
                                    ->label('Interne Notizen')
                                    ->rows(5)
                                    ->columnSpanFull(),

                                Section::make('Optionen')
                                    ->schema([
                                        Toggle::make('requires_manual_review')
                                            ->label('Manuelle Prüfung erforderlich')
                                            ->helperText('Wenn aktiviert, wird das Angebot nach Kundenannahme nicht automatisch gegengezeichnet, sondern erfordert eine manuelle Prüfung.')
                                            ->default(false),
                                    ]),

                                Section::make('Status')
                                    ->columns(3)
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status')
                                            ->options(QuoteStatus::class)
                                            ->default(QuoteStatus::Draft)
                                            ->disabled(),

                                        TextInput::make('quote_number')
                                            ->label('Angebotsnummer')
                                            ->disabled(),

                                        TextInput::make('token')
                                            ->label('Token')
                                            ->disabled(),
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
                TextColumn::make('quote_number')
                    ->label('Nr.')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('client_name')
                    ->label('Kunde')
                    ->searchable()
                    ->description(fn (Quote $record) => $record->client_company),

                TextColumn::make('type')
                    ->label('Typ')
                    ->badge(),

                TextColumn::make('total')
                    ->label('Summe')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('valid_until')
                    ->label('Gültig bis')
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(fn (Quote $record) => $record->isExpired() ? 'danger' : null),

                TextColumn::make('created_at')
                    ->label('Erstellt')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(QuoteStatus::class),

                SelectFilter::make('type')
                    ->label('Typ')
                    ->options(ServiceType::class),
            ])
            ->recordActions([
                Action::make('send')
                    ->label('Senden')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (Quote $record) => $record->status === QuoteStatus::Draft)
                    ->requiresConfirmation()
                    ->action(fn (Quote $record) => app(\App\Services\Quote\QuoteService::class)->send($record)),

                Action::make('copyLink')
                    ->label('Link kopieren')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->action(fn (Quote $record) => null)
                    ->extraAttributes(fn (Quote $record) => [
                        'x-data' => '{}',
                        'x-on:click' => "navigator.clipboard.writeText('{$record->getSignedUrl()}'); \$notification.success('Link kopiert!')",
                    ]),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
            'sign' => Pages\SignQuote::route('/{record}/sign'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::whereIn('status', [
            QuoteStatus::Sent,
            QuoteStatus::Viewed,
        ])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
