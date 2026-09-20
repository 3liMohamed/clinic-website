<?php

namespace App\Models;

use App\Services\PageService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations, HasAuditLog;
    public $timestamps = false;


    protected $fillable = [
        'image',
        'name',
        'title',
        'is_visible',
        'meta_keywords',
        'meta_description',
        'sections',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sections' => 'array',
        ];
    }

    public array $translatable = [
        'title',
        'meta_keywords',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $page) {
            app(PageService::class)->flush($page->name);
            if ($page->wasChanged('name') && $page->getOriginal('name')) {
                app(PageService::class)->flush($page->getOriginal('name'));
            }
        });
        static::deleted(fn(self $page) => app(PageService::class)->flush($page->name));
    }
    public function getImageUrlAttribute(): ?string
    {
        return asset("images/{$this->image}");
    }

    public function isSectionVisible(string $name): bool
    {
        $section = collect($this->sections ?? [])->firstWhere('name', $name);

        return $section['is_visible'] ?? true;
    }
}
