<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BillingCycle: string implements HasLabel
{
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Yearly = 'yearly';

    public function getLabel(): string
    {
        return match ($this) {
            self::Monthly => 'Monatlich',
            self::Quarterly => 'Vierteljährlich',
            self::Yearly => 'Jährlich',
        };
    }

    public function getMonths(): int
    {
        return match ($this) {
            self::Monthly => 1,
            self::Quarterly => 3,
            self::Yearly => 12,
        };
    }

    public function getPeriodLabel(): string
    {
        return match ($this) {
            self::Monthly => 'pro Monat',
            self::Quarterly => 'pro Quartal',
            self::Yearly => 'pro Jahr',
        };
    }
}
