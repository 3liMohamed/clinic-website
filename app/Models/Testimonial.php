<?php

namespace App\Models;

use App\Services\TestimonialService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'avatar',
        'name',
        'content',
        'is_visible',
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
        'content',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => app(TestimonialService::class)->flush());
        static::deleted(fn () => app(TestimonialService::class)->flush());
        static::restored(fn () => app(TestimonialService::class)->flush());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function getAvatarUrlAttribute(): string
    {
        return Storage::disk('images')->url($this->avatar ?: 'default.webp');
    }
}
