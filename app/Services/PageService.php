<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageService
{
    protected const CACHE_PREFIX = 'pages:';

    public function findByName(string $name): ?Page
    {
        return Cache::rememberForever(self::CACHE_PREFIX . $name, function () use ($name) {
            return Page::query()
                ->where('name', $name)
                ->where('is_visible', true)
                ->first();
        });
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

    public function flush(string $name): void
    {
        Cache::forget(self::CACHE_PREFIX . $name);
    }

    public function flushAll(): void
    {
        Page::query()->pluck('name')->each(fn (string $name) => $this->flush($name));
    }
}
