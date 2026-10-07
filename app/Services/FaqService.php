<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class FaqService extends BaseService
{
    protected const CACHE_KEY = 'faqs:all-visible';

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Faq::query()
                ->with('category')
                ->visible()
                ->ordered()
                ->get()
                ->groupBy(fn(Faq $faq) => $faq->category?->name);
        });
    }

    public function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
