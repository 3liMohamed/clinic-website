<?php

namespace App\Services;

use App\Models\WebsiteGallery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WebsiteGalleryService
{
    protected const CACHE_KEY = 'website_gallery:all';

    public function getAll(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return WebsiteGallery::query()->get();
        });
    }

    public function getByKey(string $key): ?WebsiteGallery
    {
        return $this->getAll()
            ->where('key', $key)
            ->first();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
