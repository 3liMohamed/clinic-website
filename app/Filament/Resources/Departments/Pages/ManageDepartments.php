<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageDepartments extends ManageRecords
{
    use Translatable;
    protected static string $resource = DepartmentResource::class;
}
