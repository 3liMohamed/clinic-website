<?php

namespace App\Models;

use App\Services\ClinicInfoService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ClinicInfo extends Model
{
    use HasTranslations, HasAuditLog, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'icon',
        'sub_title',
        'title',
        'description',
        'link_text',
        'link_url',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public array $translatable = [
        'sub_title',
        'title',
        'description',
        'link_text',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => app(ClinicInfoService::class)->flush());
        static::deleted(fn () => app(ClinicInfoService::class)->flush());
        static::restored(fn () => app(ClinicInfoService::class)->flush());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
