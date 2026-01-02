<?php

namespace App\Filament\Resources\Quotes\ContractResource\Pages;

use App\Enums\ContractStatus;
use App\Filament\Resources\Quotes\ContractResource;
use App\Filament\Resources\Quotes\InvoiceResource;
use App\Services\Quote\ContractService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewContract extends ViewRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('createInvoice')
                    ->label('Rechnung erstellen')
                    ->icon('heroicon-o-document-plus')
                    ->color('success')
                    ->visible(fn () => $this->record->status === ContractStatus::Active && $this->record->isRecurring())
                    ->requiresConfirmation()
                    ->action(function () {
                        $contractService = app(ContractService::class);
                        $invoice = $contractService->generateInvoice($this->record);

                        if ($invoice) {
                            Notification::make()
                                ->success()
                                ->title('Rechnung erstellt')
                                ->body("Rechnung {$invoice->invoice_number} wurde erstellt.")
                                ->send();

                            return redirect()->to(InvoiceResource::getUrl('edit', ['record' => $invoice]));
                        }
                    }),

                Action::make('downloadPdf')
                    ->label('PDF herunterladen')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn () => route('admin.contracts.download', $this->record))
                    ->openUrlInNewTab(),

                Action::make('cancel')
                    ->label('Vertrag kündigen')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn () => $this->record->canBeCancelled())
                    ->requiresConfirmation()
                    ->modalHeading('Vertrag kündigen')
                    ->form([
                        Textarea::make('reason')
                            ->label('Kündigungsgrund')
                            ->rows(3),

                        DatePicker::make('effective_date')
                            ->label('Wirksam zum')
                            ->default(fn () => $this->record->getEarliestCancellationDate()),
                    ])
                    ->action(function (array $data) {
                        $contractService = app(ContractService::class);
                        $contractService->cancel(
                            $this->record,
                            $data['reason'] ?? null,
                            $data['effective_date'] ? \Carbon\Carbon::parse($data['effective_date']) : null
                        );

                        Notification::make()
                            ->success()
                            ->title('Vertrag gekündigt')
                            ->send();

                        $this->refreshFormData(['status', 'cancelled_at', 'cancellation_effective_date', 'cancellation_reason']);
                    }),
            ])
                ->label('Aktionen')
                ->icon('heroicon-o-ellipsis-vertical')
                ->button(),
        ];
    }
}
