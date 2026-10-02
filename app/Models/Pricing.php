<?php

namespace App\Models;

use App\Enums\PriceSuffix;
use App\Services\PricingService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Pricing extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

        protected $fillable = [
        'icon',
        'title',
        'price',
        'price_suffix',
        'button_text',
        'button_url',
        'is_visible',
        'order',
    ];

    public array $translatable = [
        'title',
        'button_text',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_suffix' => PriceSuffix::class,
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => app(PricingService::class)->flush());

        static::deleted(function (self $pricing) {
            $pricing->features()->delete();

            app(PricingService::class)->flush();
        });

        static::restored(function (self $pricing) {
            $pricing->features()->onlyTrashed()->restore();

            app(PricingService::class)->flush();
        });

        static::forceDeleted(function (self $pricing) {
            $pricing->features()->withTrashed()->forceDelete();

            app(PricingService::class)->flush();
        });
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function features(): HasMany
    {
        return $this->hasMany(PricingFeature::class)->ordered();
    }
}
