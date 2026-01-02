<?php

namespace App\Filament\Resources\Quotes\QuoteTemplateResource\Pages;

use App\Filament\Resources\Quotes\QuoteTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuoteTemplates extends ListRecords
{
    protected static string $resource = QuoteTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
