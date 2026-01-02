<?php

namespace App\Filament\Resources\Quotes\QuoteResource\Pages;

use App\Enums\QuoteStatus;
use App\Filament\Resources\Quotes\QuoteResource;
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

                Action::make('copyLink')
                    ->label('Link kopieren')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->action(function () {
                        Notification::make()
                            ->success()
                            ->title('Link kopiert')
                            ->body($this->record->getSignedUrl())
                            ->send();
                    })
                    ->extraAttributes([
                        'x-data' => '{}',
                        'x-on:click' => "navigator.clipboard.writeText('{$this->record->getSignedUrl()}')",
                    ]),

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
