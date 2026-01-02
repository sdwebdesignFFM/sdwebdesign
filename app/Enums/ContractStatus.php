<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ContractStatus: string implements HasColor, HasIcon, HasLabel
{
    case Active = 'active';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Aktiv',
            self::Cancelled => 'Gekündigt',
            self::Expired => 'Ausgelaufen',
            self::Completed => 'Abgeschlossen',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Cancelled => 'danger',
            self::Expired => 'gray',
            self::Completed => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Active => 'heroicon-o-check-badge',
            self::Cancelled => 'heroicon-o-x-mark',
            self::Expired => 'heroicon-o-clock',
            self::Completed => 'heroicon-o-check',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }
}
