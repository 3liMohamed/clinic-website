<?php

namespace App\Filament\Resources\Translations\Pages;

use App\Filament\Resources\Translations\TranslationResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageTranslations extends ManageRecords
{
    use Translatable;
    protected static string $resource = TranslationResource::class;

}
