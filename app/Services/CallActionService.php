<?php

namespace App\Services;

use App\Models\CallAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CallActionService
{
    protected const CACHE_KEY = 'call-action:visible';

    public function visible(): ?CallAction
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return CallAction::query()
                ->visible()
                ->first();
        });
    }

    public function toggleVisibility(CallAction $callAction, bool $visible): bool
    {
        DB::transaction(function () use ($callAction, $visible) {
            if ($visible) {
                CallAction::query()
                    ->whereKeyNot($callAction->getKey())
                    ->visible()
                    ->update(['is_visible' => false]);
            }

            $callAction->update([
                'is_visible' => $visible,
            ]);
        });

        return true;
    }


    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
