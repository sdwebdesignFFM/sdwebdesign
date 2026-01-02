<?php

namespace App\Filament\Resources\Quotes\QuoteResource\Pages;

use App\Filament\Resources\Quotes\QuoteResource;
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
