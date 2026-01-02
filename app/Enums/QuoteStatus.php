<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum QuoteStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Viewed = 'viewed';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Expired = 'expired';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Entwurf',
            self::Sent => 'Gesendet',
            self::Viewed => 'Angesehen',
            self::Accepted => 'Angenommen',
            self::Declined => 'Abgelehnt',
            self::Expired => 'Abgelaufen',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Sent => 'info',
            self::Viewed => 'warning',
            self::Accepted => 'success',
            self::Declined => 'danger',
            self::Expired => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Draft => 'heroicon-o-document',
            self::Sent => 'heroicon-o-paper-airplane',
            self::Viewed => 'heroicon-o-eye',
            self::Accepted => 'heroicon-o-check-circle',
            self::Declined => 'heroicon-o-x-circle',
            self::Expired => 'heroicon-o-clock',
        };
    }

    public function isPending(): bool
    {
        return in_array($this, [self::Draft, self::Sent, self::Viewed]);
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Accepted, self::Declined, self::Expired]);
    }
}
