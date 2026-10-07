<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ClientService extends BaseService
{
    protected const CACHE_KEY = 'clients:all-visible';

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Client::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
