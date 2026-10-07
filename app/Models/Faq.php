<?php

namespace App\Models;

use App\Services\FaqService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    public array $translatable = [
        'question',
        'answer',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => app(FaqService::class)->flush());
        static::deleted(fn() => app(FaqService::class)->flush());
        static::restored(fn() => app(FaqService::class)->flush());
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
