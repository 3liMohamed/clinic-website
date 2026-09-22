<?php

namespace App\Models;

use App\Services\PageService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations, HasAuditLog;
    public $timestamps = false;


    protected $fillable = [
        'image',
        'name',
        'header_title',
        'nav_title',
        'is_visible',
        'show_in_main_nav',
        'show_in_top_nav',
        'is_cta',
        'meta_keywords',
        'meta_description',
        'sections',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'show_in_main_nav' => 'boolean',
            'show_in_top_nav' => 'boolean',
            'is_cta' => 'boolean',
            'sections' => 'array',
        ];
    }

    public array $translatable = [
        'header_title',
        'nav_title',
        'meta_keywords',
        'meta_description',
    ];

    protected static function booted(): void
    {

        static::saving(function (self $page) {
            if ($page->is_cta && $page->isDirty('is_cta')) {
                static::where('id', '!=', $page->id)
                    ->where('is_cta', true)
                    ->update(['is_cta' => false]);
            }
        });
        static::saved(fn() => app(PageService::class)->flush());
        static::deleted(fn() => app(PageService::class)->flush());
    }
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('images')->url($this->image) : asset('images/default.webp');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function isInMainNav(): bool
    {
        return $this->show_in_main_nav;
    }

    public function isInTopNav(): bool
    {
        return $this->show_in_top_nav;
    }

    public function isCta(): bool
    {
        return $this->is_cta;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function isSectionVisible(string $name): bool
    {
        $section = collect($this->sections ?? [])->firstWhere('name', $name);

        return $section['is_visible'] ?? false;
    }
}
