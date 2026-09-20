<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class TranslationService
{
    protected const CACHE_PREFIX = 'translations:';


    public function getGroup(string $group): Collection
    {
        return Cache::rememberForever(
            $this->cacheKey($group),
            fn(): Collection  => Translation::where('group', $group)->get()->keyBy('key'),
        );
    }

    public function get(string $group, string $key): ?string
    {
        $value = $this->getGroup($group)->get($key)?->value;

        return in_array($value, [null, '#'], true) ? '' : $value;
    }

    public function flushGroup(string $group): void
    {
        Cache::forget(self::CACHE_PREFIX . $group);
    }

    public function flushAll(): void
    {
        Translation::distinct()->pluck('group')->each(fn(string $group) => $this->flushGroup($group));
    }

    private function cacheKey(string $group): string
    {
        return self::CACHE_PREFIX . $group;
    }
}
