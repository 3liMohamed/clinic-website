<?php

namespace App\Services;

use App\Models\Pricing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PricingService extends BaseService
{
    protected const CACHE_KEY = 'pricings:all-visible';

    protected const MAX_VISIBLE = 3;

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Pricing::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function toggleVisibility(Pricing $pricing, bool $visible): bool
    {
        if ($visible && $this->visibleCount(excluding: $pricing) >= self::MAX_VISIBLE) {
            return false;
        }

        $pricing->update(['is_visible' => $visible]);

        return true;
    }

    public function visibleCount(?Pricing $excluding = null): int
    {
        return $this->allVisible()
            ->when($excluding, fn($collection) => $collection->reject(
                fn(Pricing $item) => $item->is($excluding)
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
