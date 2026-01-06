<?php

namespace App\Filament\Resources\Quotes\QuoteResource\Pages;

use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Client;
use App\Services\Quote\QuoteNumberService;
use App\Settings\QuoteSettings;
use Filament\Resources\Pages\CreateRecord;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $numberService = app(QuoteNumberService::class);

        $data['quote_number'] = $numberService->generateQuoteNumber();
        $data['created_by'] = auth()->id();

        if (empty($data['tax_rate'])) {
            $settings = app(QuoteSettings::class);
            $data['tax_rate'] = $settings->default_tax_rate;
        }

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

    protected function afterCreate(): void
    {
        $this->record->calculateTotals();
        $this->record->save();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
