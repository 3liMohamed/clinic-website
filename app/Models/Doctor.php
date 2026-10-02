<?php

namespace App\Models;

use App\Services\DoctorService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Doctor extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'image',
        'name',
        'biography',
        'department_id',
        'phone',
        'email',
        'address',
        'socials',
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
            'socials' => 'array',
            'is_visible' => 'boolean',
            'is_home' => 'boolean',
            'has_detail_page' => 'boolean',
            'order' => 'integer',
        ];
    }

    public array $translatable = [
        'name',
        'biography',
        'meta_keywords',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => app(DoctorService::class)->flush());
        static::deleted(function (self $doctor) {
            $doctor->workingHours()->delete();

            app(DoctorService::class)->flush();
        });

        static::restored(function (self $doctor) {
            $doctor->workingHours()->onlyTrashed()->restore();

            app(DoctorService::class)->flush();
        });

        static::forceDeleted(function (self $doctor) {
            $doctor->workingHours()->withTrashed()->forceDelete();

            app(DoctorService::class)->flush();
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(DoctorWorkingHour::class);
    }

    public function getSlugAttribute(): ?string
    {
        return $this->has_detail_page ? $this->getKey() . '-' . Str::slug($this->getTranslation('name', 'en')) : null;
    }
}
