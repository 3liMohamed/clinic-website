<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PageService extends BaseService
{
    protected const CACHE_KEY = 'pages:all-visible';

    /**
     * Get all visible pages ordered by their display order.
     */
    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Page::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    /**
     * Get all visible main pages.
     */
    public function allVisibleMain(): Collection
    {
        return $this->allVisible()
            ->filter(fn(Page $page): bool => $page->isMainPage())
            ->values();
    }

    /**
     * Get all visible details pages.
     */
    public function allVisibleDetails(): Collection
    {
        return $this->allVisible()
            ->filter(fn(Page $page): bool => $page->isDetailsPage())
            ->values();
    }

    /**
     * Find a visible main page by name.
     */
    public function findMainByName(string $name): ?Page
    {
        return $this->allVisibleMain()
            ->firstWhere('name', $name);
    }

    /**
     * Find a visible main page by name or abort with 404.
     */
    public function findMainByNameOrFail(string $name): Page
    {
        $page = $this->findMainByName($name);

        abort_unless($page, 404);

        return $page;
    }

    /**
     * Find a visible details page by name.
     */
    public function findDetailsByName(string $name): ?Page
    {
        return $this->allVisibleDetails()
            ->firstWhere('name', $name);
    }

    /**
     * Find a visible details page by name or abort with 404.
     */
    public function findDetailsByNameOrFail(string $name): Page
    {
        $page = $this->findDetailsByName($name);

        abort_unless($page, 404);

        return $page;
    }

    /**
     * Get visible sections for a page.
     */
    public function sections(Page $page): array
    {
        return collect($page->sections ?? [])
            ->filter(
                fn(array $section): bool =>
                $page->isSectionVisible($section['name'])
            )
            ->map(function (array $section) use ($page): ?array {
                $config = config(
                    "pages.{$page->name}.sections.{$section['name']}"
                );

                if (! $config) {
                    return null;
                }

                return [
                    'component' => $config['component'],
                    'requires_entity' => $config['requires_entity'] ?? false,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
