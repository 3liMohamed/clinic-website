<?php

namespace App\Services;

use App\Models\Vision;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class VisionService extends BaseService
{
    protected const CACHE_KEY = 'visions:all-visible';

    protected const MAX_VISIBLE = 3;

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Vision::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function toggleVisibility(Vision $vision, bool $visible): bool
    {
        if ($visible && $this->visibleCount(excluding: $vision) >= self::MAX_VISIBLE) {
            return false;
        }

        $vision->update(['is_visible' => $visible]);

        return true;
    }

    public function visibleCount(?Vision $excluding = null): int
    {
        return $this->allVisible()
            ->when($excluding, fn($collection) => $collection->reject(
                fn(Vision $item) => $item->is($excluding)
            ))
            ->count();
    }

    public function maxVisible(): int
    {
        return self::MAX_VISIBLE;
    }

    public function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
