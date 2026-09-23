<?php

namespace App\Filament\Resources\CallActions\Pages;

use App\Filament\Resources\CallActions\CallActionResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageCallActions extends ManageRecords
{
    use Translatable;
    protected static string $resource = CallActionResource::class;
}
