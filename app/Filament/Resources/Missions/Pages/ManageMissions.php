<?php

namespace App\Filament\Resources\Missions\Pages;

use App\Filament\Resources\Missions\MissionResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageMissions extends ManageRecords
{
    use Translatable;
    protected static string $resource = MissionResource::class;

}
