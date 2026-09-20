<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManagePages extends ManageRecords
{
    use Translatable;

    protected static string $resource = PageResource::class;

}
