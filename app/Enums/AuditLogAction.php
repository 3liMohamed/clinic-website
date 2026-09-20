<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum AuditLogAction: int implements HasColor, HasIcon, HasLabel
{
    case Create = 0;
    case Update = 1;
    case Delete = 2;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Create => __('system.field.create'),
            self::Update => __('system.field.update'),
            self::Delete => __('system.field.delete'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Create => 'success',
            self::Update => 'primary',
            self::Delete => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Create => 'heroicon-o-plus',
            self::Update => 'heroicon-o-pencil',
            self::Delete => 'heroicon-o-trash',
        };
    }
}
