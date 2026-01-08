<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TaskPriority: string implements HasColor, HasIcon, HasLabel
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Urgent = 'urgent';

    public function getLabel(): string
    {
        return match ($this) {
            self::Low => 'Niedrig',
            self::Normal => 'Normal',
            self::High => 'Hoch',
            self::Urgent => 'Dringend',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Low => 'gray',
            self::Normal => 'info',
            self::High => 'warning',
            self::Urgent => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Low => 'heroicon-o-arrow-down',
            self::Normal => 'heroicon-o-minus',
            self::High => 'heroicon-o-arrow-up',
            self::Urgent => 'heroicon-o-exclamation-triangle',
        };
    }

    public function getSortOrder(): int
    {
        return match ($this) {
            self::Urgent => 1,
            self::High => 2,
            self::Normal => 3,
            self::Low => 4,
        };
    }
}
