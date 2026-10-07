<?php

namespace App\Filament\Resources\AboutUs\Pages;

use App\Filament\Resources\AboutUs\AboutUsResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageAboutUs extends ManageRecords
{
    use Translatable;
    protected static string $resource = AboutUsResource::class;
}
