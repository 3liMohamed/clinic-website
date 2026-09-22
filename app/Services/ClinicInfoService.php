<?php

namespace App\Services;

use App\Models\ClinicInfo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ClinicInfoService
{
    protected const CACHE_KEY = 'clinic-infos:all-visible';
    protected const MAX_VISIBLE = 3;

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return ClinicInfo::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function toggleVisibility(ClinicInfo $clinicInfo, bool $visible): bool
    {
        if ($visible && $this->visibleCount(excluding: $clinicInfo) >= self::MAX_VISIBLE) {
            return false;
        }

        $clinicInfo->update(['is_visible' => $visible]);

        return true;
    }

    public function visibleCount(?ClinicInfo $excluding = null): int
    {
        return $this->allVisible()
            ->when($excluding, fn($collection) => $collection->reject(
                fn(ClinicInfo $item) => $item->is($excluding)
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
