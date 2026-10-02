<?php

namespace App\Models;

use App\Services\DepartmentService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Department extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'image',
        'icon',
        'name',
        'description',
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
        'name',
        'description',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => app(DepartmentService::class)->flush());

        static::deleted(function (self $department) {
            $department->items()->delete();
            $department->doctors()->delete();

            app(DepartmentService::class)->flush();
        });

        static::restored(function (self $department) {
            $department->items()->onlyTrashed()->restore();
            $department->doctors()->onlyTrashed()->restore();

            app(DepartmentService::class)->flush();
        });

        static::forceDeleted(function (self $department) {
            $department->items()->withTrashed()->forceDelete();
            $department->doctors()->withTrashed()->forceDelete();

            app(DepartmentService::class)->flush();
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        return Storage::disk('images')->url($this->image ?: 'default.webp');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DepartmentItem::class)->ordered();
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }
}
