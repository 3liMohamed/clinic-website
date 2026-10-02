<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

abstract class BaseService
{
    abstract protected function cacheKey(): string;

    public function flush(): void
    {
        Cache::forget($this->cacheKey());
    }
}
