<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'settings.all';

    public function getAll(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn(): array => Setting::pluck('value', 'key')->toArray());
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->getAll()[$key] ?? $default;
    }

    public function set(string $key, mixed $value): Setting
    {
        $setting = Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
        return $setting;
    }
}
