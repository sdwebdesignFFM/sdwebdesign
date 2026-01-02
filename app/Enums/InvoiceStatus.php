<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum InvoiceStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Entwurf',
            self::Sent => 'Gesendet',
            self::Paid => 'Bezahlt',
            self::Overdue => 'Überfällig',
            self::Cancelled => 'Storniert',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Sent => 'info',
            self::Paid => 'success',
            self::Overdue => 'danger',
            self::Cancelled => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Draft => 'heroicon-o-document',
            self::Sent => 'heroicon-o-paper-airplane',
            self::Paid => 'heroicon-o-check-circle',
            self::Overdue => 'heroicon-o-exclamation-triangle',
            self::Cancelled => 'heroicon-o-x-circle',
        };
    }

    public function isPending(): bool
    {
        return in_array($this, [self::Draft, self::Sent, self::Overdue]);
    }

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }
}
