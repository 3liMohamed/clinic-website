<?php

namespace App\Models;

use App\Services\StatisticService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Statistic extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'icon',
        'title',
        'count',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'count' => 'integer',
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    public array $translatable = [
        'title',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => app(StatisticService::class)->flush());
        static::deleted(fn () => app(StatisticService::class)->flush());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
