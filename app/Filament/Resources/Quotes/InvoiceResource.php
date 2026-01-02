<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Quotes\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Services\Quote\InvoiceService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Angebote & Verträge';

    protected static ?string $navigationLabel = 'Rechnungen';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Rechnung';

    protected static ?string $pluralModelLabel = 'Rechnungen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Rechnung')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Rechnungsdaten')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('invoice_number')
                                            ->label('Rechnungsnummer')
                                            ->disabled(),

                                        Select::make('status')
                                            ->label('Status')
                                            ->options(InvoiceStatus::class)
                                            ->disabled(),

                                        DatePicker::make('issue_date')
                                            ->label('Rechnungsdatum')
                                            ->required(),

                                        DatePicker::make('due_date')
                                            ->label('Fällig am')
                                            ->required(),

                                        DatePicker::make('period_start')
                                            ->label('Leistungszeitraum von'),

                                        DatePicker::make('period_end')
                                            ->label('Leistungszeitraum bis'),
                                    ]),

                                Section::make('Kunde')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('client_name')
                                            ->label('Name')
                                            ->required(),

                                        TextInput::make('client_company')
                                            ->label('Unternehmen'),

                                        TextInput::make('client_email')
                                            ->label('E-Mail')
                                            ->required(),

                                        Textarea::make('client_address')
                                            ->label('Adresse')
                                            ->rows(3),
                                    ]),
                            ]),

                        Tab::make('Beträge')
                            ->icon('heroicon-o-currency-euro')
                            ->schema([
                                Section::make('Summen')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('subtotal')
                                            ->label('Netto')
                                            ->numeric()
                                            ->prefix('€')
                                            ->disabled(),

                                        TextInput::make('tax_rate')
                                            ->label('MwSt.-Satz')
                                            ->numeric()
                                            ->suffix('%')
                                            ->disabled(),

                                        TextInput::make('tax_amount')
                                            ->label('MwSt.')
                                            ->numeric()
                                            ->prefix('€')
                                            ->disabled(),

                                        TextInput::make('total')
                                            ->label('Brutto')
                                            ->numeric()
                                            ->prefix('€')
                                            ->disabled(),
                                    ]),
                            ]),

                        Tab::make('Zahlung')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Section::make('Zahlungsinformationen')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('payment_method')
                                            ->label('Zahlungsart')
                                            ->options([
                                                'bank_transfer' => 'Überweisung',
                                                'stripe' => 'Kreditkarte (Stripe)',
                                                'paypal' => 'PayPal',
                                            ]),

                                        TextInput::make('payment_reference')
                                            ->label('Zahlungsreferenz'),

                                        DatePicker::make('paid_at')
                                            ->label('Bezahlt am'),
                                    ]),
                            ]),

                        Tab::make('Notizen')
                            ->icon('heroicon-o-document')
                            ->schema([
                                Textarea::make('notes')
                                    ->label('Notizen')
                                    ->rows(5)
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
                TextColumn::make('invoice_number')
                    ->label('Nr.')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client_name')
                    ->label('Kunde')
                    ->searchable()
                    ->description(fn (Invoice $record) => $record->client_company),

                TextColumn::make('total')
                    ->label('Betrag')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('issue_date')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Fällig')
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(fn (Invoice $record) => $record->isOverdue() ? 'danger' : null),

                TextColumn::make('paid_at')
                    ->label('Bezahlt')
                    ->date('d.m.Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('contract.contract_number')
                    ->label('Vertrag')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(InvoiceStatus::class),
            ])
            ->recordActions([
                Action::make('send')
                    ->label('Senden')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn (Invoice $record) => $record->status === InvoiceStatus::Draft)
                    ->requiresConfirmation()
                    ->action(function (Invoice $record) {
                        $invoiceService = app(InvoiceService::class);
                        $invoiceService->send($record);

                        Notification::make()
                            ->success()
                            ->title('Rechnung gesendet')
                            ->send();
                    }),

                Action::make('markPaid')
                    ->label('Als bezahlt markieren')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Invoice $record) => $record->canBePaid())
                    ->requiresConfirmation()
                    ->form([
                        Select::make('payment_method')
                            ->label('Zahlungsart')
                            ->options([
                                'bank_transfer' => 'Überweisung',
                                'stripe' => 'Kreditkarte',
                                'paypal' => 'PayPal',
                            ]),

                        TextInput::make('reference')
                            ->label('Referenz'),
                    ])
                    ->action(function (Invoice $record, array $data) {
                        $invoiceService = app(InvoiceService::class);
                        $invoiceService->markAsPaid($record, $data['payment_method'] ?? null, $data['reference'] ?? null);

                        Notification::make()
                            ->success()
                            ->title('Rechnung als bezahlt markiert')
                            ->send();
                    }),

                Action::make('downloadPdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn (Invoice $record) => route('admin.invoices.download', $record))
                    ->openUrlInNewTab(),

                EditAction::make(),
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
            'index' => Pages\ListInvoices::route('/'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', [
            InvoiceStatus::Sent,
            InvoiceStatus::Overdue,
        ])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $hasOverdue = static::getModel()::where('status', InvoiceStatus::Overdue)->exists();

        return $hasOverdue ? 'danger' : 'warning';
    }
}
