<?php

namespace App\Services;

use App\Models\Mission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MissionService extends BaseService
{
    protected const CACHE_KEY = 'missions:visible';

    public function visible(): Mission
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Mission::query()
                ->visible()
                ->with([
                    'items' => fn($query) => $query
                        ->visible()
                        ->ordered(),
                ])
                ->first();
        });
    }

    public function makeVisible(Mission $mission, bool $visible): bool
    {
        DB::transaction(function () use ($mission, $visible) {
            if ($visible) {
                Mission::query()
                    ->whereKeyNot($mission->getKey())
                    ->visible()
                    ->update(['is_visible' => false]);
            }

            $mission->update([
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
