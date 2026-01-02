<?php

namespace App\Filament\Resources\Quotes\InvoiceResource\Pages;

use App\Filament\Resources\Quotes\InvoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;
}
