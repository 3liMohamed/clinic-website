<?php

namespace App\Models;

use App\Services\SlideService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Slide extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'image',
        'title',
        'description',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public array $translatable = [
        'title',
        'description',
        'primary_button_text',
        'secondary_button_text',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => app(SlideService::class)->flush());
        static::deleted(fn() => app(SlideService::class)->flush());
        static::restored(fn () => app(SlideService::class)->flush());
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('images')->url($this->image) : asset('images/default.webp');
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
