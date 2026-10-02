<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class DepartmentService extends BaseService
{
    protected const CACHE_KEY = 'departments:all-visible';

    protected const MAX_VISIBLE = 5;

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Department::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function toggleVisibility(Department $department, bool $visible): bool
    {
        if ($visible && $this->visibleCount(excluding: $department) >= self::MAX_VISIBLE) {
            return false;
        }

        $department->update(['is_visible' => $visible]);

        return true;
    }

    public function visibleCount(?Department $excluding = null): int
    {
        return $this->allVisible()
            ->when($excluding, fn($collection) => $collection->reject(
                fn(Department $item) => $item->is($excluding)
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
