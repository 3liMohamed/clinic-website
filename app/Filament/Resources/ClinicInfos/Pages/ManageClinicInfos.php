<?php

namespace App\Filament\Resources\ClinicInfos\Pages;

use App\Filament\Resources\ClinicInfos\ClinicInfoResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageClinicInfos extends ManageRecords
{
    use Translatable;
    protected static string $resource = ClinicInfoResource::class;
}
