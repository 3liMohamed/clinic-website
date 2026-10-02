<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum WorkingDay: int implements HasColor, HasIcon, HasLabel
{
    case SATURDAY = 0;
    case SUNDAY = 1;
    case MONDAY = 2;
    case TUESDAY = 3;
    case WEDNESDAY = 4;
    case THURSDAY = 5;
    case FRIDAY = 6;

     public function getLabel(): string
    {
        return match ($this) {
            self::SATURDAY => __('system.days.saturday'),
            self::SUNDAY => __('system.days.sunday'),
            self::MONDAY => __('system.days.monday'),
            self::TUESDAY => __('system.days.tuesday'),
            self::WEDNESDAY => __('system.days.wednesday'),
            self::THURSDAY => __('system.days.thursday'),
            self::FRIDAY => __('system.days.friday'),
        };
    }

    public function getColor(): string
    {
        return 'gray';
    }

    public function getIcon(): string
    {
        return 'heroicon-o-calendar-days';
    }
}
