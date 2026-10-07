<?php

namespace App\Models;

use App\Services\MissionService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Mission extends Model
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
        static::saved(fn () => app(MissionService::class)->flush());

        static::deleted(function (self $mission) {
            $mission->items()->delete();

            app(MissionService::class)->flush();
        });

        static::restored(function (self $mission) {
            $mission->items()->onlyTrashed()->restore();

            app(MissionService::class)->flush();
        });

        static::forceDeleted(function (self $mission) {
            $mission->items()->withTrashed()->forceDelete();

            app(MissionService::class)->flush();
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
        return $this->hasMany(MissionItem::class);
    }
}
