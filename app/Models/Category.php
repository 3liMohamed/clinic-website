<?php

namespace App\Models;

use App\Services\PortfolioService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public array $translatable = [
        'name'
    ];

    protected static function booted(): void
    {
        static::deleted(function (self $category) {
            app(PortfolioService::class)->flush();
            $category->portfolios()->delete();
        });

        static::restored(function (self $category) {
            $category->portfolios()->onlyTrashed()->restore();
            app(PortfolioService::class)->flush();
        });

        static::forceDeleted(function (self $category) {
            $category->portfolios()->withTrashed()->forceDelete();
            app(PortfolioService::class)->flush();
        });
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }
}
