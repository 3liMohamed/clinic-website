<?php

namespace App\Models;

use App\Services\WhyChooseService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class WhyChoose extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'image',
        'title',
        'content',
        'video_url',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public array $translatable = [
        'title',
        'content',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => app(WhyChooseService::class)->flush());

        static::deleted(function (self $whyChoose) {
            $whyChoose->items()->delete();

            app(WhyChooseService::class)->flush();
        });

        static::restored(function (self $whyChoose) {
            $whyChoose->items()->onlyTrashed()->restore();

            app(WhyChooseService::class)->flush();
        });

        static::forceDeleted(function (self $whyChoose) {
            $whyChoose->items()->withTrashed()->forceDelete();

            app(WhyChooseService::class)->flush();
        });
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('images')->url($this->image) : asset('images/default.webp');
    }

    public function items(): HasMany
    {
        return $this->hasMany(WhyChooseItem::class)->ordered();
    }
}
