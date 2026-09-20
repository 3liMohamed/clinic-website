<?php

namespace App\Filament\Resources\WebsiteGalleries\Pages;

use App\Filament\Resources\WebsiteGalleries\WebsiteGalleryResource;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;


class ManageWebsiteGalleries extends ManageRecords
{
    use Translatable;
    protected static string $resource = WebsiteGalleryResource::class;

}
