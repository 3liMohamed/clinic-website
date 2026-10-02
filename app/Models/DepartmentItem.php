<?php

namespace App\Models;

use App\Services\DepartmentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class DepartmentItem extends Model
{
    use HasTranslations, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'department_id',
        'text',
        'order',
    ];

    public array $translatable = [
        'text',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn() => app(DepartmentService::class)->flush());
        static::deleted(fn() => app(DepartmentService::class)->flush());
        static::restored(fn() => app(DepartmentService::class)->flush());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
