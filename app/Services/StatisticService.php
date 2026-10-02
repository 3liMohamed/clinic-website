<?php

namespace App\Services;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class StatisticService extends BaseService
{
    protected const CACHE_KEY = 'statistics:all-visible';

    protected const MAX_VISIBLE = 4;

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Statistic::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function toggleVisibility(Statistic $statistic, bool $visible): bool
    {
        if ($visible && $this->visibleCount(excluding: $statistic) >= self::MAX_VISIBLE) {
            return false;
        }

        $statistic->update(['is_visible' => $visible]);

        return true;
    }

    public function visibleCount(?Statistic $excluding = null): int
    {
        return $this->allVisible()
            ->when($excluding, fn($collection) => $collection->reject(
                fn(Statistic $item) => $item->is($excluding)
            ))
            ->count();
    }

    public function maxVisible(): int
    {
        return self::MAX_VISIBLE;
    }

    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
