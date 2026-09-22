<?php

namespace App\Services;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class FeatureService
{
    protected const CACHE_KEY = 'Feature:all-visible';

    protected const MAX_VISIBLE = 3;


    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Feature::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function toggleVisibility(Feature $feature, bool $visible): bool
    {
        if ($visible && $this->visibleCount(excluding: $feature) >= self::MAX_VISIBLE) {
            return false;
        }

        $feature->update(['is_visible' => $visible]);

        return true;
    }

    public function visibleCount(?Feature $excluding = null): int
    {
        return $this->allVisible()
            ->when($excluding, fn($collection) => $collection->reject(
                fn(Feature $item) => $item->is($excluding)
            ))
            ->count();
    }

    public function maxVisible(): int
    {
        return self::MAX_VISIBLE;
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
