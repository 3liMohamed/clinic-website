<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PageService
{
    protected const CACHE_KEY = 'pages:all-visible';

    /**
     * Get all visible pages, ordered for navigation display.
     * Single source of truth - cached once, everything else reads from this.
     */
    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Page::query()
                ->where('is_visible', true)
                ->orderBy('order')
                ->get();
        });
    }

    public function findByName(string $name): ?Page
    {
        return $this->allVisible()
            ->firstWhere('name', $name);
    }

    public function findByNameOrFail(string $name): Page
    {
        $page = $this->findByName($name);

        abort_unless($page, 404);

        return $page;
    }

    public function isSectionVisible(string $pageName, string $sectionName): bool
    {
        $page = $this->findByName($pageName);

        if (! $page) {
            return false;
        }

        return $page->isSectionVisible($sectionName);
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
