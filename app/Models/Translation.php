<?php

namespace App\Models;

use App\Services\TranslationService;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Translation extends Model
{
    use HasTranslations, HasAuditLog;
    public $timestamps = false;

    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    public $translatable = [
        'value',
    ];

    protected static function booted(): void
    {
        static::saved(function (Translation $translation): void {
            app(TranslationService::class)->flushGroup($translation->group);
        });
        static::deleted(function (Translation $translation): void {
            app(TranslationService::class)->flushGroup($translation->group);
        });
    }
}
