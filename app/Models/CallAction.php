<?php

namespace App\Models;

use App\Services\CallActionService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class CallAction extends Model
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
        static::saved(fn () => app(CallActionService::class)->flush());
        static::deleted(fn () => app(CallActionService::class)->flush());
        static::restored(fn () => app(CallActionService::class)->flush());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('images')->url($this->image) : asset('images/default.webp');
    }
}
