<?php

namespace App\Filament\Resources\Visions\Pages;

use App\Filament\Resources\Visions\VisionResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageVisions extends ManageRecords
{
    use Translatable;
    protected static string $resource = VisionResource::class;
}
