<?php

namespace App\Models;

use App\Services\ServiceService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use IllUminate\Support\Str;

class Service extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'image',
        'icon',
        'title',
        'description',
        'content',
        'meta_keywords',
        'meta_description',
        'has_detail_page',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'has_detail_page' => 'boolean',
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    public array $translatable = [
        'title',
        'description',
        'content',
        'meta_keywords',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => app(ServiceService::class)->flush());
        static::deleted(fn () => app(ServiceService::class)->flush());
        static::restored(fn () => app(ServiceService::class)->flush());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::disk('images')->url($this->image ?: 'default.webp');
    }

    public function getSlugAttribute(): ?string
    {
        return $this->has_detail_page
            ? $this->getKey() . '-' . Str::slug($this->getTranslation('title', 'en'))
            : null;
    }
}
