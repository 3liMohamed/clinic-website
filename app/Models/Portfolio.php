<?php

namespace App\Models;

use App\Services\PortfolioService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'cover_image',
        'gallery',
        'title',
        'content',
        'client',
        'project_date',
        'meta_keywords',
        'meta_description',
        'has_detail_page',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'project_date' => 'date',
            'is_visible' => 'boolean',
            'has_detail_page' => 'boolean',
            'order' => 'integer',
        ];
    }

    public array $translatable = [
        'title',
        'content',
        'meta_keywords',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => app(PortfolioService::class)->flush());
        static::deleted(fn() => app(PortfolioService::class)->flush());
        static::restored(fn() => app(PortfolioService::class)->flush());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->where('is_visible', true)
            ->where(function (Builder $query) {
                $query
                    ->whereDoesntHave('category')
                    ->orWhereHas(
                        'category',
                        fn(Builder $query) => $query->visible()
                    );
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function getCoverImageUrlAttribute(): string
    {
        return Storage::disk('images')->url($this->cover_image ?: 'default.webp');
    }

    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn(string $image): string => Storage::disk('images')->url($image))->all();
    }

    public function getSlugAttribute(): ?string
    {
        return $this->has_detail_page
            ? $this->getKey() . '-' . Str::slug($this->getTranslation('title', 'en'))
            : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
