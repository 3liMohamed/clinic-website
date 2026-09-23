<?php

namespace App\Filament\Resources\WhyChooses\Pages;

use App\Filament\Resources\WhyChooses\WhyChooseResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageWhyChooses extends ManageRecords
{
    use Translatable;
    protected static string $resource = WhyChooseResource::class;

}
