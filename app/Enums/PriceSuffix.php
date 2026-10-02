<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PriceSuffix: int implements HasColor, HasIcon, HasLabel
{
    case PER_VISIT = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::PER_VISIT => __('system.price_suffix.per_visit'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PER_VISIT => 'info',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PER_VISIT => 'heroicon-o-calendar-days',
        };
    }
}
