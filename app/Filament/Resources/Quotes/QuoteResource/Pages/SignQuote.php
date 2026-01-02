<?php

namespace App\Filament\Resources\Quotes\QuoteResource\Pages;

use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Quote;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class SignQuote extends Page
{
    protected static string $resource = QuoteResource::class;

    protected string $view = 'filament.resources.quotes.pages.sign-quote';

    public Quote $record;

    public string $adminSignatureName = '';

    public string $adminSignaturePosition = '';

    public string $signatureData = '';

    public function mount(int|string $record): void
    {
        $this->record = Quote::findOrFail($record);

        if (! $this->record->hasSignature()) {
            Notification::make()
                ->danger()
                ->title('Angebot nicht unterschrieben')
                ->body('Das Angebot wurde noch nicht vom Kunden unterschrieben.')
                ->send();

            $this->redirect(QuoteResource::getUrl('edit', ['record' => $this->record]));
        }

        if ($this->record->hasAdminSignature()) {
            Notification::make()
                ->warning()
                ->title('Bereits gegengezeichnet')
                ->body('Das Angebot wurde bereits gegengezeichnet.')
                ->send();

            $this->redirect(QuoteResource::getUrl('edit', ['record' => $this->record]));
        }
    }

    public function getTitle(): string|Htmlable
    {
        return 'Angebot gegenzeichnen: '.$this->record->quote_number;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Zurück')
                ->url(QuoteResource::getUrl('edit', ['record' => $this->record]))
                ->color('gray'),
        ];
    }

    public function clearSignature(): void
    {
        $this->signatureData = '';
        $this->dispatch('signature-cleared');
    }

    public function sign(): void
    {
        $this->validate([
            'adminSignatureName' => 'required|string|min:3|max:255',
            'adminSignaturePosition' => 'required|string|max:255',
            'signatureData' => 'required|string',
        ], [
            'adminSignatureName.required' => 'Bitte geben Sie Ihren Namen ein.',
            'adminSignatureName.min' => 'Der Name muss mindestens 3 Zeichen lang sein.',
            'adminSignaturePosition.required' => 'Bitte geben Sie Ihre Position ein.',
            'signatureData.required' => 'Bitte unterschreiben Sie das Angebot.',
        ]);

        $this->record->update([
            'admin_signature_data' => $this->signatureData,
            'admin_signature_name' => $this->adminSignatureName,
            'admin_signature_position' => $this->adminSignaturePosition,
            'admin_signed_at' => now(),
        ]);

        Notification::make()
            ->success()
            ->title('Angebot gegengezeichnet')
            ->body('Die Auftragsbestätigung ist jetzt vollständig.')
            ->send();

        $this->redirect(QuoteResource::getUrl('edit', ['record' => $this->record]));
    }
}
