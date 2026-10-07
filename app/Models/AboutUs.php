<?php

namespace App\Models;

use App\Services\AboutUsService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class AboutUs extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'image',
        'sub_title',
        'title',
        'description',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public array $translatable = [
        'sub_title',
        'title',
        'description',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => app(AboutUsService::class)->flush());

        static::deleted(function (self $aboutUs) {
            $aboutUs->items()->delete();

            app(AboutUsService::class)->flush();
        });

        static::restored(function (self $aboutUs) {
            $aboutUs->items()->onlyTrashed()->restore();

            app(AboutUsService::class)->flush();
        });

        static::forceDeleted(function (self $aboutUs) {
            $aboutUs->items()->withTrashed()->forceDelete();

            app(AboutUsService::class)->flush();
        });
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return Storage::disk('images')->url($this->image ?: 'default.webp');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AboutItem::class);
    }
}
