<?php

namespace App\Filament\Resources\Pricings\Pages;

use App\Filament\Resources\Pricings\PricingResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManagePricings extends ManageRecords
{
    use Translatable;

    protected static string $resource = PricingResource::class;

}
