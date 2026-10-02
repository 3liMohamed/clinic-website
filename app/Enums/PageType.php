<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PageType: int implements HasColor, HasIcon, HasLabel
{
    case Main = 0;
    case Details = 1;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Main => __('system.field.main'),
            self::Details => __('system.field.details'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Main => 'primary',
            self::Details => 'warning',
            // self::Delete => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Main  => 'heroicon-o-home-modern',
            self::Details  => 'heroicon-o-information-circle',
        };
    }
}
