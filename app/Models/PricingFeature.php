<?php

namespace App\Models;

use App\Services\PricingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class PricingFeature extends Model
{
    use HasTranslations, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'pricing_id',
        'text',
        'is_available',
        'order',
    ];

    public array $translatable = [
        'text',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => app(PricingService::class)->flush());

        static::deleted(fn () => app(PricingService::class)->flush());

        static::restored(fn () => app(PricingService::class)->flush());

    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function pricing(): BelongsTo
    {
        return $this->belongsTo(Pricing::class);
    }
}
