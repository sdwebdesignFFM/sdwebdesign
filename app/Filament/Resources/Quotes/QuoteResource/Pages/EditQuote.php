<?php

namespace App\Filament\Resources\Quotes\QuoteResource\Pages;

use App\Enums\QuoteStatus;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Client;
use App\Models\Quote;
use App\Services\Quote\QuoteService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-create Client if not selected but data is filled
        if (empty($data['client_id']) && ! empty($data['client_name']) && ! empty($data['client_email'])) {
            // Parse name: use full client_name as last_name (simple approach for auto-creation)
            $client = Client::create([
                'last_name' => $data['client_name'],
                'company' => $data['client_company'] ?? null,
                'email' => $data['client_email'],
                'phone' => $data['client_phone'] ?? null,
                'street' => $data['client_address'] ?? null,
            ]);
            $data['client_id'] = $client->id;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        /** @var Quote $record */
        $record = $this->record;

        return [
            Action::make('countersign')
                ->label('Gegenzeichnen')
                ->icon('heroicon-o-pencil-square')
                ->color('success')
                ->visible(fn () => $record->hasSignature() && ! $record->hasAdminSignature())
                ->url(fn () => QuoteResource::getUrl('sign', ['record' => $record])),

            ActionGroup::make([
                Action::make('send')
                    ->label('Angebot senden')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn () => $this->record->status === QuoteStatus::Draft)
                    ->requiresConfirmation()
                    ->modalHeading('Angebot senden')
                    ->modalDescription('Möchten Sie das Angebot an den Kunden senden?')
                    ->action(function () {
                        $quoteService = app(QuoteService::class);
                        $quoteService->send($this->record);

                        Notification::make()
                            ->success()
                            ->title('Angebot gesendet')
                            ->body("Das Angebot wurde an {$this->record->client_email} gesendet.")
                            ->send();

                        $this->refreshFormData(['status', 'sent_at']);
                    }),

                Action::make('downloadPdf')
                    ->label('PDF herunterladen')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn () => route('admin.quotes.download', $this->record))
                    ->openUrlInNewTab(),

                Action::make('preview')
                    ->label('Vorschau')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn () => $this->record->getSignedUrl())
                    ->openUrlInNewTab(),

                Action::make('duplicate')
                    ->label('Duplizieren')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function () {
                        $quoteService = app(QuoteService::class);
                        $newQuote = $quoteService->duplicate($this->record);

                        Notification::make()
                            ->success()
                            ->title('Angebot dupliziert')
                            ->body("Neues Angebot: {$newQuote->quote_number}")
                            ->send();

                        return redirect()->to(QuoteResource::getUrl('edit', ['record' => $newQuote]));
                    }),
            ])
                ->label('Aktionen')
                ->icon('heroicon-o-ellipsis-vertical')
                ->button(),

            DeleteAction::make()
                ->visible(fn () => $this->record->status === QuoteStatus::Draft),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->calculateTotals();
        $this->record->save();
    }
}
