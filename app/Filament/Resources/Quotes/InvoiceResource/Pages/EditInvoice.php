<?php

namespace App\Filament\Resources\Quotes\InvoiceResource\Pages;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Quotes\InvoiceResource;
use App\Services\Quote\InvoiceService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('send')
                    ->label('Rechnung senden')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn () => $this->record->status === InvoiceStatus::Draft)
                    ->requiresConfirmation()
                    ->modalHeading('Rechnung senden')
                    ->modalDescription('Möchten Sie die Rechnung an den Kunden senden?')
                    ->action(function () {
                        $invoiceService = app(InvoiceService::class);
                        $invoiceService->send($this->record);

                        Notification::make()
                            ->success()
                            ->title('Rechnung gesendet')
                            ->body("Die Rechnung wurde an {$this->record->client_email} gesendet.")
                            ->send();

                        $this->refreshFormData(['status', 'sent_at']);
                    }),

                Action::make('markPaid')
                    ->label('Als bezahlt markieren')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn () => $this->record->canBePaid())
                    ->requiresConfirmation()
                    ->modalHeading('Zahlung erfassen')
                    ->form([
                        Select::make('payment_method')
                            ->label('Zahlungsart')
                            ->options([
                                'bank_transfer' => 'Überweisung',
                                'stripe' => 'Kreditkarte (Stripe)',
                                'paypal' => 'PayPal',
                            ]),

                        TextInput::make('reference')
                            ->label('Zahlungsreferenz'),
                    ])
                    ->action(function (array $data) {
                        $invoiceService = app(InvoiceService::class);
                        $invoiceService->markAsPaid(
                            $this->record,
                            $data['payment_method'] ?? null,
                            $data['reference'] ?? null
                        );

                        Notification::make()
                            ->success()
                            ->title('Rechnung als bezahlt markiert')
                            ->send();

                        $this->refreshFormData(['status', 'paid_at', 'payment_method', 'payment_reference']);
                    }),

                Action::make('downloadPdf')
                    ->label('PDF herunterladen')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn () => route('admin.invoices.download', $this->record))
                    ->openUrlInNewTab(),

                Action::make('cancel')
                    ->label('Stornieren')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn () => $this->record->canBeCancelled())
                    ->requiresConfirmation()
                    ->modalHeading('Rechnung stornieren')
                    ->form([
                        Textarea::make('reason')
                            ->label('Stornogrund')
                            ->rows(3),
                    ])
                    ->action(function (array $data) {
                        $invoiceService = app(InvoiceService::class);
                        $invoiceService->cancel($this->record, $data['reason'] ?? null);

                        Notification::make()
                            ->success()
                            ->title('Rechnung storniert')
                            ->body("Stornonummer: {$this->record->cancellation_number}")
                            ->send();

                        $this->refreshFormData(['status', 'cancelled_at', 'cancellation_number', 'cancellation_reason']);
                    }),
            ])
                ->label('Aktionen')
                ->icon('heroicon-o-ellipsis-vertical')
                ->button(),

            DeleteAction::make()
                ->visible(fn () => $this->record->status === InvoiceStatus::Draft),
        ];
    }
}
