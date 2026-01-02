<?php

namespace App\Filament\Resources\Quotes\QuoteResource\Pages;

use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
