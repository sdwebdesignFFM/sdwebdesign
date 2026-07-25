<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\BillingCycle;
use App\Enums\ContractStatus;
use App\Enums\ServiceType;
use App\Filament\Resources\Quotes\ContractResource\Pages;
use App\Models\Contract;
use App\Services\Quote\ContractService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static \UnitEnum|string|null $navigationGroup = 'Angebote & Verträge';

    protected static ?string $navigationLabel = 'Verträge';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Vertrag';

    protected static ?string $pluralModelLabel = 'Verträge';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Übersicht')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Vertrag')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('contract_number')
                                            ->label('Vertragsnummer')
                                            ->disabled(),

                                        Select::make('status')
                                            ->label('Status')
                                            ->options(ContractStatus::class)
                                            ->disabled(),

                                        TextInput::make('title')
                                            ->label('Titel')
                                            ->disabled(),

                                        Select::make('type')
                                            ->label('Typ')
                                            ->options(ServiceType::class)
                                            ->disabled(),
                                    ]),

                                Section::make('Kunde')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('client_name')
                                            ->label('Name')
                                            ->disabled(),

                                        TextInput::make('client_company')
                                            ->label('Unternehmen')
                                            ->disabled(),

                                        TextInput::make('client_email')
                                            ->label('E-Mail')
                                            ->disabled(),

                                        TextInput::make('client_phone')
                                            ->label('Telefon')
                                            ->disabled(),
                                    ]),
                            ]),

                        Tab::make('Laufzeit')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make('Vertragsdaten')
                                    ->columns(2)
                                    ->schema([
                                        DatePicker::make('start_date')
                                            ->label('Vertragsbeginn')
                                            ->disabled(),

                                        DatePicker::make('min_term_end_date')
                                            ->label('Ende Mindestlaufzeit')
                                            ->disabled(),

                                        Select::make('billing_cycle')
                                            ->label('Zahlungsweise')
                                            ->options(BillingCycle::class)
                                            ->disabled(),

                                        TextInput::make('min_term_months')
                                            ->label('Mindestlaufzeit (Monate)')
                                            ->disabled(),

                                        TextInput::make('notice_period_days')
                                            ->label('Kündigungsfrist (Tage)')
                                            ->disabled(),

                                        Toggle::make('auto_renewal')
                                            ->label('Automatische Verlängerung')
                                            ->disabled(),
                                    ]),

                                Section::make('Aktuelle Periode')
                                    ->columns(3)
                                    ->schema([
                                        DatePicker::make('current_period_start')
                                            ->label('Periodenbeginn')
                                            ->disabled(),

                                        DatePicker::make('current_period_end')
                                            ->label('Periodenende')
                                            ->disabled(),

                                        DatePicker::make('next_billing_date')
                                            ->label('Nächste Abrechnung')
                                            ->disabled(),
                                    ]),
                            ]),

                        Tab::make('Annahme')
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                Section::make('Angebotsannahme')
                                    ->description('Rechtlich relevante Informationen zur Vertragsannahme')
                                    ->columns(2)
                                    ->schema([
                                        Placeholder::make('quote_number')
                                            ->label('Angebotsnummer')
                                            ->content(fn (Contract $record) => $record->quote?->quote_number ?? '-'),

                                        Placeholder::make('quote_version')
                                            ->label('Angebotsversion')
                                            ->content(fn (Contract $record) => $record->quote?->document_hash
                                                ? substr($record->quote->document_hash, 0, 12).'...'
                                                : '-')
                                            ->hint(fn (Contract $record) => $record->quote?->document_hash ?? null),

                                        Placeholder::make('accepted_at_display')
                                            ->label('Datum & Uhrzeit der Annahme')
                                            ->content(fn (Contract $record) => $record->quote?->accepted_at?->format('d.m.Y, H:i:s') ?? '-'),

                                        Placeholder::make('accepted_name_display')
                                            ->label('Name des Unterzeichners')
                                            ->content(fn (Contract $record) => $record->quote?->accepted_name ?? '-'),

                                        Placeholder::make('acceptance_type')
                                            ->label('Art der Annahme')
                                            ->content(fn (Contract $record) => $record->quote?->signature_data
                                                ? new HtmlString('<span class="text-green-600 font-medium">Elektronisch (digitale Unterschrift)</span>')
                                                : 'Manuell (PDF)'),

                                        Placeholder::make('accepted_ip_display')
                                            ->label('IP-Adresse')
                                            ->content(fn (Contract $record) => $record->quote?->accepted_ip ?? '-'),
                                    ]),

                                Section::make('Akzeptierte Dokumente')
                                    ->schema([
                                        Placeholder::make('accepted_documents_display')
                                            ->label('')
                                            ->content(function (Contract $record) {
                                                $quote = $record->quote;
                                                if (! $quote || ! $quote->accepted_documents) {
                                                    return '-';
                                                }

                                                $docs = $quote->accepted_documents;
                                                $lines = [];

                                                // AGB
                                                if (isset($docs['agb']) && $docs['agb']['accepted']) {
                                                    $version = isset($docs['agb']['version'])
                                                        ? ' (Version: '.substr($docs['agb']['version'], 0, 10).'...)'
                                                        : '';
                                                    $lines[] = '✓ Allgemeine Geschäftsbedingungen (AGB)'.$version;
                                                }

                                                // Leistungsvereinbarungen
                                                if (isset($docs['items']) && is_array($docs['items'])) {
                                                    foreach ($docs['items'] as $item) {
                                                        $termsInfo = ($item['has_terms'] ?? false) ? ' (inkl. Leistungsvereinbarung)' : '';
                                                        $lines[] = '✓ '.$item['name'].$termsInfo;
                                                    }
                                                }

                                                return new HtmlString(
                                                    '<div class="space-y-1 text-sm">'.
                                                    implode('<br>', array_map('e', $lines)).
                                                    '</div>'
                                                );
                                            }),
                                    ]),
                            ]),

                        Tab::make('Kündigung')
                            ->icon('heroicon-o-x-circle')
                            ->visible(fn (Contract $record) => $record->status === ContractStatus::Cancelled)
                            ->schema([
                                Section::make('Kündigungsdetails')
                                    ->columns(2)
                                    ->schema([
                                        DatePicker::make('cancelled_at')
                                            ->label('Gekündigt am')
                                            ->disabled(),

                                        DatePicker::make('cancellation_effective_date')
                                            ->label('Wirksam zum')
                                            ->disabled(),

                                        Textarea::make('cancellation_reason')
                                            ->label('Kündigungsgrund')
                                            ->disabled()
                                            ->columnSpanFull(),
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
                TextColumn::make('contract_number')
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
                    ->description(fn (Contract $record) => $record->client_company),

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

                TextColumn::make('next_billing_date')
                    ->label('Nächste Abrechnung')
                    ->date('d.m.Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('start_date')
                    ->label('Beginn')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ContractStatus::class),

                SelectFilter::make('type')
                    ->label('Typ')
                    ->options(ServiceType::class),
            ])
            ->recordActions([
                Action::make('createInvoice')
                    ->label('Rechnung erstellen')
                    ->icon('heroicon-o-document-plus')
                    ->color('success')
                    ->visible(fn (Contract $record) => $record->status === ContractStatus::Active && $record->isRecurring())
                    ->requiresConfirmation()
                    ->action(function (Contract $record) {
                        $contractService = app(ContractService::class);
                        $invoice = $contractService->generateInvoice($record);

                        if ($invoice) {
                            return redirect()->to(InvoiceResource::getUrl('edit', ['record' => $invoice]));
                        }
                    }),

                Action::make('cancel')
                    ->label('Kündigen')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Contract $record) => $record->canBeCancelled())
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('reason')
                            ->label('Kündigungsgrund')
                            ->rows(3),

                        DatePicker::make('effective_date')
                            ->label('Wirksam zum')
                            ->default(fn (Contract $record) => $record->getEarliestCancellationDate()),
                    ])
                    ->action(function (Contract $record, array $data) {
                        $contractService = app(ContractService::class);
                        $contractService->cancel(
                            $record,
                            $data['reason'] ?? null,
                            $data['effective_date'] ? Carbon::parse($data['effective_date']) : null
                        );
                    }),

                ViewAction::make(),
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
            'index' => Pages\ListContracts::route('/'),
            'view' => Pages\ViewContract::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', ContractStatus::Active)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
