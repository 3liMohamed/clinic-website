<?php

namespace App\Models;

use App\Services\WebsiteGalleryService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WebsiteGallery extends Model
{
    use HasTranslations, HasAuditLog;

    protected $fillable = [
        'image',
        'key',
        'title'
    ];

    public $translatable = [
        'title',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => app(WebsiteGalleryService::class)->flush());
        static::deleted(fn () => app(WebsiteGalleryService::class)->flush());
    }

    public function getImageUrlAttribute(): ?string
    {
        return asset("images/{$this->image}");
    }

}
