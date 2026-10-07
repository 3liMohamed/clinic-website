<?php

namespace App\Services;

use App\Models\AboutUs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AboutUsService extends BaseService
{
    protected const CACHE_KEY = 'about-us:visible';

    public function visible(): ?AboutUs
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return AboutUs::query()
                ->visible()
                ->with([
                    'items' => fn($query) => $query
                        ->visible()
                        ->ordered(),
                ])
                ->first();
        });
    }

    public function makeVisible(AboutUs $aboutUs, bool $visible): bool
    {
        DB::transaction(function () use ($aboutUs, $visible) {
            if ($visible) {
                AboutUs::query()
                    ->whereKeyNot($aboutUs->getKey())
                    ->visible()
                    ->update(['is_visible' => false]);
            }

            $aboutUs->update([
                'is_visible' => $visible,
            ]);
        });

        return true;
    }

    public function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
