<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ServiceType: string implements HasLabel
{
    case OneTime = 'one_time';
    case Recurring = 'recurring';

    public function getLabel(): string
    {
        return match ($this) {
            self::OneTime => 'Einmalig',
            self::Recurring => 'Wiederkehrend',
        };
    }

    public function isRecurring(): bool
    {
        return $this === self::Recurring;
    }
}
