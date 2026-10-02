<?php

namespace App\Services;

use App\Models\WhyChoose;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WhyChooseService extends BaseService
{
    protected const CACHE_KEY = 'why-choose:visible-with-items';

    public function visible(): ?WhyChoose
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return WhyChoose::query()
                ->visible()
                ->with('items')
                ->first();
        });
    }

    public function toggleVisibility(WhyChoose $whyChoose, bool $visible): bool
    {
        DB::transaction(function () use ($whyChoose, $visible) {
            if ($visible) {
                WhyChoose::query()
                    ->whereKeyNot($whyChoose->getKey())
                    ->visible()
                    ->update(['is_visible' => false]);
            }

            $whyChoose->update([
                'is_visible' => $visible,
            ]);
        });

        return true;
    }


    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
