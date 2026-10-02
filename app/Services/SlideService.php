<?php

namespace App\Services;

use App\Models\Slide;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class SlideService extends BaseService
{
    protected const CACHE_KEY = 'slides:all-visible';

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Slide::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
