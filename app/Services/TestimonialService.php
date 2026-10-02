<?php

namespace App\Services;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TestimonialService extends BaseService
{
    protected const CACHE_KEY = 'testimonials:all-visible';

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Testimonial::query()
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
