<?php

namespace App\Filament\Resources\Quotes\QuoteTemplateResource\Pages;

use App\Filament\Resources\Quotes\QuoteTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuoteTemplate extends CreateRecord
{
    protected static string $resource = QuoteTemplateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
