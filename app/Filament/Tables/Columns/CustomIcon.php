<?php

namespace App\Filament\Tables\Columns;

use Filament\Support\Components\Contracts\HasEmbeddedView;
use Filament\Tables\Columns\Column;

class CustomIcon extends Column implements HasEmbeddedView
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->width(25);
    }

    public function toEmbeddedHtml(): string
    {
        $state = $this->getState();

        if (blank($state)) {
            return '';
        }

        $state = e($state);

        return <<<HTML
            <div class="!w-full !p-2 !text-center">
                <i class="{$state} !text-6xl !text-primary-600"></i>
            </div>
        HTML;
    }
}
