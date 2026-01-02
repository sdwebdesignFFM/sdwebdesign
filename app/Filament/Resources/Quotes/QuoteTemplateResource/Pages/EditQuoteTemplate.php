<?php

namespace App\Filament\Resources\Quotes\QuoteTemplateResource\Pages;

use App\Filament\Resources\Quotes\QuoteTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuoteTemplate extends EditRecord
{
    protected static string $resource = QuoteTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
