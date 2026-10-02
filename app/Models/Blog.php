<?php

namespace App\Models;

use App\Services\BlogService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
        'content',
        'published_at',
        'meta_keywords',
        'meta_description',
        'is_home',
        'has_detail_page',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_home' => 'boolean',
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
        static::saved(fn() => app(BlogService::class)->flush());
        static::creating(function (Blog $blog) {
            $blog->user_id = auth()->id();
        });
        static::deleted(fn() => app(BlogService::class)->flush());
        static::restored(fn() => app(BlogService::class)->flush());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return Storage::disk('images')->url($this->image ?: 'default.webp');
    }

    public function getSlugAttribute(): ?string
    {
        return $this->has_detail_page ? $this->getKey() . '-' . Str::slug($this->getTranslation('title', 'en')) : null;
    }
}
